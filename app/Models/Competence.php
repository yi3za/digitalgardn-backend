<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model : Competence
 * Table : competences
 */
class Competence extends Model
{
    // Permet d'utiliser les factories pour ce modele
    use HasFactory;
    // Champs pouvant etre remplis en masse
    protected $fillable = ['parent_id', 'nom', 'slug', 'description', 'icone', 'ordre', 'est_active'];
    /**
     * Relation : une competence appartient a une seule competence parent
     */
    public function parent()
    {
        return $this->belongsTo(Competence::class, 'parent_id');
    }
    /**
     * Relation : une competence parent possede plusieurs competences enfants
     */
    public function enfants()
    {
        return $this->hasMany(Competence::class, 'parent_id');
    }
    /**
     * Relation : une competence appartient a plusieurs services
     */
    public function services()
    {
        return $this->belongsToMany(Service::class);
    }
    /**
     * Relation : une competence appartient a plusieurs utilisateurs
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
