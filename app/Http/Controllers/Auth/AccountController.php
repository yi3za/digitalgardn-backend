<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public function update(Request $request){
        //
    }
    /**
     *
     */
    public function destroy(Request $request){
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
