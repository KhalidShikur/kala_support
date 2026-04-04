<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Models\Customer;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\Cache;

class ProcessTelegramMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $botId;
    public int $workspaceId;
    public array $payload;

    public function __construct(int $botId, int $workspaceId, array $payload)
    {
        $this->botId = $botId;
        $this->workspaceId = $workspaceId;
        $this->payload = $payload;
        $this->queue = 'default'; // optional, can create high/low priority queues
    }

    public function handle(): void
    {
        $messageData = $this->payload['message'] ?? null;

        if (!$messageData) {
            Log::warning('Invalid Telegram payload', $this->payload);
            return;
        }

        $lockKey = 'telegram_message_' . $messageData['message_id'];

        if (!Cache::lock($lockKey, 10)->get()) {
            return;
        }

        $from = $messageData['from'] ?? null;

        if (!$from) {
            Log::warning('Missing sender info', $messageData);
            return;
        }

        Log::info('Processing Telegram message: ' . json_encode($messageData));

        // 1️⃣ Find or create customer
        $customer = Customer::firstOrCreate([
            'workspace_id' => $this->workspaceId,
            'telegram_id' => $from['id']
        ], [
            'username' => $from['username'] ?? null,
            'name' => $from['first_name'] ?? 'Unknown'
        ]);

        // 2️⃣ Find or create conversation
        $conversation = Conversation::firstOrCreate([
            'bot_id' => $this->botId,
            'customer_id' => $customer->id
        ], [
            'workspace_id' => $this->workspaceId,
            'status' => 'open'
        ]);

        // 3️⃣ Store the message
        Message::firstOrCreate([
            'conversation_id' => $conversation->id,
            'message_id' => $messageData['message_id'],
        ], [
            'message' => $messageData['text'] ?? '',
            'sender_type' => 'customer'
        ]);

        Log::info('Telegram message processed successfully.');
    }
}
