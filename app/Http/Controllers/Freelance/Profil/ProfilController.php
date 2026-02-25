<?php

namespace App\Http\Controllers\Freelance\Profil;

use App\Helpers\ApiCodes;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Freelance\Catalog\SyncCompetencesRequest;
use App\Http\Requests\Freelance\Profil\UpdateRequest;
use Illuminate\Http\Request;

/**
 * Gestion des informations du profil de l'utilisateur
 */
class ProfilController extends Controller
{
    /**
     * Retourne les informations du profil de l'utilisateur connecte
     */
    public function show(Request $request)
    {
        $user = $request->user();
        return ApiResponse::send(ApiCodes::SUCCESS, 200, ['profil' => $user->profil]);
    }
    /**
     * Modifie les informations du profil de l'utilisateur connecte
     */
    public function update(UpdateRequest $request)
    {
        // Recupere l'utilisateur qui est connecte
        $user = $request->user();
        // Recupere les donnees validees
        $data = $request->validated();
        // Verifie si une image de couverture a ete envoyee
        if ($request->hasFile('image_couverture')) {
            // Stocke l'image de couverture envoyee dans le dossier 'images_couvertures' du disque 'public'
            $data['image_couverture'] = $request->file('image_couverture')->store('images_couvertures', 'public');
        }
        // Modifie les informations envoyees
        $user->profil()->update($data);
        // Retourne statut 200 avec le profil mis a jour
        return ApiResponse::send(ApiCodes::SUCCESS, 200, ['profil' => $user->profil]);
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
