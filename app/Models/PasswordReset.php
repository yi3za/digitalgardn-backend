<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    // Nom de la table associee a ce model
    protected $table = 'password_reset_tokens';
    // Definit le champ email comme cle primaire de la table
    protected $primaryKey = 'email';
    // La cle primaire n'est pas auto-incrementee
    public $incrementing = false;
    // La table ne contient pas les champs (created_at & updated_at)
    public $timestamps = false;
    // Champs autorises pour l'insertion et la mise a jour
    protected $fillable = [
        'email','token','created_at'
    ];
}
