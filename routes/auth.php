<?php

#--------------------------------
# Routes d'authentification API
#--------------------------------

use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

// Regroupe toutes les routes liees a l'authentification "/auth"
Route::prefix('auth')->group(function () {
    // Enregistrement d'un nouvel utilisateur
    Route::post('register', [RegisterController::class, 'register']);
});
