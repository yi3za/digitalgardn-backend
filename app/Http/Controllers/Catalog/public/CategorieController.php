<?php

namespace App\Http\Controllers\Catalog\public;

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
        // Recupere un categorie specifique active par son slug
        $categorie = Categorie::where(['slug' => $slug, 'est_active' => true])
            // Charge egalement ses enfants actifs, tries par ordre
            ->with([
                'enfants' => function ($query) {
                    $query->orderBy('ordre')->where('est_active', true);
                },
            ])
            ->firstOrFail();
        // Retourne la categorie avec ses enfants au format JSON avec code HTTP 200
        return response()->json(['categorie' => $categorie], 200);
    }
    /**
     * Liste tous les services d'une categorie
     */
    public function servicesParCategorie($slug)
    {
        // Recupere un categorie active par son slug
        $categorie = Categorie::where(['slug' => $slug, 'est_active' => true])->firstOrFail();
        // Recupere tous les services publies de cette categorie
        $services = $categorie->services()->where('statut', 'publie')->get();
        // Retourne les services au format JSON avec le code HTTP 200
        return response()->json(['services' => $services], 200);
    }
}
