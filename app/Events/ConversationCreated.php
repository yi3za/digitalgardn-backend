<?php

namespace App\Events;

use App\Models\Conversation;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Evenement diffuse lorsqu'une nouvelle conversation est creee
 */
class ConversationCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    // Conversation creee
    public Conversation $conversation;
    /**
     * Constructeur
     */
    public function __construct(Conversation $conversation)
    {
        $this->conversation = $conversation;
    }
    /**
     * Diffuser vers les deux utilisateurs participants
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('users.' . $this->conversation->sender_id),
            new PrivateChannel('users.' . $this->conversation->receiver_id),
        ];
    }
    /**
     * Nom de l'evenement cote client
     */
    public function broadcastAs(): string
    {
        return 'conversation.created';
    }
    /**
     * Payload minimal pour invalider puis recharger les conversations cote client
     */
    public function broadcastWith(): array
    {
        return [
            'conversation_id' => $this->conversation->id,
            'sender_id' => $this->conversation->sender_id,
            'receiver_id' => $this->conversation->receiver_id,
            'created_at' => $this->conversation->created_at,
        ];
    }
}
