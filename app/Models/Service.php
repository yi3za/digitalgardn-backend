<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model : Service
 * Table : services
 */
class Service extends Model
{
    // Permet d'utiliser les factories pour ce modele
    use HasFactory;
    // Champs pouvant etre remplis en masse
    protected $fillable = ['user_id', 'titre', 'slug', 'description', 'prix_base', 'delai_livraison', 'revisions', 'statut', 'ventes', 'note_moyenne'];
    /**
     * Relation : un service appartient a un seul utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    /**
     * Relation : un service appartient a plusieurs categories
     */
    public function categories()
    {
        return $this->belongsToMany(Categorie::class)->orderBy('ordre');
    }
    /**
     * Relation : un service possede plusieurs fichiers
     */
    public function fichiers()
    {
        return $this->hasMany(ServiceFichier::class)->orderBy('ordre');
    }
}
