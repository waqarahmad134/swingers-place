<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');
        $username = $credentials['username'];
        $password = $credentials['password'];

        // Determine if input is email or username
        $isEmail = filter_var($username, FILTER_VALIDATE_EMAIL);

        // Try to find user by username or email
        $user = null;
        if ($isEmail) {
            $user = User::where('email', $username)->first();
        } else {
            $user = User::where('username', $username)->first();
        }

        // If user not found with first method, try the other
        if (!$user && $isEmail) {
            $user = User::where('username', $username)->first();
        } elseif (!$user && !$isEmail) {
            $user = User::where('email', $username)->first();
        }

        // Attempt authentication
        if ($user && Hash::check($password, $user->password)) {
            Auth::login($user, $remember);
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
            'username' => 'The provided credentials do not match our records.',
        ])->onlyInput('username');
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

