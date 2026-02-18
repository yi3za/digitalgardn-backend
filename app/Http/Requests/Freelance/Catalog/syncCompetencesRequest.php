<?php

namespace App\Http\Requests\Freelance\Catalog;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Requete pour gestion des competences de services
 */
class SyncCompetencesRequest extends FormRequest
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
            'competences' => ['required', 'array', 'min:1', 'max:5'],
            'competences.*' => [
                'required',
                'integer',
                // Pas de doublons
                'distinct',
                // Competence doit etre enfant
                'exists:competences,id,parent_id,NOT_NULL',
                // Competence doit etre active
                'exists:competences,id,est_active,1',
            ],
        ];
    }
}
