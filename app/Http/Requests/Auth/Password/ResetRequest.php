<?php

namespace App\Http\Requests\Auth\Password;

use App\Http\Requests\Auth\AuthNormalizationRequest;
use Illuminate\Validation\Rule;

/**
 * Requete pour reinitialiser le mot de passe de l'utilisateur en utilisant le code de verification envoye par e-mail
 */
class ResetRequest extends AuthNormalizationRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255', 'exists:users,email', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
            'code' => ['required', 'string', 'size:6', Rule::exists('password_reset_tokens', 'token')->where(fn($query) => $query->where('email', $this->email)->where('created_at', '>=', now()->subMinutes(2)))],
            'password' => ['required', 'string', 'min:8', 'max:72', 'confirmed'],
        ];
    }
}
