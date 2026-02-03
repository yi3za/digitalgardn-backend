<?php

namespace App\Http\Requests\Freelance\Catalog;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Requete pour la creation des services par un freelance
 */
class StoreServiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->role === 'freelance';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'titre' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'prix_base' => ['required', 'numeric', 'min:0'],
            'delai_livraison' => ['required', 'integer', 'min:1'],
            'revisions' => ['nullable', 'integer', 'min:0'],
            'ventes' => ['nullable', 'integer', 'min:0'],
            'note_moyenne' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'vues' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
