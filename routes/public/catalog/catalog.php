<?php

#---------------------------------
# Routes du catalogue publiques
#---------------------------------

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\Catalog\CategorieController;
use App\Http\Controllers\Public\Catalog\CompetenceController;
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
        Route::get('{categorie:slug}', 'show');
        // Liste tous les services d'une categorie
        Route::get('{categorie:slug}/services', 'servicesParCategorie');
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
        Route::get('{service:slug}', 'show');
    });

/**
 * Routes publiques pour les competences
 */
Route::prefix('competences')
    ->controller(CompetenceController::class)
    ->group(function () {
        // Liste tous les competences
        Route::get('', 'index');
        // Affiche une competence specifique
        Route::get('{competence:slug}', 'show');
        // Liste tous les services d'une competence
        Route::get('{competence:slug}/services', 'servicesParCompetence');
    });
