<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource pour le portefeuille
 */
class PortefeuilleResource extends JsonResource
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
            'solde_disponible' => $this->solde_disponible,
            'solde_en_attente' => $this->solde_en_attente,
            'solde_total' => $this->total_balance,
            'devise' => $this->devise,
        ];
    }
}
