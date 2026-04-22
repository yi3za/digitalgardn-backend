<?php

namespace App\Http\Requests\Freelance\Profil;

use App\Constants\TableStates\UserRoleState;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Requete pour le mise a jour des informations du profil de l'utilisateur
 */
class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null  && $this->user()->role === UserRoleState::FREELANCE;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'titre' => ['sometimes', 'required', 'min:10', 'string', 'max:255'],
            'biographie' => ['sometimes', 'required', 'min:150', 'max:600', 'string'],
            'site_web' => ['sometimes', 'nullable', 'url', 'max:255'],
        ];
    }
}
