<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bot;
use App\Models\Message;

class WebhookController extends Controller
{
    public function handle(Request $request, $botID) {
        $bot = Bot::findOrFail($botID);

        $workspaceId = $bot->workspace_id;

        $data = $request->all();
        $message = Message::create([
            'workspace_id' => $workspaceId,
            'message' => $data['message']['text'] ?? '',
            'sender_type' => 'customer'
        ]);

        return response()->json($message);
    }
}
