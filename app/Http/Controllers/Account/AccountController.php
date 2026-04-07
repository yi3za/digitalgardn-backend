<?php

namespace App\Http\Controllers\Account;

use App\Helpers\ApiCodes;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Account\ChangePasswordRequest;
use App\Http\Requests\Account\UpdateInfoRequest;
use App\Http\Requests\Account\UploadAvatarRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
        // Recupere l'utilisateur connecte
        $user = $request->user();
        // Retourne les informations de l'utilisateur
        return ApiResponse::send(ApiCodes::SUCCESS, 200, ['user' => new UserResource($user)]);
    }
    /**
     * Modifie les informations de l'utilisateur connecte
     */
    public function updateInfo(UpdateInfoRequest $request)
    {
        // Recupere l'utilisateur qui est connecte
        $user = $request->user();
        // Recupere les donnees validees
        $data = $request->validated();
        // Modifie les informations envoyees
        $user->update($data);
        // Retourne statut 200 avec l'utilisateur mis a jour
        return ApiResponse::send(ApiCodes::SUCCESS, 200, ['user' => new UserResource($user)]);
    }
    /**
     * Televerser l'avatar de l'utilisateur
     */
    public function uploadAvatar(UploadAvatarRequest $request)
    {
        // Recupere l'utilisateur connecte
        $user = $request->user();
        // Supprimer l'ancien avatar s'il existe
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }
        // Mettre a jour l'avatar si present
        $user->update([
            // Stocke l'avatar envoye dans le dossier 'avatars' sur le disque 'public' s'il existe
            'avatar' => $request->hasFile('avatar') ? $request->file('avatar')->store('avatars', 'public') : null,
        ]);
        // Retourne statut 200 avec l'utilisateur mis a jour
        return ApiResponse::send(ApiCodes::SUCCESS, 200, ['user' => new UserResource($user)]);
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
        // Mettre a jour le mot de passe de l'utilisateur
        $user->update([
            'password' => $data['new_password'],
        ]);
        // Retourne une reponse JSON indiquant que l'operation a reussi avec code 200
        return ApiResponse::send(ApiCodes::SUCCESS, 200);
    }
    /**
     * Finalise l'onboarding de l'utilisateur
     */
    public function completeOnboarding(Request $request)
    {
        // Recupere l'utilisateur
        $user = $request->user();
        // Marquer l'onboarding comme termine
        $user->update(['onboarding_termine' => true]);
        // Retourner une reponse de succes
        return ApiResponse::send(ApiCodes::SUCCESS, 200, ['user' => new UserResource($user)]);
    }
    /**
     * Passer le role de l'utilisateur a freelance durant l'onboarding
     */
    public function switchToFreelance(Request $request)
    {
        // Recupere l'utilisateur
        $user = $request->user();
        // Verification : si l'onboarding est deja termine, on refuse le changement ici
        // (pour securiser le fait que ce switch n'arrive que durant l'onboarding)
        if ($user->onboarding_termine) {
            return ApiResponse::send(ApiCodes::FORBIDDEN, 403);
        }
        // Utilisation d'une transaction pour garantir l'integrite des donnees
        return DB::transaction(function () use ($user) {
            // Mise a jour du role
            $user->update(['role' => 'freelance']);
            // Cree le profil s'il n'existe pas, sinon recupere le profil actuel
            $user->profil()->firstOrCreate([
                'user_id' => $user->id,
            ]);
            // charge son profil
            $user->load('profil');
            // Retourner l'utilisateur avec son profil charge
            return ApiResponse::send(ApiCodes::SUCCESS, 200, ['user' => new UserResource($user)]);
        });
    }
    /**
     * Active le compte de l'utilisateur authentifie s'il est inactif
     */
    public function activateAccount(Request $request)
    {
        // Recupere l'utilisateur
        $user = $request->user();
        // Verifie si le compte est banni
        if ($user->status === 'banni') {
            return ApiResponse::send(ApiCodes::FORBIDDEN, 403);
        }
        // Verifie si le compte est inactif
        if ($user->status === 'inactif') {
            // Activer le compte
            $user->update(['status' => 'actif']);
            // Retourne une reponse succes
            return ApiResponse::send(ApiCodes::SUCCESS, 200, ['user' => new UserResource($user)]);
        }
        // Retourne une reponse erreur (compte deja actif)
        return ApiResponse::send(ApiCodes::BAD_REQUEST, 400);
    }
    /**
     * Desactive le compte de l'utilisateur authentifie s'il est actif
     */
    public function deactivateAccount(Request $request)
    {
        // Recupere l'utilisateur
        $user = $request->user();
        // Verifie si le compte est banni
        if ($user->status === 'banni') {
            return ApiResponse::send(ApiCodes::FORBIDDEN, 403);
        }
        // Verifie si le compte est actif
        if ($user->status === 'actif') {
            // Desactiver le compte
            $user->update(['status' => 'inactif']);
            // Retourne une reponse succes
            return ApiResponse::send(ApiCodes::SUCCESS, 200, ['user' => new UserResource($user)]);
        }
        // Retourne une reponse erreur (compte deja inactif)
        return ApiResponse::send(ApiCodes::BAD_REQUEST, 400);
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
        // Retourne une reponse succes
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
            // Retourne une reponse succes
            return ApiResponse::send(ApiCodes::SUCCESS, 200);
        }
        // Sinon renvoyer 401 Unauthorized
    }
}
