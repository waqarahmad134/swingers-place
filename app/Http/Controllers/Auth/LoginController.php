<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm(): View
    {
        return view('pages.auth.login');
    }

    /**
     * Handle a login request.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Update last seen at on login
            Auth::user()->updateLastSeen();

            // Redirect based on user role
            if (Auth::user()->is_admin) {
                return redirect()->intended('/admin');
            }

            return redirect()->intended('/account/profile');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Log the user out.
     */
    public function logout(Request $request): RedirectResponse
    {
        // Update last seen at before logout (user will be shown as offline after 5 minutes)
        if (Auth::check()) {
            Auth::user()->updateLastSeen();
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}

