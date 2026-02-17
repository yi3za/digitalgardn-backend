<?php

namespace App\Models;

use App\Traits\HasHierarchicalChildren;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model : Categorie
 * Table : categories
 */
class Categorie extends Model
{
    // HasFactory: Permet d'utiliser les factories pour ce modele
    // HasHierarchicalChildren: Gerer les relations hierarchiques et recuperer les services
    use HasFactory, HasHierarchicalChildren;
    // Champs pouvant etre remplis en masse
    protected $fillable = ['parent_id', 'nom', 'slug', 'description', 'icone', 'ordre', 'est_active'];
    /**
     * Relation : une categorie appartient a une seule categorie parent
     */
    public function parent()
    {
        return $this->belongsTo(Categorie::class, 'parent_id');
    }
    /**
     * Relation : une categorie parent possede plusieurs categories enfants
     */
    public function enfants()
    {
        return $this->hasMany(Categorie::class, 'parent_id');
    }
    /**
     * Relation : une categorie appartient a plusieurs services
     */
    public function services()
    {
        return $this->belongsToMany(Service::class);
    }
}
