<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Commande extends Model
{
    // Champs pouvant etre remplis en masse
    protected $fillable = ['client_id', 'freelance_id', 'service_id', 'montant', 'statut', 'instructions', 'date_livraison'];
    // Casts pour les champs montant et date_livraison
    protected $casts = [
        'montant' => 'decimal:2',
        'date_livraison' => 'datetime',
    ];
    /**
     * Rolation : une commande appartient a un client (utilisateur)
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }
    /**
     * Rolation : une commande appartient a un freelance (utilisateur)
     */
    public function freelance(): BelongsTo
    {
        return $this->belongsTo(User::class, 'freelance_id');
    }
    /**
     * Rolation : une commande appartient a un service
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
    /**
     * Rolation : une commande a plusieurs transactions
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
