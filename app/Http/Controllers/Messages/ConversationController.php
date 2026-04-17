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
        $conversations = Conversation::with(['sender', 'receiver', 'latestMessage.sender'])
            ->where(function ($query) use ($user) {
                $query->where('sender_id', $user->id)->orWhere('receiver_id', $user->id);
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
        // Normaliser l'ordre des deux utilisateurs pour garantir l'unicite
        $ids = [$request->user()->id, (int) $data['receiver_id']];
        sort($ids);
        // Creer la conversation si elle n'existe pas deja
        $conversation = Conversation::firstOrCreate([
            'sender_id' => $ids[0],
            'receiver_id' => $ids[1],
        ]);
        // Retourner la conversation creee ou existante
        return ApiResponse::send(ApiCodes::SUCCESS, 200, ['conversation' => new ConversationResource($conversation)]);
    }
}
