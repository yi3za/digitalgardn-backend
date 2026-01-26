<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Classe de base pour normaliser email et username avant validation dans les requetes d'authentification
 */
abstract class AuthNormalizationRequest extends FormRequest
{
    /**
     * Conversion de l'email et du nom d'utilisateur en minuscules avant la validation
     */
    protected function prepareForValidation()
    {
        // Tableau temporaire pour stocker les champs a normaliser
        $data = [];
        // Verifier si le champ "username" est present dans la requete
        if ($this->has('username')) {
            // Conversion du nom d'utilisateur en minuscules
            $data['username'] = strtolower($this->input('username'));
        }
        // Verifier si le champ "email" est present dans la requete
        if ($this->has('email')) {
            // Conversion de l'adresse email en minuscules
            $data['email'] = strtolower($this->input('email'));
        }
        // Fusionner les donnees normalisees avec la requete avant validation
        return $this->merge($data);
    }
}
