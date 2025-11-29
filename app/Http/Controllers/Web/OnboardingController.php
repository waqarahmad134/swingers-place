<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\RegistrationSetting;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnboardingController extends Controller
{
    // Step 0: Profile Type Selection (BEFORE registration - no auth required)
    public function profileType()
    {
        // Check if registration is open
        $settings = RegistrationSetting::getSettings();
        
        if (!$settings->isRegistrationOpen()) {
            return redirect()->route('home')
                ->with('error', 'Registration is currently closed. Please contact support for more information.');
        }
        
        // If already logged in and has profile, redirect to home
        if (Auth::check() && Auth::user()->profile && Auth::user()->profile->onboarding_completed) {
            return redirect()->route('home');
        }
        
        return view('pages.onboarding.profile-type');
    }

    public function storeProfileType(Request $request)
    {
        $request->validate([
            'profile_type' => 'required|in:normal,business',
        ]);

        // Store in session for registration
        session(['selected_profile_type' => $request->profile_type]);

        // If user is already logged in, update their profile
        if (Auth::check()) {
            Auth::user()->update([
                'profile_type' => $request->profile_type,
            ]);
            
            $profile = $this->getOrCreateProfile();
            $profile->update([
                'profile_type' => $request->profile_type,
                'onboarding_step' => 1,
            ]);
            
            return redirect()->route('onboarding.step1');
        }

        // If not logged in, redirect to registration
        return redirect()->route('register');
    }

    // Step 1: Choose Category
    public function step1()
    {
        $profile = $this->getOrCreateProfile();
        return view('pages.onboarding.step1', compact('profile'));
    }

    public function storeStep1(Request $request)
    {
        $request->validate([
            'category' => 'required|string',
        ]);

        $this->getOrCreateProfile()->update([
            'category' => $request->category,
            'onboarding_step' => 2,
        ]);

        return response()->json(['success' => true, 'next' => route('onboarding.step2')]);
    }

    // Step 2: Preferences
    public function step2()
    {
        $profile = $this->getOrCreateProfile();
        return view('pages.onboarding.step2', compact('profile'));
    }

    public function storeStep2(Request $request)
    {
        $this->getOrCreateProfile()->update([
            'preferences' => json_encode($request->preferences ?? []),
            'onboarding_step' => 3,
        ]);

        return response()->json(['success' => true, 'next' => route('onboarding.step3')]);
    }

    // Step 3: Location
    public function step3()
    {
        $profile = $this->getOrCreateProfile();
        return view('pages.onboarding.step3', compact('profile'));
    }

    public function storeStep3(Request $request)
    {
        $this->getOrCreateProfile()->update([
            'home_location' => $request->home_location,
            'travel_location' => $request->travel_location,
            'onboarding_step' => 4,
        ]);

        return response()->json(['success' => true, 'next' => route('onboarding.step4')]);
    }

    // Step 4: Languages
    public function step4()
    {
        $profile = $this->getOrCreateProfile();
        return view('pages.onboarding.step4', compact('profile'));
    }

    public function storeStep4(Request $request)
    {
        $this->getOrCreateProfile()->update([
            'languages' => json_encode($request->languages ?? []),
            'onboarding_step' => 5,
        ]);

        return response()->json(['success' => true, 'next' => route('onboarding.step5')]);
    }

    // Step 5: Basic Information
    public function step5()
    {
        $profile = $this->getOrCreateProfile();
        return view('pages.onboarding.step5', compact('profile'));
    }

    public function storeStep5(Request $request)
    {
        $this->getOrCreateProfile()->update([
            'date_of_birth' => $request->date_of_birth,
            'sexuality' => $request->sexuality,
            'relationship_status' => $request->relationship_status,
            'experience' => $request->experience,
            'smoking' => $request->smoking,
            'travel_options' => $request->travel_options,
            'onboarding_step' => 6,
        ]);

        return response()->json(['success' => true, 'next' => route('onboarding.step6')]);
    }

    // Step 6: Personal Details
    public function step6()
    {
        $profile = $this->getOrCreateProfile();
        return view('pages.onboarding.step6', compact('profile'));
    }

    public function storeStep6(Request $request)
    {
        $this->getOrCreateProfile()->update([
            'weight' => $request->weight,
            'height' => $request->height,
            'body_type' => $request->body_type,
            'eye_color' => $request->eye_color,
            'hair_color' => $request->hair_color,
            'tattoos' => $request->tattoos,
            'piercings' => $request->piercings,
            'race' => $request->race,
            'onboarding_step' => 7,
        ]);

        return response()->json(['success' => true, 'next' => route('onboarding.step7')]);
    }

    // Step 7: Add Photos
    public function step7()
    {
        $profile = $this->getOrCreateProfile();
        return view('pages.onboarding.step7', compact('profile'));
    }

    public function storeStep7(Request $request)
    {
        $profile = $this->getOrCreateProfile();
        
        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profiles', 'public');
            $profile->update(['profile_photo' => $path]);
        }

        if ($request->hasFile('cover_photo')) {
            $path = $request->file('cover_photo')->store('covers', 'public');
            $profile->update(['cover_photo' => $path]);
        }

        if ($request->hasFile('album_photos')) {
            $albumPhotos = [];
            foreach ($request->file('album_photos') as $photo) {
                $albumPhotos[] = $photo->store('albums', 'public');
            }
            $profile->update(['album_photos' => json_encode($albumPhotos)]);
        }

        $profile->update(['onboarding_step' => 8]);

        return response()->json(['success' => true, 'next' => route('onboarding.step8')]);
    }

    // Step 8: Tell Your Story
    public function step8()
    {
        $profile = $this->getOrCreateProfile();
        return view('pages.onboarding.step8', compact('profile'));
    }

    public function storeStep8(Request $request)
    {
        $this->getOrCreateProfile()->update([
            'bio' => $request->bio,
            'looking_for' => $request->looking_for,
            'additional_notes' => $request->additional_notes,
            'onboarding_step' => 9,
        ]);

        return response()->json(['success' => true, 'next' => route('onboarding.step9')]);
    }

    // Step 9: Privacy Settings
    public function step9()
    {
        $profile = $this->getOrCreateProfile();
        return view('pages.onboarding.step9', compact('profile'));
    }

    public function storeStep9(Request $request)
    {
        $this->getOrCreateProfile()->update([
            'profile_visible' => $request->has('profile_visible'),
            'allow_wall_posts' => $request->has('allow_wall_posts'),
            'show_online_status' => $request->has('show_online_status'),
            'country_visibility' => $request->has('country_visibility'),
            'photo_filtering' => $request->has('photo_filtering'),
            'onboarding_completed' => true,
            'onboarding_step' => 10,
        ]);

        return response()->json(['success' => true, 'next' => route('onboarding.complete')]);
    }

    // Complete
    public function complete()
    {
        // Check if user has a profile
        $profile = Auth::user()->profile;
        
        // If no profile or not completed, redirect to profile type selection
        if (!$profile || !$profile->onboarding_completed) {
            return redirect()->route('onboarding.profile-type');
        }

        return view('pages.onboarding.complete');
    }

    // Skip step
    public function skip($step)
    {
        $profile = $this->getOrCreateProfile();
        $profile->update(['onboarding_step' => $step + 1]);

        return response()->json(['success' => true, 'next' => route('onboarding.step' . ($step + 1))]);
    }

    // Helper method
    private function getOrCreateProfile()
    {
        $user = Auth::user();
        
        return UserProfile::firstOrCreate(
            ['user_id' => $user->id],
            ['onboarding_step' => 0]
        );
    }
}
