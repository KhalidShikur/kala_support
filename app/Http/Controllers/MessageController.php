<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Customer;
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

    public function countMessages (Workspace $workspace) {
        $workspaceID = $workspace->id;
        $count = Message::whereHas('conversation', function ($q) use ($workspaceID) {
            $q->where('workspace_id', $workspaceID);
        })->count();

        return response()->json(['count' => $count]);
    }

    public function countCustomers (Workspace $workspace) {
        $workspaceID = $workspace->id;
        $count = Customer::where('workspace_id', $workspaceID)->count();

        return response()->json(['count' => $count]);
    }

    public function avgResponseTime (Conversation $conversation) {
        $messages = $conversation->messages->sortBy('created_at');

        $customerMessage = null;
        $agentReply = null;

        foreach ($messages as $msg) {
            if ($msg->sender_type === 'customer' && !$customerMessage) {
                $customerMessage = $msg;
            }

            if ($msg->sender_type === 'agent' && $customerMessage) {
                $agentReply = $msg;
                break;
            }
        }

        $responseTime = ($customerMessage && $agentReply) ? 
        $agentReply->created_at->diffInSeconds($customerMessage->created_at) : null;

        return response()->json(['response_time' => $responseTime]);
    }
}
