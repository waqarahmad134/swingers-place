<?php

namespace App\Http\Middleware;

use App\Models\RegistrationSetting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRegistrationOpen
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $settings = RegistrationSetting::getSettings();
        
        if (!$settings->isRegistrationOpen()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Registration is currently closed. Please contact support for more information.'
                ], 403);
            }
            
            return redirect()->route('home')
                ->with('error', 'Registration is currently closed. Please contact support for more information.');
        }
        
        return $next($request);
    }
}
