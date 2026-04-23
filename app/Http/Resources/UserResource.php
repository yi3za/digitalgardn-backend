<?php

namespace App\Http\Resources;

use App\Constants\TableStates\UserRoleState;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource pour l'utilisateur
 */
class UserResource extends JsonResource
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
            'email' => $this->email,
            'role' => $this->role,
            'status' => $this->status,
            'derniere_activite' => $this->derniere_activite,
            'onboarding_termine' => $this->onboarding_termine,
            'avatar_url' => $this->avatar_url,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'profil' => $this->when($this->role === UserRoleState::FREELANCE, new ProfilResource($this->profil)),
            'competences' => $this->when($this->role === UserRoleState::FREELANCE, $this->competences?->pluck('id')),
        ];
    }
}
