<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ApiCodes;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

/**
 * Gestion de la creation et de l enregistrement des nouveaux utilisateurs
 */
class RegisterController extends Controller
{
    /**
     * Inscription d'un utilisateur
     */
    public function register(RegisterRequest $request)
    {
        // Donnees validees
        $data = $request->validated();
        // Recuperer la valeur du champ 'remember' pour la session persistante
        $remember = $data['remember'];
        // Supprimer le champ 'remember' des donnees d'authentification
        unset($data['remember']);
        // Creation d'un nouvel utilisateur
        $user = User::create($data);
        // Authentification automatique
        Auth::login($user, $remember);
        // Regeneration de la session
        $request->session()->regenerate();
        // Retourne l'utilisateur authentifie avec le statut HTTP 201 (cree)
        return ApiResponse::send(ApiCodes::SUCCESS, 201, ['user' => Auth::user()]);
    }
}
