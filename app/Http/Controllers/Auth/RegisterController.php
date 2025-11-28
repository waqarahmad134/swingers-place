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
        $rules = [
            'name' => ['nullable', 'string', 'max:255'],
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ];

        // Only validate username uniqueness if username is provided
        if ($request->has('username') && !empty($request->username)) {
            $rules['username'] = ['nullable', 'string', 'max:255', 'unique:users,username'];
        } else {
            $rules['username'] = ['nullable', 'string', 'max:255'];
        }

        // Only validate email uniqueness if email is provided
        if ($request->has('email') && !empty($request->email)) {
            $rules['email'] = ['nullable', 'string', 'email', 'max:255', 'unique:users,email'];
        } else {
            $rules['email'] = ['nullable', 'string', 'email', 'max:255'];
        }

        $validated = $request->validate($rules);

        // Get profile type from session
        $profileType = session('selected_profile_type', 'normal');

        // Handle name - use 'name' field if provided, otherwise construct from first_name/last_name, or use username
        if (!empty($validated['name'])) {
            $fullName = $validated['name'];
        } elseif (!empty($validated['first_name']) || !empty($validated['last_name'])) {
            $fullName = trim(($validated['first_name'] ?? '') . ' ' . ($validated['last_name'] ?? ''));
        } elseif (!empty($validated['username'])) {
            $fullName = $validated['username'];
        } else {
            $fullName = 'User';
        }

        // Generate a password if not provided (required for authentication)
        $password = isset($validated['password']) && !empty($validated['password']) 
            ? Hash::make($validated['password']) 
            : Hash::make(uniqid('user_', true));

        $user = User::create([
            'name' => trim($fullName) ?: 'User',
            'username' => $validated['username'] ?? null,
            'first_name' => $validated['first_name'] ?? null,
            'last_name' => $validated['last_name'] ?? null,
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'password' => $password,
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

