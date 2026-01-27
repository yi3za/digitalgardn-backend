<?php

#------------------------------
# Routes de gestion du compte
#------------------------------

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Account\AccountController;

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
