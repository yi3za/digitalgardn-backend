<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ApiCodes;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;

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
        // Donnees validees
        $data = $request->validated();
        // Recuperer la valeur du champ 'remember' pour la session persistante
        $remember = $data['remember'];
        // Supprimer le champ 'remember' des donnees d'authentification
        unset($data['remember']);
        // Tentative d'authentification
        if (!Auth::attempt($data, $remember)) {
            // Echec d'authentification
            return ApiResponse::send(ApiCodes::INVALID_CREDENTIALS, 401);
        }
        // Authentification reussie
        return ApiResponse::send(ApiCodes::SUCCESS, 200, ['user' => Auth::user()]);
    }
}
