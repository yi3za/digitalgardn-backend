<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Evenement diffuse lorsqu'un message est envoye
 */
class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    // Message envoye
    public Message $message;
    /**
     * Constructeur
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }
    /**
     * Canal de diffusion prive de la conversation
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('conversations.' . $this->message->conversation_id),
        ];
    }
    /**
     * Nom de l'evenement cote client
     */
    public function broadcastAs(): string
    {
        return 'message.sent';
    }
    /**
     * Payload envoye aux deux participants
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,
            'conversation_id' => $this->message->conversation_id,
            'sender_id' => $this->message->sender_id,
            'content' => $this->message->content,
            'read_at' => $this->message->read_at,
            'created_at' => $this->message->created_at,
        ];
    }
}
