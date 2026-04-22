<?php

namespace App\Http\Controllers\Account;

use App\Constants\TableStates\ServiceStatusState;
use App\Constants\TableStates\TransactionTypeState;
use App\Constants\TableStates\UserStatusState;
use App\Helpers\ApiCodes;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Account\StoreCommandeRequest;
use App\Http\Resources\CommandeResource;
use App\Models\Commande;
use App\Models\Portefeuille;
use App\Models\Service;
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
                $query->where('client_id', $user->id)
                    ->orWhere('freelance_id', $user->id);
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
            // Cree les traces de transaction cote client et freelance
            $clientPortefeuille->transactions()->create([
                'commande_id' => $commande->id,
                'type' => TransactionTypeState::ACHAT,
                'montant' => $montant,
            ]);
            $freelancePortefeuille->transactions()->create([
                'commande_id' => $commande->id,
                'type' => TransactionTypeState::GAIN,
                'montant' => $montant,
            ]);
            return $commande->load(['client', 'freelance', 'service.fichierPrincipale', 'transactions']);
        });
        if (!$commande) {
            return ApiResponse::send(ApiCodes::BAD_REQUEST, 400);
        }
        // Retourne la commande creee
        return ApiResponse::send(ApiCodes::SUCCESS, 201, [
            'commande' => new CommandeResource($commande),
        ]);
    }
}
