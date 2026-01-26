<?php

namespace App\Http\Middleware\Auth;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use function Symfony\Component\Clock\now;

class ActivityMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Recuperer l'utilisateur authentifie
        $user = $request->user();
        // Mettre a jour la derniere activite
        $user->updateQuietly([
            'derniere_activite' => now(),
        ]);
        // Continuer la requete
        return $next($request);
    }
}
