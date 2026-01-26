<?php

#--------------------------------
# Routes d'authentification API
#--------------------------------

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\MeController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;

// Regroupe toutes les routes liees a l'authentification "/auth"
Route::prefix('auth')->group(function () {
    // Ce groupe est reserve aux utilisateurs **non authentifies** (guests)
    Route::middleware('guest')->group(function () {
        // Enregistrement d'un nouvel utilisateur
        Route::post('register', [RegisterController::class, 'register']);
        // Gere connexion d'utilisateur
        Route::post('login', [LoginController::class, 'login']);
    });
    // Envoie un code de reinitialisation du mot de passe a l'utilisateur
    Route::post('forget-password', [PasswordResetController::class, 'sendCode']);
    // Reinitialise le mot de passe en utilisant le code recu
    Route::post('reset-password', [PasswordResetController::class, 'resetPassword']);
    /*
    Routes protegees par :
    - auth:sanctum : Authentification via cookies (verifie que l'utilisateur est connecte)
    - activity : Mettre a jour la derniere activite de l'utilisateur
    */
    Route::middleware(['auth:sanctum','activity'])->group(function () {
        // Recuperer l'utilisateur actuellement authentifie
        Route::get('me', [MeController::class, 'me']);
        // Deconnecter l'utilisateur authentifie
        Route::post('logout', [LogoutController::class, 'logout']);
    });
});
