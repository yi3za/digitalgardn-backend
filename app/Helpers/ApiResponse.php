<?php

namespace App\Helpers;
use Illuminate\Http\JsonResponse;

/**
 * ApiResponse fournit une methode statique pour generer des reponses JSON uniformes
 */
class ApiResponse
{
    /**
     * Retourne une reponse JSON standardisee pour l'API
     */
    public static function send(string $code, int $httpStatus, $details = null): JsonResponse
    {
        // Génère le JSON avec la structure uniforme
        return response()->json(
            [
                'code' => $code,
                'details' => $details,
            ],
            $httpStatus,
        );
    }
}
