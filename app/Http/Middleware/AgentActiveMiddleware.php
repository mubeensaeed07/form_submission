<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AgentActiveMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || $user->role !== 'agent') {
            abort(403);
        }

        if ($user->status !== 'active') {
            // Non-active agents should see waiting page instead
            return redirect()->route('agent.waiting');
        }

        return $next($request);
    }
}


