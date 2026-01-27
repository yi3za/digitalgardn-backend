<?php

namespace App\Http\Requests\Account;
use App\Http\Requests\Auth\AuthNormalizationRequest;

/**
 * Requete pour le mise a jour des informations utilisateur
 * Herite de AuthNormalizationRequest pour normaliser username et email avant validation
 */
class UpdateRequest extends AuthNormalizationRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'username' => ['sometimes', 'string', 'alpha_dash', 'min:3', 'max:30', 'unique:users,username,' . $this->user()->id],
            'email' => ['sometimes', 'string', 'email', 'max:255', 'unique:users,email,' . $this->user()->id, 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
        ];
    }
}
