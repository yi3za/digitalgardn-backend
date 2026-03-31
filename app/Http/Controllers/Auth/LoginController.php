<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ApiCodes;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Gestion de l authentification et de la connexion des utilisateurs
 */
class LoginController extends Controller
{
    /**
     * Connexion d'un utilisateur
     */
    public function login(LoginRequest $request)
    {
        // Utiliser une transaction pour garantir l'integrite des donnees en cas d'erreur
        return DB::transaction(function () use ($request) {
            // Donnees validees
            $data = $request->validated();
            // Recuperer la valeur du champ 'remember' pour la session persistante
            $remember = $data['remember'];
            // Supprimer le champ 'remember' des donnees d'authentification
            unset($data['remember']);
            // Tentative d'authentification
            if (Auth::attempt($data, $remember)) {
                // Regeneration de la session
                $request->session()->regenerate();
                // Authentification reussie
                return ApiResponse::send(ApiCodes::SUCCESS, 200, ['user' => new UserResource(Auth::user())]);
            }
            // Echec d'authentification
            return ApiResponse::send(ApiCodes::INVALID_CREDENTIALS, 401);
        });
    }
}
