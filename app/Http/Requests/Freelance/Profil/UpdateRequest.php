<?php

namespace App\Http\Requests\Freelance\Profil;

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
        return $this->user() !== null  && $this->user()->role === 'freelance';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'titre' => ['sometimes', 'nullable', 'string', 'max:255'],
            'biographie' => ['sometimes', 'nullable', 'string'],
            'image_couverture' => ['sometimes', 'nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:4096'],
            'site_web' => ['sometimes', 'nullable', 'url', 'max:255'],
            'liens_sociaux' => ['sometimes', 'nullable', 'json'],
        ];
    }
}
