<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model : ServiceFichier
 * Database : service_fichiers
 */
class ServiceFichier extends Model
{
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
