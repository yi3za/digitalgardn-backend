<?php

namespace App\Models;

use App\Traits\HasImageUrl;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * Model : ServiceFichier
 * Table : service_fichiers
 */
class ServiceFichier extends Model
{
    // Permet d'utiliser les factories pour ce modele
    // HasImageUrl (chemin)
    use HasFactory, HasImageUrl;
    // Champs pouvant etre remplis en masse
    protected $fillable = ['service_id', 'chemin', 'type', 'ordre', 'est_principale'];
    /**
     * Relation : un fichier appartient a un seul service
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
    /**
     * Ajoute automatiquement l'attribut 'chemin_url' au JSON du modele
     *
     * Remarque : chaque nom inscrit dans $appends doit avoir un accessor correspondant
     * Exemple : 'test' dans $appends necessite la methode 'getTestAttribute()'
     */
    protected $appends = ['chemin_url'];
    // Accessor pour recuperer l'URL complete de l'chemin
    public function getCheminUrlAttribute()
    {
        // Utilise la methode getImageUrl du Trait  pour generer l'URL complete
        return $this->getImageUrl('chemin', 'services/images/default.webp');
    }
    /**
     * Evenements du modele ServiceFichier
     */
    protected static function booted()
    {
        /**
         * Avant la suppression : supprime le fichier du storage
         */
        static::deleting(function ($fichier) {
            // Supprime le fichier stocke
            Storage::disk('public')->delete($fichier->chemin);
        });
    }
}
