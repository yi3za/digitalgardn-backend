<?php

#---------------------------------
# Routes du catalogue publiques
#---------------------------------

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\Catalog\CategorieController;
use App\Http\Controllers\Public\Catalog\ServiceController;

/**
 * Routes publiques pour les categories
 */
Route::prefix('categories')
    ->controller(CategorieController::class)
    ->group(function () {
        // Liste tous les categories
        Route::get('', 'index');
        // Affiche une categorie specifique
        Route::get('{slug}', 'show');
        // Liste tous les services d'une categorie
        Route::get('{slug}/services', 'servicesParCategorie');
    });

/**
 *  Routes publiques pour les services
 */
Route::prefix('services')
    ->controller(ServiceController::class)
    ->group(function () {
        // Liste tous les services
        Route::get('', 'index');
        // Affiche un service specifique
        Route::get('{slug}', 'show');
    });
