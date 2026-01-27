<?php

namespace App\Http\Middleware\Auth;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use function Symfony\Component\Clock\now;

/**
 * Middleware pour mettre à jour la derniere activite
 */
class ActivityMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Recupere l'utilisateur authentifie
        $user = $request->user();
        // Met a jour la derniere activite de l'utilisateur sans declencher les evenements
        $user->updateQuietly([
            'derniere_activite' => now(),
        ]);
        // Passe la requete
        return $next($request);
    }
}
