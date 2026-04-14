<?php

namespace App\Http\Controllers\Public\Catalog;

use App\Helpers\ApiCodes;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Competence;

/**
 * Gerer les competences publiques
 */
class CompetenceController extends Controller
{
    /**
     * Liste toutes les competences avec leurs enfants
     */
    public function index()
    {
        // Recupere les competences parents avec leurs enfants actifs
        $competences = Competence::whereNull('parent_id')
            ->where('est_active', true)
            ->with([
                'enfants' => function ($q) {
                    $q->where('est_active', true)->orderBy('ordre');
                }
            ])
            ->orderBy('ordre')
            ->get();
        // Retourne la structure complete en JSON
        return ApiResponse::send(ApiCodes::SUCCESS, 200, ['competences' => $competences]);
    }
    /**
     * Liste tous les services d'une competence
     */
    public function servicesParCompetence(Competence $competence)
    {
        // Si la competence n'est pas active, retourne 404
        if (!$competence->est_active) {
            return ApiResponse::send(ApiCodes::NOT_FOUND, 404);
        }
        // Recupere tous les services
        $services = $competence->servicesAvecDetails(['est_active' => true], ['statut' => 'publie'], ['status' => 'actif']);
        // Retourne les services au format JSON avec le code HTTP 200
        return ApiResponse::send(ApiCodes::SUCCESS, 200, ['services' => $services]);
    }
}
