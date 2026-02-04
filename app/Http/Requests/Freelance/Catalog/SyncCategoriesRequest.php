<?php

namespace App\Http\Requests\Freelance\Catalog;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Requete pour gestion des categories de services
 */
class SyncCategoriesRequest extends FormRequest
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
            'categories' => ['required', 'array', 'min:1', 'max:5'],
            'categories.*' => [
                'required',
                'integer',
                // Pas de doublons
                'distinct',
                // Categorie doit etre enfant
                'exists:categories,id,parent_id,NOT_NULL',
                // Categorie doit etre active
                'exists:categories,id,est_active,1',
            ],
        ];
    }
}
