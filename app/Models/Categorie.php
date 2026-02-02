<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model : Categorie
 * Table : categories
 */
class Categorie extends Model
{
    // Permet d'utiliser les factories pour ce modele
    use HasFactory;
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
    /**
     * Private
     * Function helper : recupere les services d'une categorie avec leurs details (user, fichier)
     */
    private function fetchServicesForEnfant($enfant, $whereServices, $whereUser)
    {
        // Recuperer les services de la categorie
        $queryServices = $enfant->services();
        // Appliquer des conditions sur les services si fournies
        if ($whereServices) {
            $queryServices->where($whereServices);
        }
        // Appliquer des conditions sur les utilisateurs si fournies
        if ($whereUser) {
            $queryServices->whereHas('user', fn($q) => $q->where($whereUser));
        }
        // Charger les relations user et fichierPrincipale
        $queryServices->with('user', 'fichierPrincipale');
        // Retourner les services
        return $queryServices->get();
    }
    /**
     * Relation : recupere tous les services des enfants d'une categorie principale
     */
    public function servicesAvecDetails($whereEnfants = [], $whereServices = [], $whereUser = [])
    {
        // Si categorie enfant, retourner ses services directement
        if ($this->parent_id !== null) {
            return $this->fetchServicesForEnfant($this, $whereServices, $whereUser);
        }
        // Sinon la categorie parent, recuperer les services de tous les enfants
        // Recuperer la relation enfants
        $queryEnfants = $this->enfants();
        // Appliquer des conditions sur les enfants si fournies
        if ($whereEnfants) {
            $queryEnfants->where($whereEnfants);
        }
        // Executer la requete pour obtenir les enfants
        $enfants = $queryEnfants->get();
        // Parcourir chaque enfant pour recuperer ses services
        return $enfants->flatMap(fn($enfant) => $this->fetchServicesForEnfant($enfant, $whereServices, $whereUser));
    }
}
