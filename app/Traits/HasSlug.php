<?php

namespace App\Traits;

use Illuminate\Support\Str;

/**
 * Trait pour generer automatiquement un slug unique
 */
trait HasSlug
{
    /**
     * Private
     * Function helper : generer un slug unique
     */
    private function generateSlugUnique($value)
    {
        // Initialiser le compteur pour eviter les doublons
        $counter = 1;
        // Generer un slug
        $slug = Str::slug($value);
        // Tant que le slug existe deja, ajouter un suffixe
        while (static::where('slug', $slug)->exists()) {
            $slug = Str::slug($value) . '-' . $counter++;
        }
        // Retourner le slug
        return $slug;
    }
}
