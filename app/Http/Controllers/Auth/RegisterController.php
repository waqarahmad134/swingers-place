<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\RegistrationSetting;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     */
    public function showRegistrationForm(): View|RedirectResponse
    {
        // Check if registration is open
        $settings = RegistrationSetting::getSettings();
        
        if (!$settings->isRegistrationOpen()) {
            return redirect()->route('home')
                ->with('error', 'Registration is currently closed. Please contact support for more information.');
        }
        
        // Always default to normal profile type
        if (!session()->has('selected_profile_type')) {
            session(['selected_profile_type' => 'normal']);
        }
        
        return view('pages.auth.register');
    }

    /**
     * Check username availability and generate suggestions.
     */
    public function checkUsername(Request $request)
    {
        $username = $request->input('username');
        
        if (empty($username)) {
            return response()->json([
                'available' => false,
                'message' => 'Username is required',
                'suggestions' => []
            ]);
        }

        // Check if username exists
        $exists = User::where('username', $username)->exists();
        
        if (!$exists) {
            return response()->json([
                'available' => true,
                'message' => 'Username is available',
                'suggestions' => []
            ]);
        }

        // Generate suggestions if username is taken
        $suggestions = $this->generateUsernameSuggestions($username);
        
        return response()->json([
            'available' => false,
            'message' => 'Username is already taken',
            'suggestions' => $suggestions
        ]);
    }

    /**
     * Generate username suggestions based on the input.
     */
    private function generateUsernameSuggestions(string $baseUsername): array
    {
        $suggestions = [];
        $attempts = 0;
        $maxSuggestions = 3;
        
        // Try different variations
        $variations = [
            $baseUsername . rand(100, 999),
            $baseUsername . rand(1000, 9999),
            $baseUsername . '_' . rand(10, 99),
        ];
        
        foreach ($variations as $suggestion) {
            if (count($suggestions) >= $maxSuggestions) {
                break;
            }
            
            // Check if suggestion is available
            if (!User::where('username', $suggestion)->exists()) {
                $suggestions[] = $suggestion;
            } else {
                // If taken, try with different random numbers
                $attempts = 0;
                while ($attempts < 10 && count($suggestions) < $maxSuggestions) {
                    $newSuggestion = $baseUsername . rand(10000, 99999);
                    if (!User::where('username', $newSuggestion)->exists()) {
                        $suggestions[] = $newSuggestion;
                        break;
                    }
                    $attempts++;
                }
            }
        }
        
        // If we still don't have enough suggestions, try with underscores
        if (count($suggestions) < $maxSuggestions) {
            $underscoreVariations = [
                $baseUsername . '_' . rand(100, 999),
                $baseUsername . '_' . rand(1000, 9999),
            ];
            
            foreach ($underscoreVariations as $suggestion) {
                if (count($suggestions) >= $maxSuggestions) {
                    break;
                }
                
                if (!User::where('username', $suggestion)->exists()) {
                    $suggestions[] = $suggestion;
                }
            }
        }
        
        return array_slice($suggestions, 0, $maxSuggestions);
    }

    /**
     * Check email availability and validity.
     */
    public function checkEmail(Request $request)
    {
        $email = $request->input('email');
        
        if (empty($email)) {
            return response()->json([
                'valid' => false,
                'available' => false,
                'message' => 'Email is required'
            ]);
        }

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return response()->json([
                'valid' => false,
                'available' => false,
                'message' => 'Please enter a valid email address'
            ]);
        }

        // Check if email exists
        $exists = User::where('email', $email)->exists();
        
        if (!$exists) {
            return response()->json([
                'valid' => true,
                'available' => true,
                'message' => 'Email is Valid and Available'
            ]);
        }

        return response()->json([
            'valid' => true,
            'available' => false,
            'message' => 'Email is Invalid or Already Registered'
        ]);
    }

    /**
     * Send OTP to user's email.
     */
    public function sendOTP(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'username' => ['required', 'string', 'max:255'],
            'password' => ['nullable', 'string'],
            'password_confirmation' => ['nullable', 'string'],
            'terms_accepted' => ['required', 'accepted'],
        ]);

        $email = $request->input('email');
        
        // Check if email is still available
        if (User::where('email', $email)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Email is already registered'
            ], 400);
        }

        // Generate OTP (1111 for dev mode, random 4 digits for production)
        $otp = (config('app.env') === 'local' || config('app.debug')) ? '1111' : str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        
        // Store OTP and registration data in session
        session([
            'registration_otp' => $otp,
            'registration_otp_expires' => now()->addMinutes(10),
            'registration_data' => [
                'email' => $email,
                'username' => $request->input('username'),
                'password' => $request->input('password'),
                'password_confirmation' => $request->input('password_confirmation'),
                'terms_accepted' => '1', // Already validated above
            ]
        ]);

        // Send OTP email (only in production, skip in dev mode)
        if (config('app.env') !== 'local' && !config('app.debug')) {
            try {
                Mail::send('emails.otp', ['otp' => $otp], function ($message) use ($email) {
                    $message->to($email)
                        ->subject('Email Verification Code - ' . config('app.name'));
                });
            } catch (\Exception $e) {
                \Log::error('Failed to send OTP email: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send verification code. Please try again.'
                ], 500);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Verification code sent to your email'
        ]);
    }

    /**
     * Verify OTP and complete registration.
     */
    public function verifyOTP(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'string', 'size:4'],
        ]);

        $otp = $request->input('otp');
        $sessionOtp = session('registration_otp');
        $otpExpires = session('registration_otp_expires');
        $registrationData = session('registration_data');

        // Check if OTP exists and hasn't expired
        if (!$sessionOtp || !$otpExpires || now()->gt($otpExpires)) {
            return response()->json([
                'success' => false,
                'message' => 'Verification code has expired. Please request a new one.'
            ], 400);
        }

        // Verify OTP
        if ($otp !== $sessionOtp) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid verification code. Please try again.'
            ], 400);
        }

        // OTP verified - store flag in session to allow registration
        session(['otp_verified' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Email verified successfully'
        ]);
    }

    /**
     * Store category, preferences, location, and profile photo.
     */
    public function storeCategory(Request $request)
    {
        $request->validate([
            'category' => ['required', 'string', 'in:couple,single_female,single_male,transsexual'],
            'preferences' => ['nullable', 'array'],
            'preferences.*' => ['string', 'in:full_swap,soft_swap,exhibitionist,voyeur,still_exploring,hotwife,others'],
            'home_location' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'home_location_lat' => ['nullable', 'numeric'],
            'home_location_lng' => ['nullable', 'numeric'],
            'bio' => ['nullable', 'string', 'max:5000'],
            'date_of_birth' => ['nullable', 'date', 'before:today', 'before:' . now()->subYears(18)->format('Y-m-d')],
            'sexuality' => ['nullable', 'string', 'in:heterosexual,bisexual,homosexual,pansexual'],
            'date_of_birth_her' => ['nullable', 'date', 'before:today', 'before:' . now()->subYears(18)->format('Y-m-d')],
            'sexuality_her' => ['nullable', 'string', 'in:heterosexual,bisexual,homosexual,pansexual'],
            'date_of_birth_him' => ['nullable', 'date', 'before:today', 'before:' . now()->subYears(18)->format('Y-m-d')],
            'sexuality_him' => ['nullable', 'string', 'in:heterosexual,bisexual,homosexual,pansexual'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:5120'], // 5MB max
        ]);

        // Store category, preferences, and location in session
        $registrationData = session('registration_data', []);
        $registrationData['category'] = $request->input('category');
        $registrationData['preferences'] = $request->input('preferences', []);
        $registrationData['home_location'] = $request->input('home_location');
        $registrationData['country'] = $request->input('country');
        $registrationData['city'] = $request->input('city');
        $registrationData['home_location_lat'] = $request->input('home_location_lat');
        $registrationData['home_location_lng'] = $request->input('home_location_lng');
        $registrationData['bio'] = $request->input('bio');
        
        // Store basic info based on category
        $category = $request->input('category');
        if ($category === 'couple') {
            $registrationData['date_of_birth_her'] = $request->input('date_of_birth_her');
            $registrationData['sexuality_her'] = $request->input('sexuality_her');
            $registrationData['date_of_birth_him'] = $request->input('date_of_birth_him');
            $registrationData['sexuality_him'] = $request->input('sexuality_him');
        } else {
            $registrationData['date_of_birth'] = $request->input('date_of_birth');
            $registrationData['sexuality'] = $request->input('sexuality');
        }
        
        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $photo = $request->file('profile_photo');
            $path = $photo->store('profiles', 'public');
            $registrationData['profile_photo'] = $path;
        }
        
        session(['registration_data' => $registrationData]);

        return response()->json([
            'success' => true,
            'message' => 'Data saved'
        ]);
    }

    /**
     * Resend OTP.
     */
    public function resendOTP(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = $request->input('email');
        $registrationData = session('registration_data');

        // Verify email matches
        if (!$registrationData || $registrationData['email'] !== $email) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request. Please start registration again.'
            ], 400);
        }

        // Generate new OTP
        $otp = (config('app.env') === 'local' || config('app.debug')) ? '1111' : str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        
        // Update session
        session([
            'registration_otp' => $otp,
            'registration_otp_expires' => now()->addMinutes(10),
        ]);

        // Send OTP email (only in production)
        if (config('app.env') !== 'local' && !config('app.debug')) {
            try {
                Mail::send('emails.otp', ['otp' => $otp], function ($message) use ($email) {
                    $message->to($email)
                        ->subject('Email Verification Code - ' . config('app.name'));
                });
            } catch (\Exception $e) {
                \Log::error('Failed to resend OTP email: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to resend verification code. Please try again.'
                ], 500);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Verification code has been resent to your email'
        ]);
    }

    /**
     * Handle a registration request.
     */
    public function register(Request $request): RedirectResponse
    {
        // Check if OTP was verified
        if (!session('otp_verified')) {
            return redirect()->route('register')
                ->with('error', 'Please verify your email address first.');
        }

        // Check if registration is open
        $settings = RegistrationSetting::getSettings();
        
        if (!$settings->isRegistrationOpen()) {
            return redirect()->route('home')
                ->with('error', 'Registration is currently closed. Please contact support for more information.');
        }

        // Get registration data from session
        $registrationData = session('registration_data');
        
        if (!$registrationData) {
            return redirect()->route('register')
                ->with('error', 'Session expired. Please start registration again.');
        }

        // Merge session data with request for validation
        $request->merge($registrationData);
        
        // Ensure terms_accepted is set (from request or default to accepted since user already verified OTP)
        if (!$request->has('terms_accepted')) {
            $request->merge(['terms_accepted' => '1']);
        }
        
        $rules = [
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'terms_accepted' => ['required', 'accepted'],
        ];

        $validated = $request->validate($rules);

        // Get profile type from session
        $profileType = session('selected_profile_type', 'normal');

        // Auto-set name from username
        $fullName = $validated['username'] ?? 'User';

        // Generate a password if not provided (required for authentication)
        $password = isset($validated['password']) && !empty($validated['password']) 
            ? Hash::make($validated['password']) 
            : Hash::make(uniqid('user_', true));

        // Determine if user should be active based on admin approval setting
        $isActive = !$settings->requiresAdminApproval();
        
        $user = User::create([
            'name' => $fullName,
            'username' => $validated['username'] ?? null,
            'first_name' => $validated['first_name'] ?? null,
            'last_name' => $validated['last_name'] ?? null,
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'password' => $password,
            'profile_type' => $profileType,
            'is_admin' => false,
            'is_active' => $isActive, // Set based on admin approval requirement
            'email_verified_at' => $settings->requiresEmailVerification() ? null : now(), // Auto-verify if email verification not required
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Get category, preferences, location, bio, basic info, and profile photo from session data
        $category = $registrationData['category'] ?? null;
        $preferences = $registrationData['preferences'] ?? [];
        $homeLocation = $registrationData['home_location'] ?? null;
        $country = $registrationData['country'] ?? null;
        $city = $registrationData['city'] ?? null;
        $latitude = $registrationData['home_location_lat'] ?? null;
        $longitude = $registrationData['home_location_lng'] ?? null;
        $bio = $registrationData['bio'] ?? null;
        $profilePhoto = $registrationData['profile_photo'] ?? null;
        
        // Get basic info based on category
        $dateOfBirth = null;
        $sexuality = null;
        $coupleData = null;
        
        if ($category === 'couple') {
            // Store couple data in JSON (matching the structure used in AdminOnboardingController)
            $coupleData = [
                'date_of_birth_her' => $registrationData['date_of_birth_her'] ?? null,
                'sexuality_her' => $registrationData['sexuality_her'] ?? null,
                'date_of_birth_him' => $registrationData['date_of_birth_him'] ?? null,
                'sexuality_him' => $registrationData['sexuality_him'] ?? null,
            ];
        } else {
            $dateOfBirth = $registrationData['date_of_birth'] ?? null;
            $sexuality = $registrationData['sexuality'] ?? null;
        }

        // Create profile with all data collected during registration (onboarding completed)
        UserProfile::create([
            'user_id' => $user->id,
            'profile_type' => $profileType,
            'category' => $category,
            'preferences' => !empty($preferences) ? json_encode($preferences) : null,
            'home_location' => $homeLocation,
            'country' => $country,
            'city' => $city,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'date_of_birth' => $dateOfBirth,
            'sexuality' => $sexuality,
            'couple_data' => $coupleData ? json_encode($coupleData) : null,
            'bio' => $bio,
            'profile_photo' => $profilePhoto,
            'onboarding_completed' => true, // All data collected during registration
            'onboarding_step' => 9, // Mark as completed
        ]);

        // Clear session data
        session()->forget([
            'selected_profile_type',
            'registration_otp',
            'registration_otp_expires',
            'registration_data',
            'otp_verified'
        ]);

        // Redirect to profile page since all onboarding is done
        return redirect()->route('account.profile');
    }
}

