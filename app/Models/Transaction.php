<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    // Le schema contient created_at uniquement (pas de updated_at)
    const UPDATED_AT = null;
    // Champs pouvant etre remplis en masse
    protected $fillable = ['portefeuille_id', 'commande_id', 'type', 'montant'];
    // Casts pour le champ montant
    protected $casts = [
        'montant' => 'decimal:2',
    ];
    /**
     * Rolation : une transaction appartient a un portefeuille
     */
    public function portefeuille(): BelongsTo
    {
        return $this->belongsTo(Portefeuille::class);
    }
    /**
     * Rolation : une transaction appartient a une commande
     */
    public function commande(): BelongsTo
    {
        return $this->belongsTo(Commande::class);
    }
}
