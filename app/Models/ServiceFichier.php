<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model : ServiceFichier
 * Table : service_fichiers
 */
class ServiceFichier extends Model
{
    // Permet d'utiliser les factories pour ce modele
    use HasFactory;
    // Champs pouvant etre remplis en masse
    protected $fillable = ['service_id', 'chemin', 'type', 'ordre', 'est_principale'];
    /**
     * Relation : un fichier appartient a un seul service
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
