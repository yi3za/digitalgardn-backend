<?php

#------------------------------
# Routes de gestion du profil
#------------------------------

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Freelance\Profil\ProfilController;

/**
 * Routes pour le profil de l'utilisateur connecte (/profil)
 */
Route::controller(ProfilController::class)
    ->prefix('profil')
    ->group(function () {
        // Modifie les informations du profil
        Route::patch('', 'update');
        /**
         * Gestion des competences de services
         */
        Route::put('competences', 'syncCompetences');
    });
