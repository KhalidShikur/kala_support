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
}
