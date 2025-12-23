<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Allow admin and editor users to bypass maintenance mode
        $user = $request->user();
        if ($user && ((bool) ($user->is_admin ?? false) || (bool) ($user->is_editor ?? false))) {
            return $next($request);
        }

        // Check maintenance mode from config
        $isMaintenanceEnabled = config('app.maintenance_mode', false);

        // Allow access to admin and editor routes even during maintenance
        if ($isMaintenanceEnabled && !$request->is('admin*') && !$request->is('editor*') && !$request->is('login') && !$request->is('register')) {
            return response()->view('pages.maintenance', [], 503);
        }

        return $next($request);
    }
}

