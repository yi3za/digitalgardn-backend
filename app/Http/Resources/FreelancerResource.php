<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource pour l'affichage public d'un freelance
 */
class FreelancerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'avatar_url' => $this->avatar_url,
            'profil' => new ProfilResource($this->whenLoaded('profil')),
            'competences' => $this->whenLoaded(
                'competences',
                fn() => $this->competences->map(fn($competence) => [
                    'id' => $competence->id,
                    'nom' => $competence->nom,
                    'slug' => $competence->slug,
                ])->values(),
            ),
        ];
    }
}
