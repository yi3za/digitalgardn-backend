<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;

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
            return response()->json([], 401);
        }
        // Authentification reussie
        return response()->json(
            [
                // Retourne l'utilisant authentifie
                'user' => Auth::user(),
            ],
            200,
        );
    }
}
