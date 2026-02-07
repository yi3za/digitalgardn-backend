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
        $services = Service::with('user', 'fichierPrincipale')->where('statut', 'publie')->whereHas('user', fn($q) => $q->where('status', 'actif'))->get();
        // Retourne la liste au format JSON avec le code HTTP 200
        return response()->json(['services' => $services], 200);
    }
    /**
     * Affiche un service specifique
     */
    public function show(Service $service)
    {
        // Si le service n'est pas publie ou l'utilisateur n'est pas actif, retourne 404
        if ($service->statut !== 'publie' || $service->user->status !== 'actif') {
            return response()->json([], 404);
        }
        /**
         * Recupere un service specifique publie avec :
         * - leur utilisateur actif
         * - tous ses fichiers
         * - toutes ses categories (enfants avec leur parent), tous actifs
         */
        $service->load(['user', 'fichiers', 'categories' => fn($q) => $q->with('parent')->whereHas('parent', fn($q) => $q->where('est_active', true))->where('est_active', true)]);
        // Retourne le service au format JSON avec le code HTTP 200
        return response()->json(['service' => $service], 200);
    }
}
