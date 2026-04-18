<?php

namespace App\Http\Controllers\Public\Catalog;

use App\Helpers\ApiCodes;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use App\Models\Categorie;

/**
 * Gerer les categories publiques
 */
class CategorieController extends Controller
{
    /**
     * Liste toutes les categories avec leurs sous-categories
     */
    public function index()
    {
        // Recupere les categories parents avec leurs enfants actifs
        $categories = Categorie::whereNull('parent_id')
            ->where('est_active', true)
            ->with([
                'enfants' => function ($q) {
                    $q->where('est_active', true)->orderBy('ordre');
                },
            ])
            ->orderBy('ordre')
            ->get();

        // Retourne la structure complete en JSON
        return ApiResponse::send(ApiCodes::SUCCESS, 200, ['categories' => $categories]);
    }
    /**
     * Liste tous les services d'une categorie
     */
    public function servicesParCategorie(Categorie $categorie)
    {
        // Si la categorie n'est pas active, retourne 404
        if (!$categorie->est_active) {
            return ApiResponse::send(ApiCodes::NOT_FOUND, 404);
        }
        // Recupere tous les services
        $services = $categorie->servicesAvecDetails(['est_active' => true], ['statut' => 'publie'], ['status' => 'actif']);
        // Retourne les services au format JSON avec le code HTTP 200
        return ApiResponse::send(ApiCodes::SUCCESS, 200, ['services' => ServiceResource::collection($services)]);
    }
}
