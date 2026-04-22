<?php

namespace App\Http\Controllers\Public\Catalog;

use App\Constants\TableStates\ServiceStatusState;
use App\Constants\TableStates\UserStatusState;
use App\Helpers\ApiCodes;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
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
         * Recupere tous les services publies avec :
         * - leur utilisateur actif
         * - leur fichier principale
         * - categories
         * - competences
         */
        $services = Service::with(['user', 'fichierPrincipale', 'categories', 'competences'])
            ->where('statut', ServiceStatusState::PUBLIE)
            ->whereHas('user', fn($q) => $q->where('status', UserStatusState::ACTIF))
            ->get();
        // transformation via Resource
        return ApiResponse::send(ApiCodes::SUCCESS, 200, [
            'services' => ServiceResource::collection($services),
        ]);
    }
    /**
     * Affiche un service specifique
     */
    public function show(Service $service)
    {
        // Si le service n'est pas publie ou l'utilisateur n'est pas actif, retourne 404
        if ($service->statut !== ServiceStatusState::PUBLIE || $service->user->status !== UserStatusState::ACTIF) {
            return ApiResponse::send(ApiCodes::NOT_FOUND, 404);
        }
        /**
         * Recupere un service specifique publie avec :
         * - leur utilisateur actif
         * - tous ses fichiers
         * - son fichier principale
         * - toutes ses categories et competences (enfants avec leur parent), tous actifs
         */
        $service->load(['user', 'fichiers', 'fichierPrincipale', 'categories' => fn($q) => $q->with('parent')->whereHas('parent', fn($q) => $q->where('est_active', true))->where('est_active', true), 'competences' => fn($q) => $q->with('parent')->whereHas('parent', fn($q) => $q->where('est_active', true))->where('est_active', true)]);
        // Retourne le service au format JSON avec le code HTTP 200
        return ApiResponse::send(ApiCodes::SUCCESS, 200, ['service' => new ServiceResource($service)]);
    }
}
