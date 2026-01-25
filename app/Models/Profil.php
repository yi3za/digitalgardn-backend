<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profil extends Model
{
    // Definition de la cle primaire
    protected $primaryKey = 'user_id';
    // Desactivation de l'auto-incrementation car user_id est fourni depuis la table users
    public $incrementing = false;
    // Champs pouvant etre remplis en masse
    protected $fillable = [
        'user_id',
        'titre',
        'biographie',
        'image_couverture',
        'site_web',
        'liens_sociaux',
    ];
    /**
     * Relation : un profil appartient a un seul utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
