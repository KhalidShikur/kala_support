<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use Illuminate\Http\Request;

class WorkspaceController extends Controller
{
    public function create(Request $request) {
        $request->validate(['name'=>'required|string|max:255']);
        $workspace = Workspace::create([
            'name' => $request->name,
            'owner_id' => $request->user()->id,
        ]);

        $workspace->users()->attach($request->user()->id, ['role'=>'owner']);

        return response()->json($workspace, 201);
    }

    public function addUser(Request $request, Workspace $workspace) {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:admin,agent'
        ]);

        if($request->user()->id !== $workspace->owner_id) {
            return response()->json(['message'=>'Forbidden!'], 403);
        }

        $workspace->users()->syncWithoutDetaching([
            $request->user_id => ['role' => $request->role]
        ]);
        return response()->json(['message'=>'User added!']);
    }

    public function getAllUsers(Request $request, Workspace $workspace) {
        if($request->user()->id !== $workspace->owner_id) {
            return response()->json(['message'=>'Forbidden!'], 403);
        }

        return response()->json($workspace->users()->get());
    }
}
