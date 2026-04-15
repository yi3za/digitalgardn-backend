<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
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
            'titre' => $this->titre,
            'slug' => $this->slug,
            'description' => $this->description,
            'prix_base' => $this->prix_base,
            'delai_livraison' => $this->delai_livraison,
            'revisions' => $this->revisions,
            'statut' => $this->statut,
            'ventes' => $this->ventes,
            'note_moyenne' => $this->note_moyenne,
            'user' => $this->whenLoaded('user'),
            'categories' => $this->whenLoaded('categories'),
            'competences' => $this->whenLoaded('competences'),
            'fichiers' => $this->whenLoaded('fichiers'),
            'fichierPrincipale' => $this->whenLoaded('fichierPrincipale'),
            'created_at' => $this->created_at,
        ];
    }
}
