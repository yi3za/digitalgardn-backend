<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /**
     * Inscription d'un utilisateur
     */
    public function register(RegisterRequest $request)
    {
        // Donnees validees
        $data = $request->validated();
        // Creation d'un nouvel utilisateur
        $user = User::create($data);
        // Authentification automatique
        Auth::login($user);
        // Retourne l'utilisateur authentifie avec le statut HTTP 201 (cree)
        return response()->json(
            [
                'user' => Auth::user(),
            ],
            201,
        );
    }
}
