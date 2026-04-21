<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ApiCodes;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\Portefeuille;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        // Utiliser une transaction pour garantir l'integrite des donnees en cas d'erreur
        return DB::transaction(function () use ($request) {
            // Donnees validees
            $data = $request->validated();
            // Recuperer la valeur du champ 'remember' pour la session persistante
            $remember = $data['remember'];
            // Supprimer le champ 'remember' des donnees d'authentification
            unset($data['remember']);
            // Creation d'un nouvel utilisateur
            $user = User::create($data);
            // Creation du portefeuille initial associe a l'utilisateur
            Portefeuille::create([
                'user_id' => $user->id,
            ]);
            // Authentification automatique
            Auth::login($user, $remember);
            // Actualiser les donnees de l'utilisateur
            $user->refresh();
            // Regeneration de la session
            $request->session()->regenerate();
            // Retourne l'utilisateur authentifie avec le statut HTTP 201 (cree)
            return ApiResponse::send(ApiCodes::SUCCESS, 201, ['user' => new UserResource($user)]);
        });
    }
}
