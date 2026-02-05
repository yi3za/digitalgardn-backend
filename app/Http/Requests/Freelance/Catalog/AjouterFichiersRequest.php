<?php

namespace App\Http\Requests\Freelance\Catalog;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Requete pour gestion des fichiers de services
 */
class AjouterFichiersRequest extends FormRequest
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
            'fichiers' => ['required', 'array', 'min:1', 'max:10'],
            'fichiers.*' => ['required', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'],
        ];
    }
}
