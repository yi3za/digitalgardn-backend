<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Portefeuille extends Model
{
    // Champs pouvant etre remplis en masse
    protected $fillable = [
        'user_id',
        'solde_disponible',
        'solde_en_attente',
        'devise',
    ];
    // Casts pour les champs solde_disponible et solde_en_attente
    protected $casts = [
        'solde_disponible' => 'decimal:2',
        'solde_en_attente' => 'decimal:2',
    ];
    /**
     * Rolation : un portefeuille appartient a un utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    /**
     * Rolation : un portefeuille a plusieurs transactions
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
    /**
     * Accessor pour obtenir le solde total (disponible + en attente)
     */
    public function getTotalBalanceAttribute(): float
    {
        return (float) ($this->solde_disponible + $this->solde_en_attente);
    }
}
