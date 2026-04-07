<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfilResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user_id' => $this->user_id,
            'titre' => $this->titre,
            'biographie' => $this->biographie,
            'site_web' => $this->site_web,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
