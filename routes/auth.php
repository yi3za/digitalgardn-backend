<?php

#--------------------------------
# Routes d'authentification API
#--------------------------------

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\MeController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

// Regroupe toutes les routes liees a l'authentification "/auth"
Route::prefix('auth')->group(function () {
    // Enregistrement d'un nouvel utilisateur
    Route::post('register', [RegisterController::class, 'register']);
    // Gere connexion d'utilisateur
    Route::post('login', [LoginController::class, 'login']);
    // Envoie un code de reinitialisation du mot de passe a l'utilisateur
    Route::post('forget-password', [PasswordResetController::class, 'sendCode']);
    // Reinitialise le mot de passe en utilisant le code recu
    Route::post('reset-password', [PasswordResetController::class, 'resetPassword']);
    // Routes protegees par sanctum
    Route::middleware('auth:sanctum')->group(function () {
        // Recuperer l'utilisateur actuellement authentifie
        Route::get('me', [MeController::class, 'me']);
        // Deconnecter l'utilisateur authentifie
        Route::post('logout', [LogoutController::class, 'logout']);
    });
});
