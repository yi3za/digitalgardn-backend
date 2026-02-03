<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
    /**
     * Relation : un service possede un fichier principale
     */
    public function fichierPrincipale()
    {
        return $this->hasOne(ServiceFichier::class)->where('est_principale', true);
    }
    /**
     * Private
     * Function helper : generer un slug unique a partir du titre
     */
    private function generateSlugUnique($titre)
    {
        // Initialiser le compteur pour eviter les doublons
        $counter = 1;
        // Generer un slug a partir du titre
        $slug = Str::slug($titre);
        // Tant que le slug existe deja, ajouter un suffixe
        while (static::where('slug', $slug)->exists()) {
            $slug = Str::slug($titre) . '-' . $counter++;
        }
        // Retourner le slug
        return $slug;
    }
    /**
     * Evenements du modele Service
     */
    protected static function booted()
    {
        /**
         * Avant la creation : generer automatiquement le slug
         */
        static::creating(function ($service) {
            // Generer un slug unique a partir du titre
            $service->slug = $service->generateSlugUnique($service->titre);
            // Remplir automatiquement le champ user_id avec l'utilisateur authentifie
            $service->user_id = auth('sanctum')->id();
        });
        /**
         * Avant la mise a jour :
         * - regenerer le slug uniquement si le titre change
         * - le service est encore en brouillon
         */
        static::updating(function ($service) {
            if ($service->isDirty('titre') && $service->statut === 'brouillon') {
                $service->slug = $service->generateSlugUnique($service->titre);
            }
        });
    }
}
