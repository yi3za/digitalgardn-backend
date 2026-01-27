<?php

#--------------------------------
# Routes d'authentification API
#--------------------------------

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;

/**
 * Regroupe toutes les routes liees a l'authentification "/auth"
 */
Route::prefix('auth')->group(function () {
    /**
     * Ce groupe est reserve aux utilisateurs **non authentifies** (guests)
     */
    Route::middleware('guest')->group(function () {
        // Enregistrement d'un nouvel utilisateur
        Route::post('register', [RegisterController::class, 'register']);
        // Gere connexion d'utilisateur
        Route::post('login', [LoginController::class, 'login']);
    });
    /**
     * Routes de reinitialisation du mot de passe de l'utilisateur
     */
    Route::controller(PasswordResetController::class)->group(function () {
        Route::post('forget-password', 'sendCode');
        Route::post('reset-password', 'resetPassword');
    });
});
