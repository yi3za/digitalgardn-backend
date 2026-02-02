<?php

#------------------------------
# Routes de gestion du profil
#------------------------------

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Profil\ProfilController;

/**
 * Routes pour le profil de l'utilisateur connecte (/profil)
 * Ce groupe est reserve aux utilisateurs freelance
 */
Route::middleware('role:freelance')
    ->controller(ProfilController::class)
    ->prefix('profil')
    ->group(function () {
        // Recupere les informations du profil
        Route::get('', 'show');
        // Modifie les informations du profil
        Route::patch('', 'update');
    });
