<?php

namespace App\Http\Middleware\Auth;

use App\Helpers\ApiCodes;
use App\Helpers\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware pour bloquer l'acces aux routes destinees aux invites
 */
class ApiGuestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $gruad): Response
    {
        // Verifie si l'utilisateur est connecte via le guard specifie
        if (auth($gruad)->check()) {
            // Retourne 403 Forbidden si l'utilisateur est authentifie
            return ApiResponse::send(ApiCodes::FORBIDDEN, 403);
        }
        // Passe la requete
        return $next($request);
    }
}
