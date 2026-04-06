<?php

namespace App\Services;

use App\Models\Conversation;
use App\Models\Message;

use Illuminate\Support\Facades\Http;

class TelegramService
{
    public function sendMessage($conversation, $text)
	{
		$customer = $conversation->customer;
        $bot = $conversation->bot;

	    $url = "https://api.telegram.org/bot{$bot->bot_token}/sendMessage";

	    $res = Http::post($url, [
	        'chat_id' => $customer->telegram_id,
	        'text' => $text,
	    ]);

	    $messageID = $res['result']['message_id'] ?? null;

	    Message::create([
            'conversation_id' => $conversation->id,
            'message_id' => $messageID,
            'message' => $text,
            'sender_type' => 'agent'
        ]);
	}
}
