<?php

namespace App\Models;

use App\Traits\HasHierarchicalChildren;
use App\Traits\HasImageUrl;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model : Competence
 * Table : competences
 */
class Competence extends Model
{
    // HasFactory: Permet d'utiliser les factories pour ce modele
    // HasHierarchicalChildren: Gerer les relations hierarchiques et recuperer les services
    // HasImageUrl: Generer l'URL d'une image stockee
    use HasFactory, HasHierarchicalChildren, HasImageUrl;
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
    /**
     * Ajoute automatiquement l'attribut 'icone_url' au JSON du modele
     *
     * Remarque : chaque nom inscrit dans $appends doit avoir un accessor correspondant
     * Exemple : 'test' dans $appends necessite la methode 'getTestAttribute()'
     */
    protected $appends = ['icone_url'];
    // Accessor pour recuperer l'URL complete de l'icone
    public function getIconeUrlAttribute()
    {
        // Utilise la methode getImageUrl du Trait  pour generer l'URL complete
        return $this->getImageUrl('icone', 'icones/competences/default.avif');
    }
}
