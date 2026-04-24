<?php

#------------------------------
# Routes de gestion du compte
#------------------------------

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Account\AccountController;
use App\Http\Controllers\Account\CommandeController;
use App\Http\Controllers\Account\PortefeuilleController;
use App\Constants\TableStates\UserRoleState;

/**
 * Routes de gestion du compte utilisateur connecte
 */
Route::controller(AccountController::class)
    ->group(function () {
        // Recupere les informations du compte utilisateur connecte
        Route::get('', 'show');
        // Modifie les informations du compte utilisateur connecte
        Route::patch('', 'updateInfo');
        // Televerser l'avatar de l'utilisateur
        Route::post('upload-avatar', 'uploadAvatar');
        // Change le mot de passe du compte utilisateur connecte
        Route::post('change-password', 'changePassword');
        // Finalise l'onboarding de l'utilisateur
        Route::patch('complete-onboarding', 'completeOnboarding');
        // Change le role de l'utilisateur vers freelance s'il n'a pas fini l'onboarding
        Route::patch('switch-to-freelance', 'switchToFreelance');
        // Synchronise les competences de l'utilisateur connecte (freelance uniquement)
        Route::put('competences', 'syncCompetences')->middleware('role:' . UserRoleState::FREELANCE);
        // Active le compte utilisateur s'il est inactif
        Route::patch('activate-account', 'activateAccount');
        // Desactive le compte utilisateur s'il est actif
        Route::patch('deactivate-account', 'deactivateAccount');
        // Supprime le compte utilisateur connecte
        Route::delete('', 'destroy');
        // Deconnecte l'utilisateur
        Route::post('logout', 'logout');
    });

/**
 * Routes liees au portefeuille de l'utilisateur connecte
 */
Route::prefix('portefeuille')
    ->controller(PortefeuilleController::class)
    ->group(function () {
        // Recupere les informations du portefeuille
        Route::get('', 'show');
        // Recupere l'historique des transactions du portefeuille
        Route::get('transactions', 'transactions');
        // Recharge le portefeuille d'un montant donne (simulation)
        Route::post('recharge', 'recharge');
    });

/**
 * Routes liees aux commandes de l'utilisateur connecte
 */
Route::prefix('commandes')
    ->controller(CommandeController::class)
    ->group(function () {
        // Liste les commandes ou l'utilisateur est implique
        Route::get('', 'index');
        // Cree une nouvelle commande pour un service
        Route::post('', 'store');
        // Met a jour le statut d'une commande
        Route::patch('{commande}/status', 'updateStatus');
        // Affiche une commande precise
        Route::get('{commande}', 'show');
    });
