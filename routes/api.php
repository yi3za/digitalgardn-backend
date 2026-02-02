<?php

# ------------
# API ROUTES
# ------------

use Illuminate\Support\Facades\Route;

// Inclusion des routes d'authentification
require __DIR__ . '/auth.php';
// Inclusion des routes publiques des catalogues
require __DIR__ . '/public/catalog/catalog.php';

/**
 * Routes protegees par :
 * - auth:sanctum : Authentification via cookies (verifie que l'utilisateur est connecte)
 * - activity : Mettre a jour la derniere activite de l'utilisateur
 */
Route::middleware(['auth:sanctum', 'activity'])->group(function () {
    /**
     * Routes liees a l'utilisateur connecte (/me)
     */
    Route::prefix('me')->group(function () {
        // Inclusion des routes de gestion du compte utilisateur connecte
        require __DIR__ . '/account.php';
        /**
         * Routes reservees aux freelances uniquement
         */
        Route::middleware('role:freelance')->group(function () {
            // Inclusion des routes de gestion du profil
            require __DIR__ . '/freelance/profil/profil.php';
            // Inclusion des routes de gestion des services
            require __DIR__ . '/freelance/catalog/catalog.php';
        });
    });
});
