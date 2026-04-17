<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model : Message
 * Table : messages
 */
class Message extends Model
{
    use HasFactory;
    // Champs pouvant etre remplis en masse
    protected $fillable = ['conversation_id', 'sender_id', 'content', 'read_at'];
    /**
     * Casts automatiques
     */
    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }
    /**
     * Relation : un message appartient a une conversation
     */
    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }
    /**
     * Relation : auteur du message
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
