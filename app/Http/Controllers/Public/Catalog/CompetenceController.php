<?php

namespace App\Http\Controllers\Public\Catalog;

use App\Http\Controllers\Controller;
use App\Models\Competence;

/**
 * Gerer les competences publiques
 */
class CompetenceController extends Controller
{
    /**
     * Liste tous les competences
     */
    public function index()
    {
        // Recupere les competences principales actives, triees par ordre
        $competences = Competence::where(['parent_id' => null, 'est_active' => true])
            ->orderBy('ordre')
            ->get();
        // Retourne la liste au format JSON avec le code HTTP 200
        return response()->json(['competences' => $competences], 200);
    }
    /**
     * Affiche une competence specifique
     */
    public function show(Competence $competence)
    {
        // Si la competence n'est pas active ou a un parent, retourne 404
        if (!$competence->est_active || $competence->parent_id !== null) {
            return response()->json([], 404);
        }
        // Charge egalement les enfants actifs, tries par ordre
        $competence->load([
            'enfants' => fn($q) => $q->orderBy('ordre')->where('est_active', true),
        ]);
        // Retourne la competence avec ses enfants au format JSON avec code HTTP 200
        return response()->json(['competence' => $competence], 200);
    }
}
