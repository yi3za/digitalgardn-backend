<?php

#--------------------------------
# Routes d'authentification API
#--------------------------------

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Regroupe toutes les routes liees a l'authentification "/auth"
Route::prefix('auth')->group(function () {
    // Enregistrement d'un nouvel utilisateur
    Route::post('register', [RegisterController::class, 'register']);
    // Gere connexion d'utilisateur
    Route::post('login', [LoginController::class, 'login']);
});
