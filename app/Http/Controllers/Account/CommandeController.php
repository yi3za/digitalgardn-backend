<?php

namespace App\Http\Controllers\Account;

use App\Constants\TableStates\CommandeStatusState;
use App\Constants\TableStates\ServiceStatusState;
use App\Constants\TableStates\TransactionTypeState;
use App\Constants\TableStates\UserStatusState;
use App\Events\CommandeStatusUpdated;
use App\Events\ConversationCreated;
use App\Helpers\ApiCodes;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Account\StoreCommandeRequest;
use App\Http\Resources\CommandeResource;
use App\Models\Commande;
use App\Models\Portefeuille;
use App\Models\Service;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Gestion des commandes de l'utilisateur connecte
 */
class CommandeController extends Controller
{
    /**
     * Liste les commandes ou l'utilisateur connecte est client ou freelance
     */
    public function index(Request $request)
    {
        // Recupere l'utilisateur connecte
        $user = $request->user();
        // Recupere les commandes ou l'utilisateur est client ou freelance
        $commandes = Commande::with(['client', 'freelance', 'service.fichierPrincipale'])
            ->where(function ($query) use ($user) {
                $query->where('client_id', $user->id)->orWhere('freelance_id', $user->id);
            })
            ->orderByDesc('created_at')
            ->get();
        // Retourne la liste des commandes
        return ApiResponse::send(ApiCodes::SUCCESS, 200, [
            'commandes' => CommandeResource::collection($commandes),
        ]);
    }
    /**
     * Affiche une commande precise si l'utilisateur y participe
     */
    public function show(Request $request, Commande $commande)
    {
        // Recupere l'utilisateur connecte
        $user = $request->user();
        // Verifie que l'utilisateur appartient a la commande
        if ($commande->client_id !== $user->id && $commande->freelance_id !== $user->id) {
            return ApiResponse::send(ApiCodes::FORBIDDEN, 403);
        }
        // Charge les relations utiles pour l'affichage
        $commande->load(['client', 'freelance', 'service.fichierPrincipale', 'transactions']);
        // Retourne la commande
        return ApiResponse::send(ApiCodes::SUCCESS, 200, [
            'commande' => new CommandeResource($commande),
        ]);
    }
    /**
     * Cree une nouvelle commande pour un service public publie
     */
    public function store(StoreCommandeRequest $request)
    {
        // Recupere l'utilisateur et les donnees validees
        $user = $request->user();
        $data = $request->validated();
        // Recupere le service cible avec son proprietaire
        $service = Service::with('user')->find($data['service_id']);
        // Verifie que le service est achetable
        if (!$service || $service->statut !== ServiceStatusState::PUBLIE || $service->user?->status !== UserStatusState::ACTIF) {
            return ApiResponse::send(ApiCodes::NOT_FOUND, 404);
        }
        // Interdit l'achat de son propre service
        if ($service->user_id === $user->id) {
            return ApiResponse::send(ApiCodes::FORBIDDEN, 403);
        }
        // Montant de la commande base sur le prix du service
        $montant = (float) $service->prix_base;
        // Execute l'achat dans une transaction DB pour garder la coherence
        $commande = DB::transaction(function () use ($user, $service, $data, $montant) {
            $clientPortefeuille = Portefeuille::firstOrCreate(['user_id' => $user->id]);
            $freelancePortefeuille = Portefeuille::firstOrCreate(['user_id' => $service->user_id]);
            $clientPortefeuille = Portefeuille::whereKey($clientPortefeuille->id)->lockForUpdate()->first();
            $freelancePortefeuille = Portefeuille::whereKey($freelancePortefeuille->id)->lockForUpdate()->first();
            // Stoppe la creation si le client n'a pas assez de solde
            if (!$clientPortefeuille || !$freelancePortefeuille || (float) $clientPortefeuille->solde_disponible < $montant) {
                return null;
            }
            // Cree la commande
            $commande = Commande::create([
                'client_id' => $user->id,
                'freelance_id' => $service->user_id,
                'service_id' => $service->id,
                'montant' => $montant,
                'instructions' => $data['instructions'] ?? null,
                'date_livraison' => Carbon::now()->addDays((int) $service->delai_livraison),
            ]);
            // Met a jour les soldes en attente pour les deux parties
            $clientPortefeuille->decrement('solde_disponible', $montant);
            $clientPortefeuille->increment('solde_en_attente', $montant);
            $freelancePortefeuille->increment('solde_en_attente', $montant);
            // Cree les traces de transaction cote client
            $clientPortefeuille->transactions()->create([
                'commande_id' => $commande->id,
                'type' => TransactionTypeState::ACHAT,
                'montant' => $montant,
            ]);
            // Cree conversation liee a la commande
            $conversation = $commande->conversation()->create([
                'sender_id' => $user->id,
                'receiver_id' => $service->user_id,
                'last_message_at' => now(),
            ]);
            // Si des instructions sont fournies, les envoie dans la conversation liee a la commande
            $conversation->messages()->create([
                'sender_id' => $user->id,
                'content' => $data['instructions'] ?? '--',
            ]);
            // Charge les relations utiles pour l'affichage
            $commande->load('conversation');
            // Retourne la commande creee
            return $commande;
        });
        // Si la creation a echoue (ex: solde insuffisant), retourne une erreur
        if (!$commande) {
            return ApiResponse::send(ApiCodes::BAD_REQUEST, 400);
        }
        // Apres validation et creation de la commande, broadcast de l'evenements
        DB::afterCommit(function () use ($commande) {
            broadcast(new ConversationCreated($commande->conversation))->toOthers();
        });
        // Retourne la commande creee
        return ApiResponse::send(ApiCodes::SUCCESS, 201, [
            'commande' => new CommandeResource($commande),
        ]);
    }
    /**
     * Met a jour le statut d'une commande
     */
    public function updateStatus(Request $request, Commande $commande)
    {
        // Recupere l'utilisateur connecte
        $user = $request->user();
        // Verifie que l'utilisateur appartient a la commande
        if ($commande->client_id !== $user->id && $commande->freelance_id !== $user->id) {
            return ApiResponse::send(ApiCodes::FORBIDDEN, 403);
        }
        // Verifier que l'utilisateur connecte est vendeur ou acheteur de la commande
        $isVendeur = $user->id === $commande->freelance_id;
        // Valide le nouveau statut selon le role
        $targetStatuses = $isVendeur ? CommandeStatusState::freelanceTargetStatuses() : CommandeStatusState::clientTargetStatuses();
        // Valide que le statut cible est dans les transitions autorisees pour le statut actuel
        $data = $request->validate([
            'statut' => ['required', 'in:' . implode(',', $targetStatuses)],
        ]);
        // Verifie que la transition est autorisee pour le role de l'utilisateur
        $transitions = $isVendeur ? CommandeStatusState::freelanceTransitions() : CommandeStatusState::clientTransitions();
        // Recupere les statuts cibles autorises pour le statut actuel
        $autorises = $transitions[$commande->statut] ?? [];
        if (!in_array($data['statut'], $autorises)) {
            return ApiResponse::send(ApiCodes::FORBIDDEN, 403);
        }
        // Transfert de soldes + mise a jour statut dans une transaction DB
        return DB::transaction(function () use ($commande, $data) {
            // Si en_revision, incrementer le compteur
            if ($data['statut'] === CommandeStatusState::EN_REVISION) {
                // Verifie que le nombre de revisions n'est pas depasse
                if ($commande->revisions_utilisees >= $commande->service->revisions) {
                    return ApiResponse::send(ApiCodes::BAD_REQUEST, 400);
                }
                $commande->increment('revisions_utilisees');
            }
            // Met a jour le statut de la commande
            $commande->statut = $data['statut'];
            $commande->save();
            // Effectue les operations de debloquage ou remboursement selon le nouveau statut
            match ($data['statut']) {
                CommandeStatusState::TERMINEE => $this->debloquer($commande),
                CommandeStatusState::ANNULEE => $this->rembourser($commande),
                default => null,
            };
            // Broadcast
            broadcast(new CommandeStatusUpdated($commande))->toOthers();
            // Retourne la commande mise a jour
            return ApiResponse::send(ApiCodes::SUCCESS, 200, [
                'commande' => new CommandeResource($commande),
            ]);
        });
    }
    /**
     * Debloque les fonds apres terminee
     *
     * lors de l'achat :
     * client.solde_disponible    - montant
     * client.solde_en_attente    + montant
     * freelance.solde_en_attente + montant
     *
     * lors de terminee :
     * client.solde_en_attente    - montant
     * freelance.solde_en_attente - montant
     * freelance.solde_disponible + montant
     */
    private function debloquer(Commande $commande): void
    {
        // Retire de solde_en_attente du client
        Portefeuille::where('user_id', $commande->client_id)->decrement('solde_en_attente', (float) $commande->montant);
        // Retire de solde_en_attente du freelance
        Portefeuille::where('user_id', $commande->freelance_id)->decrement('solde_en_attente', (float) $commande->montant);
        // Ajoute a solde_disponible du freelance
        Portefeuille::where('user_id', $commande->freelance_id)->increment('solde_disponible', (float) $commande->montant);
        // Enregistre la transaction
        Transaction::create([
            'portefeuille_id' => Portefeuille::where('user_id', $commande->freelance_id)->value('id'),
            'commande_id' => $commande->id,
            'type' => TransactionTypeState::GAIN,
            'montant' => $commande->montant,
        ]);
    }
    /**
     * Rembourse le client apres annulee
     *
     * lors de annulee :
     * client.solde_en_attente    - montant
     * client.solde_disponible    + montant
     * freelance.solde_en_attente - montant
     */
    private function rembourser(Commande $commande): void
    {
        // Retire de solde_en_attente du freelance
        Portefeuille::where('user_id', $commande->freelance_id)->decrement('solde_en_attente', (float) $commande->montant);
        // Retire de solde_en_attente du client
        Portefeuille::where('user_id', $commande->client_id)->decrement('solde_en_attente', (float) $commande->montant);
        // Rembourse le client sur solde_disponible
        Portefeuille::where('user_id', $commande->client_id)->increment('solde_disponible', (float) $commande->montant);
        // Enregistre la transaction
        Transaction::create([
            'portefeuille_id' => Portefeuille::where('user_id', $commande->client_id)->value('id'),
            'commande_id' => $commande->id,
            'type' => TransactionTypeState::REMBOURSEMENT,
            'montant' => $commande->montant,
        ]);
    }
}
