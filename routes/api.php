<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WorkspaceController;

Route::get('/me', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('hello', function (Request $request) {
    return response()->json(["message"=>"Excellent"]);
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::post('workspaces', [WorkspaceController::class, 'create'])->middleware('auth:sanctum');

Route::post('workspaces/{workspace}/add-user', [WorkspaceController::class, 'addUser'])->middleware('auth:sanctum');