<?php

namespace App\Http\Controllers\Public\Catalog;

use App\Helpers\ApiCodes;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Categorie;

/**
 * Gerer les categories publiques
 */
class CategorieController extends Controller
{
    /**
     * Liste tous les categories
     */
    public function index()
    {
        // Recupere les categories principales actives, triees par ordre
        $categories = Categorie::where(['parent_id' => null, 'est_active' => true])
            ->orderBy('ordre')
            ->get();
        // Retourne la liste au format JSON avec le code HTTP 200
        return ApiResponse::send(ApiCodes::SUCCESS, 200, ['categories' => $categories]);
    }
    /**
     * Affiche une categorie specifique
     */
    public function show(Categorie $categorie)
    {
        // Si la categorie n'est pas active ou a un parent, retourne 404
        if (!$categorie->est_active || $categorie->parent_id !== null) {
            return ApiResponse::send(ApiCodes::NOT_FOUND, 404);
        }
        // Charge egalement les enfants actifs, tries par ordre
        $categorie->load([
            'enfants' => fn($q) => $q->orderBy('ordre')->where('est_active', true),
        ]);
        // Retourne la categorie avec ses enfants au format JSON avec code HTTP 200
        return ApiResponse::send(ApiCodes::SUCCESS, 200, ['categorie' => $categorie]);
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
        return ApiResponse::send(ApiCodes::SUCCESS, 200, ['services' => $services]);
    }
}
