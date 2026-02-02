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
}
