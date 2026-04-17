<?php

# -------------------------------
# Routes de messagerie
# -------------------------------

use App\Http\Controllers\Messages\ConversationController;
use App\Http\Controllers\Messages\MessageController;
use Illuminate\Support\Facades\Route;

/**
 * Routes des conversations
 */
Route::controller(ConversationController::class)->group(function () {
    // Liste des conversations
    Route::get('conversations', 'index');
    // Creation d'une conversation
    Route::post('conversations', 'store');
});

/**
 * Routes des messages
 */
Route::controller(MessageController::class)->group(function () {
    // Liste des messages d'une conversation
    Route::get('conversations/{conversation}/messages', 'index');
    // Envoi d'un message dans une conversation
    Route::post('conversations/{conversation}/messages', 'store');
});
