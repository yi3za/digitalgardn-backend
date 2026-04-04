<?php

#------------------------------
# Routes de gestion du compte
#------------------------------

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Account\AccountController;

/**
 * Routes de gestion du compte utilisateur connecte
 */
Route::controller(AccountController::class)
    ->group(function () {
        // Recupere les informations du compte utilisateur connecte
        Route::get('', 'show');
        // Modifie les informations du compte utilisateur connecte
        Route::patch('', 'updateInfo');
        // Televerser l'avatar de l'utilisateur
        Route::post('upload-avatar', 'uploadAvatar');
        // Change le mot de passe du compte utilisateur connecte
        Route::post('change-password', 'changePassword');
        // Finalise l'onboarding de l'utilisateur
        Route::patch('complete-onboarding', 'completeOnboarding');
        // Active le compte utilisateur s'il est inactif
        Route::patch('activate-account', 'activateAccount');
        // Desactive le compte utilisateur s'il est actif
        Route::patch('deactivate-account', 'deactivateAccount');
        // Supprime le compte utilisateur connecte
        Route::delete('', 'destroy');
        // Deconnecte l'utilisateur
        Route::post('logout', 'logout');
    });
