<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    /**
     * Deconnexion d'un utilisateur
     */
    public function logout()
    {
        // Si l'utilisateur est authentifie
        if (Auth::check()) {
            // Deconnecter et renvoyer 204 Not Content
            Auth::guard('web')->logout();
            return response()->noContent();
        }
        // Sinon renvoyer 401 Unauthorized
    }
}
