<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BotController;
use App\Http\Controllers\WorkspaceController;
use App\Http\Controllers\WebhookController;
use App\Models\Workspace;
use App\Jobs\ProcessMessage;

Route::get('/me', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('hello', function (Request $request) {
    return response()->json(["message"=>"Excellent"]);
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::post('workspaces', [WorkspaceController::class, 'create'])->middleware('auth:sanctum');

Route::middleware(['auth:sanctum, WorkspaceMiddleware'])->group(function () {
    Route::post('workspaces/{workspace}/add-user', [WorkspaceController::class, 'addUser']);
    Route::get('workspaces/{workspace}/users', [WorkspaceController::class, 'getAllUsers']);
    Route::post('workspaces/{workspace}/bot', [BotController::class, 'create']);
});

Route::post('webhooks/telegram/{bot}', [WebhookController::class, 'handle']);


Route::get('/send-job', function () {
    ProcessMessage::dispatch('Test message');
    return 'Job dispatched!';
});
