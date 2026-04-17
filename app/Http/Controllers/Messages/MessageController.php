<?php

namespace App\Http\Controllers\Messages;

use App\Events\MessageSent;
use App\Helpers\ApiCodes;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Messages\StoreMessageRequest;
use App\Http\Resources\MessageResource;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Gestion des messages d'une conversation
 */
class MessageController extends Controller
{
    /**
     * Liste les messages d'une conversation donnee
     */
    public function index(Request $request, Conversation $conversation)
    {
        // Recuperer l'utilisateur connecte
        $user = $request->user();
        // Verifier que l'utilisateur fait partie de la conversation
        if (!$this->isConversationParticipant($user->id, $conversation)) {
            return ApiResponse::send(ApiCodes::FORBIDDEN, 403);
        }
        // Marquer comme lus les messages recus non encore lus
        $conversation
            ->messages()
            ->where('sender_id', '!=', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
        // Recuperer les messages avec l'auteur de chaque message
        $messages = $conversation->messages()->with('sender')->get();
        // Retourner la liste des messages
        return ApiResponse::send(ApiCodes::SUCCESS, 200, ['messages' => MessageResource::collection($messages)]);
    }
    /**
     * Envoie un nouveau message dans une conversation
     */
    public function store(StoreMessageRequest $request, Conversation $conversation)
    {
        // Recuperer l'utilisateur connecte
        $user = $request->user();
        // Verifier que l'utilisateur fait partie de la conversation
        if (!$this->isConversationParticipant($user->id, $conversation)) {
            return ApiResponse::send(ApiCodes::FORBIDDEN, 403);
        }
        // Creer le message et mettre a jour la date du dernier message
        $message = DB::transaction(function () use ($request, $conversation, $user) {
            // Creer le message
            $message = $conversation->messages()->create([
                'sender_id' => $user->id,
                'content' => $request->validated('content'),
            ]);
            // Mettre a jour la date du dernier message de la conversation
            $conversation->update([
                'last_message_at' => now(),
            ]);
            return $message->load('sender');
        });
        // Broadcast du message aux deux participants de la conversation
        broadcast(new MessageSent($message));
        // Retourner le message cree
        return ApiResponse::send(ApiCodes::SUCCESS, 200, ['message' => new MessageResource($message)]);
    }
    /**
     * Verifie si l'utilisateur appartient a la conversation
     */
    private function isConversationParticipant(int $userId, Conversation $conversation): bool
    {
        return $conversation->sender_id === $userId || $conversation->receiver_id === $userId;
    }
}
