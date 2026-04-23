<?php

namespace App\Http\Controllers\Messages;

use App\Helpers\ApiCodes;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Messages\StoreConversationRequest;
use App\Http\Resources\ConversationResource;
use App\Models\Conversation;
use Illuminate\Http\Request;

/**
 * Gestion des conversations de messagerie
 */
class ConversationController extends Controller
{
    /**
     * Liste des conversations de l'utilisateur connecte
     */
    public function index(Request $request)
    {
        // Recupere l'utilisateur connecte
        $user = $request->user();
        // Recupere ses conversations avec les utilisateurs lies et le dernier message
        // - Si l'utilisateur est le sender, affiche toujours
        // - Si l'utilisateur est le receiver, affiche seulement s'il y a au moins un message
        $conversations = Conversation::with(['sender', 'receiver', 'latestMessage.sender'])
            ->where(function ($query) use ($user) {
                // Cas 1: L'utilisateur est le sender
                $query
                    ->where('sender_id', $user->id)
                    // Cas 2: L'utilisateur est le receiver ET il y a des messages
                    ->orWhere(function ($q) use ($user) {
                    $q->where('receiver_id', $user->id)->whereHas('messages');
                });
            })
            ->orderByDesc('last_message_at')
            ->orderByDesc('updated_at')
            ->get();
        // Retourner la liste des conversations
        return ApiResponse::send(ApiCodes::SUCCESS, 200, ['conversations' => ConversationResource::collection($conversations)]);
    }
    /**
     * Creation d'une nouvelle conversation entre deux utilisateurs
     */
    public function store(StoreConversationRequest $request)
    {
        // Donnees validees
        $data = $request->validated();
        $receiverId = (int) $data['receiver_id'];
        $userId = $request->user()->id;
        // Chercher la conversation existante dans n'importe quel direction
        $conversation = Conversation::whereNull('commande_id')
            ->where(function ($query) use ($userId, $receiverId) {
                $query->where(['sender_id' => $userId, 'receiver_id' => $receiverId])->orWhere(['sender_id' => $receiverId, 'receiver_id' => $userId]);
            })
            ->first();
        // Creer la conversation si elle n'existe pas
        if (!$conversation) {
            $conversation = Conversation::create([
                'sender_id' => $userId,
                'receiver_id' => $receiverId,
                'commande_id' => null,
            ]);
        }
        // Retourner la conversation creee ou existante
        return ApiResponse::send(ApiCodes::SUCCESS, 200, ['conversation' => new ConversationResource($conversation)]);
    }
}
