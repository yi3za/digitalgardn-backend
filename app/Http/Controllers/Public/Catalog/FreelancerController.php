<?php

namespace App\Http\Controllers\Public\Catalog;

use App\Helpers\ApiCodes;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\FreelancerResource;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use App\Models\User;

/**
 * Gerer l'affichage public des freelances
 */
class FreelancerController extends Controller
{
    /**
     * Affiche un freelance specifique avec ses services publies
     */
    public function show(User $user)
    {
        // Limiter l'acces aux freelances actifs uniquement
        if ($user->role !== 'freelance' || $user->status !== 'actif') {
            return ApiResponse::send(ApiCodes::NOT_FOUND, 404);
        }

        $user->load(['profil', 'competences' => fn($query) => $query->where('est_active', true)]);

        $services = Service::with(['fichierPrincipale', 'categories', 'competences'])
            ->where('user_id', $user->id)
            ->where('statut', 'publie')
            ->latest('created_at')
            ->get();

        return ApiResponse::send(ApiCodes::SUCCESS, 200, [
            'freelancer' => new FreelancerResource($user),
            'services' => ServiceResource::collection($services),
        ]);
    }
}
