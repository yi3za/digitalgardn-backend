<?php

namespace App\Http\Requests\Freelance\Catalog;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Requete de mise a jour d'un service par un freelance
 */
class UpdateServiceRequest extends FormRequest
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
            'titre' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'required', 'string'],
            'prix_base' => ['sometimes', 'required', 'numeric', 'min:0'],
            'delai_livraison' => ['sometimes', 'required', 'integer', 'min:1'],
            'revisions' => ['sometimes', 'nullable', 'integer', 'min:0'],
            'statut' => ['sometimes', 'required', 'in:en_pause,en_attente_approbation'],
        ];
    }
}
