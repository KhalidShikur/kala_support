<?php

namespace App\Http\Controllers;

use App\Models\Bot;
use App\Models\Workspace;
use Illuminate\Http\Request;

class BotController extends Controller
{
    public function create(Request $request, Workspace $workspace) {
        $request->validate([
            'bot_name' => 'required|string|max:255',
            'bot_username' => 'required|string|max:255',
            'bot_token' => 'required|string|max:255'
        ]);

        if($request->user()->id !== $workspace->owner_id) {
            return response()->json(['message'=>'Forbidden!'], 403);
        }

        $bot = Bot::create([
            'workspace_id' => $workspace->id,
            'bot_name' => $request->bot_name,
            'bot_username' => $request->bot_username,
            'bot_token' => $request->bot_token,
        ]);

        return response()->json($bot);
    }
}
