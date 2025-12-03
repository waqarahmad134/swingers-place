<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UpdateLastSeenAt
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Only update if last_seen_at is null or more than 1 minute ago (to reduce DB writes)
            if (!$user->last_seen_at || $user->last_seen_at->lt(now()->subMinute())) {
                $user->updateLastSeen();
            }
        }

        return $next($request);
    }
}
