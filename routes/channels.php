<?php

use App\Models\Commande;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

/**
 * Autorisation des canaux prives de messagerie
 */
Broadcast::channel('conversations.{conversationId}', function (User $user, int $conversationId) {
    return Conversation::query()
        ->where('id', $conversationId)
        ->where(function ($query) use ($user) {
            $query->where('sender_id', $user->id)
                ->orWhere('receiver_id', $user->id);
        })
        ->exists();
});

/**
 * Autorisation du canal prive utilisateur pour les notifications de nouvelles conversations
 */
Broadcast::channel('users.{userId}', function (User $user, int $userId) {
    return $user->id === $userId;
});

/**
 * Autorisation du canal prive de commande pour les notifications de changement de statut de commande
 */
Broadcast::channel('commandes.{commandeId}', function (User $user, int $commandeId) {
    $commande = Commande::query()->find($commandeId);
    if (!$commande) {
        return false;
    }
    return $commande->client_id === $user->id || $commande->freelance_id === $user->id;
});
