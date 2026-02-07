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
        Route::get('{service:slug}','show');
        // Mettre a jour les informations d'un service
        Route::patch('{service}', 'update');
        // Supprimer un service existant
        Route::delete('{service}','destroy');
        /**
         * Gestion des categories de services
         */
        Route::put('{service}/categories','syncCategories');
        /**
         * Gestion des fichiers de services
         */
        Route::put('{service}/fichiers', 'syncFichiers');
    });
