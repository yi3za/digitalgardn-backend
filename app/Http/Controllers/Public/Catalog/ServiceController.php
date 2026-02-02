<?php

namespace App\Http\Controllers\Public\Catalog;

use App\Http\Controllers\Controller;
use App\Models\Service;

/**
 * Gerer les services publiques
 */
class ServiceController extends Controller
{
    /**
     * Liste tous les services
     */
    public function index()
    {
        /**
         * Recupere tous les sevices publies avec :
         * - leur utilisateur actif
         * - leur fichier principale
         */
        $services = Service::with('user', 'fichierPrincipale')
            ->where('statut', 'publie')
            ->whereHas('user', function ($query) {
                $query->where('status', 'actif');
            })
            ->get();
        // Retourne la liste au format JSON avec le code HTTP 200
        return response()->json(['services' => $services], 200);
    }
    /**
     * Affiche un service specifique
     */
    public function show($slug)
    {
        /**
         * Recupere un service specifique publie avec :
         * - leur utilisateur actif
         * - tous ses fichiers
         * - toutes ses categories (enfants avec leur parent)
         */
        $service = Service::with([
            'user',
            'fichiers',
            'categories' => function ($query) {
                $query->with('parent');
            },
        ])
            ->where(['slug' => $slug, 'statut' => 'publie'])
            ->whereHas('user', function ($query) {
                $query->where('status', 'actif');
            })
            ->first();
        // Si le service n'existe pas, retourne une erreur HTTP 404
        if (!$service) {
            return response()->json([], 404);
        }
        // Retourne le service au format JSON avec le code HTTP 200
        return response()->json(['service' => $service], 200);
    }
}
