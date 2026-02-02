<?php

namespace App\Http\Controllers\Freelance\Catalog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Http\Requests\Freelance\Catalog\ServiceRequest;

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
    public function store(ServiceRequest $request)
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
}
