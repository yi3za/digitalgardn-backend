<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\Account\UpdateRequest;
use App\Http\Requests\Auth\Password\ChangePasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    /**
     * Retourne les informations de l'utilisateur connecte
     */
    public function show(Request $request)
    {
        return response()->json(
            [
                'user' => $request->user(),
            ],
            200,
        );
    }
    /**
     * Modifier les informations de l'utilisateur connecte
     */
    public function update(UpdateRequest $request)
    {
        // Recuperer l'utilisateur qui est connecte
        $user = $request->user();
        // Recuperer les donnees validees
        $data = $request->validated();
        // Verifie si un avatar a ete envoye
        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }
        // Modifier les informations envoyees
        $user->update($data);
        // Retourner statut 200 avec l'utilisateur mis a jour
        return response()->json(['user' => $user], 200);
    }
    /**
     * Changer le mot de passe de l'utilisateur
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        // Recuperer l'utilisateur actuellement authentifie
        $user = $request->user();
        // Valider et Recuperer les donnees de formulaire (ancien et nouveau mot de passe)
        $data = $request->validated();
        // Verifier que l'ancien mot de passe fourni correspond au mot de passe actuel de l'utilisateur
        if (!Hash::check($data['old_password'], $user->password)) {
            // Si le mot de passe ancien est incorrect, retourner une reponse JSON avec code 422
            return response()->json([], 422);
        }
        // Mettre a jour le mot de passe de l'utilisateur
        $user->update([
            'password' => $data['new_password'],
        ]);
        // Retourner une reponse JSON indiquant que l'operation a reussi avec code 200
        return response()->json([], 200);
    }
    /**
     * Supprime le compte de l'utilisateur connecte
     */
    public function destroy(Request $request)
    {
        // Recuperer l'utilisateur authentifie
        $user = $request->user();
        // Supprimer le compte de l'utilisateur
        $user->delete();
        // Retourner une response vide (204 Not Content)
        return response()->noContent();
    }
    /**
     * Deconnexion d'un utilisateur
     */
    public function logout()
    {
        // Verifie si l'utilisateur est authentifie
        if (Auth::check()) {
            // Deconnecter l'utilisateur (session web)
            Auth::guard('web')->logout();
            // Retourner une response vide (204 Not Content)
            return response()->noContent();
        }
        // Sinon renvoyer 401 Unauthorized
    }
}
