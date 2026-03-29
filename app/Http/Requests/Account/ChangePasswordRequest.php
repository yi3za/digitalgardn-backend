<?php

namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Requete pour changer le mot de passe de l'utilisateur connecte en verifiant l'ancien mot de passe
 */
class ChangePasswordRequest extends FormRequest
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
            'old_password' => ['required', 'string', 'current_password', 'min:8', 'max:72'],
            'new_password' => ['required', 'string', 'min:8', 'max:72', 'confirmed', 'different:old_password'],
        ];
    }
}
