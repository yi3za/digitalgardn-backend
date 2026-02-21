<?php

namespace App\Http\Middleware\Auth;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware qui verifie si l'utilisateur authentifie dispose d'un role specifique
 */
class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Recupere l'utilisateur authentifie
        $user = $request->user();
        // Verifie si l'utilisateur n'existe pas ou si son role est incorrect
        if (!$user || !in_array($user->role, $roles)) {
            // Bloque l'acces avec une reponse "Interdit" (403)
            return response()->json([], 403);
        }
        // Passe la requete
        return $next($request);
    }
}
