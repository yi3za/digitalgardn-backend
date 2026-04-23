<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource pour une conversation
 */
class ConversationResource extends JsonResource
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
            'sender_id' => $this->sender_id,
            'receiver_id' => $this->receiver_id,
            'sender' => $this->whenLoaded('sender', function () {
                return [
                    'id' => $this->sender?->id,
                    'name' => $this->sender?->name,
                    'username' => $this->sender?->username,
                    'avatar_url' => $this->sender?->avatar_url,
                ];
            }),
            'receiver' => $this->whenLoaded('receiver', function () {
                return [
                    'id' => $this->receiver?->id,
                    'name' => $this->receiver?->name,
                    'username' => $this->receiver?->username,
                    'avatar_url' => $this->receiver?->avatar_url,
                ];
            }),
            'latest_message' => $this->whenLoaded('latestMessage', function () {
                return new MessageResource($this->latestMessage);
            }),
            'commande' => $this->whenLoaded('commande', function () {
                return [
                    'id' => $this->commande?->id,
                    'statut' => $this->commande?->statut,
                    'client_id' => $this->commande?->client_id,
                    'freelance_id' => $this->commande?->freelance_id,
                    'montant' => $this->commande?->montant,
                ];
            }),
            'last_message_at' => $this->last_message_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
