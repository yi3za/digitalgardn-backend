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
        // Tentative d'authentification
        if (!Auth::attempt($data)) {
            // Echec d'authentification
            return ApiResponse::send(ApiCodes::INVALID_CREDENTIALS, 401);
        }
        // Authentification reussie
        return ApiResponse::send(ApiCodes::SUCCESS, 200, ['user' => Auth::user()]);
    }
}
