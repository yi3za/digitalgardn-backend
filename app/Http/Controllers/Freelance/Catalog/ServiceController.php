<?php

namespace App\Http\Controllers\Freelance\Catalog;

use App\Helpers\ApiCodes;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Http\Requests\Freelance\Catalog\StoreServiceRequest;
use App\Http\Requests\Freelance\Catalog\UpdateServiceRequest;
use App\Http\Requests\Freelance\Catalog\SyncCategoriesRequest;
use App\Http\Requests\Freelance\Catalog\SyncCompetencesRequest;
use App\Http\Requests\Freelance\Catalog\SyncFichiersRequest;

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
        return ApiResponse::send(ApiCodes::SUCCESS, 200, ['services' => $services]);
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
        return ApiResponse::send(ApiCodes::SUCCESS, 200, ['service' => $service]);
    }
    /**
     * Affiche un service specifique
     */
    public function show(Request $request, Service $service)
    {
        // Recupere l'utilisateur authentifie
        $user = $request->user();
        // Verifie que le service appartient a l'utilisateur
        if ($service->user_id !== $user->id) {
            return ApiResponse::send(ApiCodes::NOT_FOUND, 404);
        }
        // Charger les relations
        $service->load(['fichiers', 'categories' => fn($q) => $q->where('est_active', true)]);
        // Retourne le service
        return ApiResponse::send(ApiCodes::SUCCESS, 200, ['service' => $service]);
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
        return ApiResponse::send(ApiCodes::SUCCESS, 200, ['service' => $service]);
    }
    /**
     * Supprimer un service existant
     */
    public function destroy(Service $service, Request $request)
    {
        // Verifie si l'utilisateur connecte est le proprietaire du service
        if ($request->user()->id !== $service->user_id) {
            return ApiResponse::send(ApiCodes::FORBIDDEN, 403);
        }
        // Supprime le service de la base de donnees
        $service->delete();
        // Retourne une response vide pour confirmer le suppression
        return ApiResponse::send(ApiCodes::SUCCESS, 200);
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
        return ApiResponse::send(ApiCodes::SUCCESS, 200);
    }
    /**
     * Gestion des competences de services
     */
    public function syncCompetences(Service $service, SyncCompetencesRequest $request)
    {
        // Recupere les IDs des competences validees
        $competencesIds = $request->validated('competences');
        // Supprime les anciennes relations et ajoute les nouvelles
        $service->competences()->sync($competencesIds);
        // Retourne une response succes
        return ApiResponse::send(ApiCodes::SUCCESS, 200);
    }
    /**
     * Gestion des fichiers de services
     */
    public function syncFichiers(Service $service, SyncFichiersRequest $request)
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
        return ApiResponse::send(ApiCodes::SUCCESS, 200);
    }
}
