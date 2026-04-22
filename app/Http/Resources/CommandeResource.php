<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource pour une commande
 */
class CommandeResource extends JsonResource
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
            'client_id' => $this->client_id,
            'freelance_id' => $this->freelance_id,
            'service_id' => $this->service_id,
            'montant' => $this->montant,
            'statut' => $this->statut,
            'instructions' => $this->instructions,
            'date_livraison' => $this->date_livraison,
            'client' => $this->whenLoaded('client', function () {
                return [
                    'id' => $this->client?->id,
                    'name' => $this->client?->name,
                    'username' => $this->client?->username,
                    'avatar_url' => $this->client?->avatar_url,
                ];
            }),
            'freelance' => $this->whenLoaded('freelance', function () {
                return [
                    'id' => $this->freelance?->id,
                    'name' => $this->freelance?->name,
                    'username' => $this->freelance?->username,
                    'avatar_url' => $this->freelance?->avatar_url,
                ];
            }),
            'service' => $this->whenLoaded('service', function () {
                return new ServiceResource($this->service);
            }),
            'transactions' => $this->whenLoaded('transactions', function () {
                return TransactionResource::collection($this->transactions);
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
