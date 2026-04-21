<?php

namespace App\Http\Controllers\Freelance\Profil;

use App\Helpers\ApiCodes;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Freelance\Catalog\SyncCompetencesRequest;
use App\Http\Requests\Freelance\Profil\UpdateRequest;
use App\Http\Resources\UserResource;

/**
 * Gestion des informations du profil de l'utilisateur
 */
class ProfilController extends Controller
{
    /**
     * Modifie les informations du profil de l'utilisateur connecte
     */
    public function update(UpdateRequest $request)
    {
        // Recupere l'utilisateur qui est connecte
        $user = $request->user();
        // Recupere les donnees validees
        $data = $request->validated();
        // Modifie les informations envoyees
        $user->profil()->update($data);
        // Actualiser les donnees de l'utilisateur
        $user->refresh();
        // Retourne statut 200 avec le profil mis a jour
        return ApiResponse::send(ApiCodes::SUCCESS, 200, ['user' => new UserResource($user)]);
    }
    /**
     * Gestion des competences de l'utilisateur connecte
     */
    public function syncCompetences(SyncCompetencesRequest $request)
    {
        // Recupere l'utilisateur qui est connecte
        $user = $request->user();
        // Recupere les IDs des competences validees
        $competencesIds = $request->validated('competences');
        // Supprime les anciennes relations et ajoute les nouvelles
        $user->competences()->sync($competencesIds);
        // Retourne une response succes
        return ApiResponse::send(ApiCodes::SUCCESS, 200);
    }
}
