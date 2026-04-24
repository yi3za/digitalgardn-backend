<?php

namespace App\Events;

use App\Models\Commande;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Evenement diffuse lorsqu'une commande change de statut
 */
class CommandeStatusUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public Commande $commande;
    /**
     * Constructeur
     */
    public function __construct(Commande $commande)
    {
        $this->commande = $commande;
    }
    /**
     * Canal prive de la commande
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('commandes.' . $this->commande->id),
        ];
    }
    /**
     * Nom de l'evenement cote client
     */
    public function broadcastAs(): string
    {
        return 'status.updated';
    }
    /**
     * Payload envoye aux deux participants
     */
    public function broadcastWith(): array
    {
        return [];
    }
}
