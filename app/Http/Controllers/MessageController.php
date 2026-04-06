<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Workspace;
use Illuminate\Http\Request;
use App\Services\TelegramService;

class MessageController extends Controller
{
    protected $telegramService;

    public function __construct(TelegramService $telegramService) {
        $this->telegramService = $telegramService;
    }

    public function getAllMessages (Conversation $conversation) {
        $messages = Message::where('conversation_id', $conversation->id)
                                        ->orderBy('created_at')->get();

        return response()->json($messages);
    }

    public function sendMessage (Request $request, Conversation $conversation) {
        $this->telegramService->sendMessage(
            $conversation,
            $request->message
        );

        return response()->json(['message' => 'message sent successfully!']);
    }
}
