<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\Auth\ActivityMiddleware;
use App\Http\Middleware\Auth\ApiGuestMiddleware;
use App\Http\Middleware\Auth\RoleMiddleware;
use App\Helpers\ApiCodes;
use App\Helpers\ApiResponse;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

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
            // Bloque l'acces si l'utilisateur est deja authentifie
            'isGuest' => ApiGuestMiddleware::class,
            // Mettre a jour la derniere activite de l'utilisateur
            'activity' => ActivityMiddleware::class,
            // Verifier le role de l'utilisateur authentifie
            'role' => RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        /**
         * Intercepte toutes les exceptions de l'application et les convertit en reponses JSON uniformes pour l'API
         */
        $exceptions->render(function (\Throwable $e): JsonResponse {
            // ValidationException : erreurs de validation des champs
            if ($e instanceof ValidationException) {
                return ApiResponse::send(ApiCodes::VALIDATION_ERROR, 422, $e->errors());
            }
            // AuthenticationException : utilisateur non authentifie
            if ($e instanceof AuthenticationException) {
                return ApiResponse::send(ApiCodes::UNAUTHENTICATED, 401);
            }
            // AuthorizationException : acces refuse ou droits insuffisants
            if ($e instanceof AuthorizationException || $e instanceof AccessDeniedHttpException) {
                return ApiResponse::send(ApiCodes::FORBIDDEN, 403);
            }
            // NotFoundHttpException : route ou ressource introuvable
            if ($e instanceof NotFoundHttpException) {
                return ApiResponse::send(ApiCodes::NOT_FOUND, 404);
            }
            // TokenMismatchException : session expiree ou jeton CSRF invalide
            if ($e instanceof HttpException && $e->getStatusCode() === 419) {
                return ApiResponse::send(ApiCodes::CSRF_TOKEN_MISMATCH, 419);
            }
            // MethodNotAllowedHttpException : methode HTTP incorrecte
            if ($e instanceof MethodNotAllowedHttpException) {
                return ApiResponse::send(ApiCodes::METHOD_NOT_ALLOWED, 405);
            }
            // ThrottleRequestsException : trop de requetes
            if ($e instanceof ThrottleRequestsException) {
                return ApiResponse::send(ApiCodes::TOO_MANY_REQUESTS, 429);
            }
            // Throwable : erreur serveur interne inattendue
            return ApiResponse::send(ApiCodes::SERVER_ERROR, 500);
        });
    })
    ->create();
