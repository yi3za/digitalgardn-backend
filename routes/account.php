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
        // Recupere les informations du compte utilisateur connecte
        Route::get('', 'show');
        // Modifie les informations du compte utilisateur connecte
        Route::put('', 'update');
        // Change le mot de passe du compte utilisateur connecte
        Route::post('change-password', 'changePassword');
        // Supprime le compte utilisateur connecte
        Route::delete('', 'destroy');
        // Deconnecte l'utilisateur
        Route::post('logout', 'logout');
    });
