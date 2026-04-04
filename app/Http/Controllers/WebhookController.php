<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bot;
use App\Jobs\ProcessTelegramMessage;

class WebhookController extends Controller
{
    public function handle(Request $request, $botID) {
        $bot = Bot::findOrFail($botID);

        $workspaceId = $bot->workspace_id;

        ProcessTelegramMessage::dispatch($botID, $workspaceId, $request->all());

        return response()->json(['status' => 'ok']);
    }
}
