<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Workspace;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function getAllMessages (Conversation $conversation) {
        $messages = Message::where('conversation_id', $conversation->id)
                                        ->orderBy('created_at')->get();

        return response()->json($messages);
    }
}
