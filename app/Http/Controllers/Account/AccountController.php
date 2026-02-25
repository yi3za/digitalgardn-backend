<?php

namespace App\Http\Controllers\Account;

use App\Helpers\ApiCodes;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Account\UpdateRequest;
use App\Http\Requests\Auth\Password\ChangePasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Gestion des actions liees au compte de l utilisateur connecte
 */
class AccountController extends Controller
{
    /**
     * Retourne les informations de l'utilisateur connecte
     */
    public function show(Request $request)
    {
        return ApiResponse::send(ApiCodes::SUCCESS, 200, ['user' => $request->user()]);
    }
    /**
     * Modifie les informations de l'utilisateur connecte
     */
    public function update(UpdateRequest $request)
    {
        // Recupere l'utilisateur qui est connecte
        $user = $request->user();
        // Recupere les donnees validees
        $data = $request->validated();
        // Verifie si un avatar a ete envoye
        if ($request->hasFile('avatar')) {
            // Stocke l'avatar envoye dans le dossier 'avatars' sur le disque 'public'
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }
        // Modifie les informations envoyees
        $user->update($data);
        // Retourne statut 200 avec l'utilisateur mis a jour
        return ApiResponse::send(ApiCodes::SUCCESS, 200, ['user' => $user]);
    }
    /**
     * Change le mot de passe de l'utilisateur
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        // Recupere l'utilisateur actuellement authentifie
        $user = $request->user();
        // Valide et Recupere les donnees de formulaire (ancien et nouveau mot de passe)
        $data = $request->validated();
        // Verifie que l'ancien mot de passe fourni correspond au mot de passe actuel de l'utilisateur
        if (!Hash::check($data['old_password'], $user->password)) {
            // Si le mot de passe ancien est incorrect, retourne une reponse JSON avec code 422
            return ApiResponse::send(ApiCodes::VALIDATION_ERROR, 422);
        }
        // Mettre a jour le mot de passe de l'utilisateur
        $user->update([
            'password' => $data['new_password'],
        ]);
        // Retourne une reponse JSON indiquant que l'operation a reussi avec code 200
        return ApiResponse::send(ApiCodes::SUCCESS, 200);
    }
    /**
     * Supprime le compte de l'utilisateur connecte
     */
    public function destroy(Request $request)
    {
        // Recupere l'utilisateur authentifie
        $user = $request->user();
        // Supprime le compte de l'utilisateur
        $user->delete();
        // Retourne une response vide (204 Not Content)
        return ApiResponse::send(ApiCodes::SUCCESS, 200);
    }
    /**
     * Deconnexion d'un utilisateur
     */
    public function logout()
    {
        // Verifie si l'utilisateur est authentifie
        if (Auth::check()) {
            // Deconnecte l'utilisateur (session web)
            Auth::guard('web')->logout();
            // Retourne une response vide (204 Not Content)
            return ApiResponse::send(ApiCodes::SUCCESS, 200);
        }
        // Sinon renvoyer 401 Unauthorized
    }
}
