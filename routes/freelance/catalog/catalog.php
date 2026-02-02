<?php

#--------------------------------------------------
# Routes du catalogue specifiques aux freelances
#--------------------------------------------------

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Freelance\Catalog\ServiceController;

/**
 * Gestion des services
 */
Route::controller(ServiceController::class)
    ->prefix('services')
    ->group(function () {
        // Liste tous les services
        Route::get('', 'index');
        // Creation d'un service
        Route::post('', 'store');
        // Affiche un service specifique
        Route::get('{slug}','show');
    });
