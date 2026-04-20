<?php

namespace App\Http\Requests\Freelance\Catalog;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Requete de mise a jour du statut d'un service par un freelance
 */
class UpdateServiceStatusRequest extends FormRequest
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
            'statut' => ['required', 'in:en_pause,en_attente_approbation'],
        ];
    }
}
