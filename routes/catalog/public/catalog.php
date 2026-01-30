<?php

#-------------------------
# Routes du catalogue
#-------------------------

use App\Http\Controllers\Catalog\public\CategorieController;
use Illuminate\Support\Facades\Route;

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
