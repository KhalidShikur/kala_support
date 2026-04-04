<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Workspace;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function getAllConversations (Request $request, Workspace $workspace) {
        $conversations = Conversation::where('workspace_id', $workspace->id)
                                        ->with('customer', 'messages')
                                        ->latest()->get();

        return response()->json($conversations);
    }

    public function getConversation (Request $request, Workspace $workspace, Conversation $conversation) {
        $conversation = Conversation::where('workspace_id', $workspace->id)
                                        ->where('id', $conversation->id)
                                        ->with('customer', 'messages')
                                        ->firstOrFail();

        return response()->json($conversation);
    }
}
