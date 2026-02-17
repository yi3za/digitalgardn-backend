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
}
