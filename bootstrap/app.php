<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\Auth\ActivityMiddleware;
use App\Http\Middleware\Auth\RoleMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        // api
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Utilise l'authentification par cookie de sanctum pour le web
        $middleware->statefulApi();
        // Enregistrer les alias de middleware personnalises
        $middleware->alias([
            // Mettre a jour la derniere activite de l'utilisateur
            'activity' => ActivityMiddleware::class,
            // Verifier le role de l'utilisateur authentifie
            'role' => RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
