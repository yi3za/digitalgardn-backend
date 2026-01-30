<?php

# ------------
# API ROUTES
# ------------

use Illuminate\Support\Facades\Route;

// Inclusion des routes d'authentification
require __DIR__ . '/auth.php';
// Inclusion des routes publiques des catalogues
require __DIR__ . '/catalog/public/catalog.php';

/**
 * Routes protegees par :
 * - auth:sanctum : Authentification via cookies (verifie que l'utilisateur est connecte)
 * - activity : Mettre a jour la derniere activite de l'utilisateur
 */
Route::middleware(['auth:sanctum', 'activity'])->group(function () {
    // Inclusion des routes de gestion du compte utilisateur connecte
    require __DIR__ . '/account.php';
    // Inclusion des routes de gestion du profil utilisateur connecte
    require __DIR__ . '/profil.php';
});
