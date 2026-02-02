<?php

namespace App\Http\Controllers\Public\Catalog;

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
        return response()->json(['categories' => $categories], 200);
    }
    /**
     * Affiche une categorie specifique
     */
    public function show($slug)
    {
        // Recupere une categorie specifique active par son slug
        $categorie = Categorie::where(['slug' => $slug, 'parent_id' => null, 'est_active' => true])
            // Charge egalement ses enfants actifs, tries par ordre
            ->with([
                'enfants' => function ($query) {
                    $query->orderBy('ordre')->where('est_active', true);
                },
            ])
            ->first();
        // Si la categorie n'existe pas, retourne une erreur HTTP 404
        if (!$categorie) {
            return response()->json([], 404);
        }
        // Retourne la categorie avec ses enfants au format JSON avec code HTTP 200
        return response()->json(['categorie' => $categorie], 200);
    }
    /**
     * Liste tous les services d'une categorie
     */
    public function servicesParCategorie($slug)
    {
        // Recupere un categorie active par son slug
        $categorie = Categorie::where(['slug' => $slug, 'est_active' => true])->first();
        // Si la categorie n'existe pas, retourne une erreur HTTP 404
        if (!$categorie) {
            return response()->json([], 404);
        }
        // Recupere tous les services
        $services = $categorie->servicesAvecDetails(['est_active' => true], ['statut' => 'publie'], ['status' => 'actif']);
        // Retourne les services au format JSON avec le code HTTP 200
        return response()->json(['services' => $services], 200);
    }
}
