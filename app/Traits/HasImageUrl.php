<?php

namespace App\Traits;

/**
 * Trait pour generer l'URL d'une image stockee
 */
trait HasImageUrl
{
    /**
     * Retourne l'URL complete de l'image ou de l'image par defaut
     */
    public function getImageUrl(string $field, string $default): string
    {
        // Si la propriete existe et contient un chemin, retourne l'URL de l'image
        // Sinon, retourne l'URL de l'image par defaut
        return $this->{$field} ? asset('storage') . '/' . $this->{$field} : asset('storage') . '/' . $default;
    }
}
