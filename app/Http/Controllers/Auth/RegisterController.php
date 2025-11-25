<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     */
    public function showRegistrationForm(): View|RedirectResponse
    {
        // If no profile type selected, redirect to profile type selection
        if (!session()->has('selected_profile_type')) {
            return redirect()->route('onboarding.profile-type');
        }
        
        return view('pages.auth.register');
    }

    /**
     * Handle a registration request.
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Get profile type from session
        $profileType = session('selected_profile_type', 'normal');

        $user = User::create([
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'profile_type' => $profileType,
            'is_admin' => false,
            'is_active' => true,
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Create basic profile (not completed) to start onboarding
        UserProfile::create([
            'user_id' => $user->id,
            'profile_type' => $profileType,
            'onboarding_completed' => false,
            'onboarding_step' => 0, // Will start at step 1
        ]);

        // Clear session
        session()->forget('selected_profile_type');

        // Redirect to step 1 (Choose Category)
        return redirect()->route('onboarding.step1');
    }
}

