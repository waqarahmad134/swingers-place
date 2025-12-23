<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EditorMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Allow both admin and editor access (admin has all access)
        if (! $user || (! (bool) ($user->is_admin ?? false) && ! (bool) ($user->is_editor ?? false))) {
            abort(403, 'Unauthorized. Admin or Editor access required.');
        }

        return $next($request);
    }
}
