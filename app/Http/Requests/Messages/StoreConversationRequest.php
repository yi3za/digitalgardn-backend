<?php

namespace App\Http\Requests\Messages;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Requete pour creer une conversation entre deux utilisateurs
 */
class StoreConversationRequest extends FormRequest
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
            'receiver_id' => ['required', 'integer', 'exists:users,id', 'not_in:' . $this->user()->id],
            'commande_id' => ['nullable', 'integer', 'exists:commandes,id'],
        ];
    }
}
