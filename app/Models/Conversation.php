<?php

namespace App\Models;

use App\Models\Message;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model : Conversation
 * Table : conversations
 */
class Conversation extends Model
{
    use HasFactory;
    // Champs pouvant etre remplis en masse
    protected $fillable = ['sender_id', 'receiver_id', 'last_message_at'];
    /**
     * Casts automatiques
     */
    protected function casts(): array
    {
        return [
            'last_message_at' => 'datetime',
        ];
    }
    /**
     * Relation : l'expediteur de la conversation
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
    /**
     * Relation : le destinataire de la conversation
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
    /**
     * Relation : une conversation possede plusieurs messages
     */
    public function messages()
    {
        return $this->hasMany(Message::class)->orderBy('created_at');
    }
    /**
     * Relation : dernier message de la conversation
     */
    public function latestMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }
}
