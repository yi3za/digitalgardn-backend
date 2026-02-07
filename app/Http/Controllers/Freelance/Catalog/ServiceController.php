<?php

namespace App\Http\Controllers\Freelance\Catalog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Http\Requests\Freelance\Catalog\StoreServiceRequest;
use App\Http\Requests\Freelance\Catalog\SyncCategoriesRequest;
use App\Http\Requests\Freelance\Catalog\UpdateServiceRequest;
use App\Http\Requests\Freelance\Catalog\AjouterFichiersRequest;

/**
 * Gerer les services du freelance connecte
 */
class ServiceController extends Controller
{
    /**
     * Liste tous ses services (tous les statuts confondus)
     */
    public function index(Request $request)
    {
        // Recupere le freelance connecte
        $user = $request->user();
        // Recupere tous ses services avec leur fichier principale
        $services = Service::with('fichierPrincipale')->where('user_id', $user->id)->orderByDesc('updated_at')->get();
        // Retourner la liste
        return response()->json(['services' => $services], 200);
    }
    /**
     * Creation d'un service par le freelance
     */
    public function store(StoreServiceRequest $request)
    {
        // Recuperer les donnees validees
        $data = $request->validated();
        // Creer un nouveau service
        $service = Service::create($data);
        // Retourner la service cree
        return response()->json(['service' => $service], 200);
    }
    /**
     * Affiche un service specifique
     */
    public function show(Request $request, $slug)
    {
        // Recupere l'utilisateur authentifie
        $user = $request->user();
        // Cherche le service correspondant a l'utilisateur connecte
        $service = Service::with(['fichiers', 'categories' => fn($q) => $q->where('est_active', true)])
            ->where(['user_id' => $user->id, 'slug' => $slug])
            ->first();
        // Si le service n'existe pas, retourne une erreur HTTP 404
        if (!$service) {
            return response()->json([], 404);
        }
        // Sinon retourne le service
        return response()->json(['service' => $service], 200);
    }
    /**
     * Mettre a jour les informations d'un service
     */
    public function update(Service $service, UpdateServiceRequest $request)
    {
        // Recuperer les donnees validees
        $data = $request->validated();
        // Mettre a jour le service avec les donnees fournies
        $service->update($data);
        // Retourner le service mis a jour
        return response()->json(['service' => $service], 200);
    }
    /**
     * Supprimer un service existant
     */
    public function destroy(Service $service, Request $request)
    {
        // Verifie si l'utilisateur connecte est le proprietaire du service
        if ($request->user()->id !== $service->user_id) {
            return response()->json([], 403);
        }
        // Supprime le service de la base de donnees
        $service->delete();
        // Retourne une response vide pour confirmer le suppression
        return response()->noContent();
    }
    /**
     * Gestion des categories de services
     */
    public function syncCategories(Service $service, SyncCategoriesRequest $request)
    {
        // Recupere les IDs des categories validees
        $categoriesIds = $request->validated('categories');
        // Supprime les anciennes relations et ajoute les nouvelles
        $service->categories()->sync($categoriesIds);
        // Retourne une response succes
        return response()->json([], 200);
    }
    /**
     * Gestion des fichiers de services
     */
    public function syncFichiers(Service $service, AjouterFichiersRequest $request)
    {
        // Nettoyer les fichiers existants
        $fichiersExists = $service->fichiers;
        // Parcourir chaque fichier
        foreach ($fichiersExists as $fichier) {
            // Supprimer le fichier
            $fichier->delete();
        }
        // Recupere les fichiers valides
        $fichiers = $request->validated('fichiers');
        // Preparer les donnees a inserer
        $data = [];
        // Parcourir chaque fichier
        foreach ($fichiers as $index => $fichier) {
            // Stocke le fichier et recupere le chemin
            $chemin = $fichier->store('services/images', 'public');
            // Ajouter les informations de chaque fichier
            $data[] = [
                'chemin' => $chemin,
                'ordre' => $index,
                'est_principale' => $index === 0,
            ];
        }
        // Cree plusieures enregistrements d'un coup
        $service->fichiers()->createMany($data);
        // Retourne une response succes
        return response()->json([], 200);
    }
}
