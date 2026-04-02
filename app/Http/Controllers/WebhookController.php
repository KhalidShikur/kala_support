<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bot;
use App\Models\Message;
use App\Models\Customer;
use App\Models\Conversation;

class WebhookController extends Controller
{
    public function handle(Request $request, $botID) {
        $bot = Bot::findOrFail($botID);

        $workspaceId = $bot->workspace_id;

        $data = $request->all();

        $customer = Customer::firstOrCreate([
            'workspace_id' => $workspaceId,
            'telegram_id' => $data['message']['from']['id']
        ], [
            'username' => $data['message']['from']['username'] ?? null,
            'name' => $data['message']['from']['first_name'] ?? 'Unknown'
        ]);

        $conversation = Conversation::firstOrCreate([
            'bot_id' => $bot->id,
            'customer_id' => $customer->id
        ], [
            'workspace_id' => $workspaceId,
            'status' => 'open'
        ]);

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'message' => $data['message']['text'] ?? '',
            'sender_type' => 'customer'
        ]);

        return response()->json([$customer, $conversation, $message]);
    }
}
