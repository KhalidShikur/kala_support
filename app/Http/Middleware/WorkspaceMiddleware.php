<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WorkspaceMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $workspace = $request->route('workspace');

        $belongs = auth()->user()
                            ->workspaces()
                            ->where('workspaces.id', $workspace->id)
                            ->exists();

        if(!$belongs) {
            return response()->json(['message'=>'Unauthorized workspace'], 403);
        }

        return $next($request);
    }
}
