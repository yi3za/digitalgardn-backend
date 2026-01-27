<?php

#--------------------------------
# Routes d'authentification API
#--------------------------------

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\AccountController;
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
    /**
     * Routes protegees par :
     * - auth:sanctum : Authentification via cookies (verifie que l'utilisateur est connecte)
     * - activity : Mettre a jour la derniere activite de l'utilisateur
     */
    Route::middleware(['auth:sanctum', 'activity'])->group(function () {
        /**
         * Routes de gestion du compte utilisateur connecte (/me)
         */
        Route::controller(AccountController::class)
            ->prefix('me')
            ->group(function () {
                // Recuperer les infos du compte
                Route::get('', 'show');
                // Modifier les infos du compte
                Route::put('', 'update');
                // Changer le mot de passe
                Route::post('change-password', 'changePassword');
                // Supprimer le compte
                Route::delete('', 'destroy');
                // Deconnexion de l'utilisateur
                Route::post('logout', 'logout');
            });
    });
});
