<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminOnboardingController extends Controller
{
    // Helper method to get the user being created
    private function getCreatingUser()
    {
        $userId = session('admin_creating_user_id');
        if (!$userId) {
            abort(404, 'No user being created');
        }
        return User::findOrFail($userId);
    }

    // Helper method to get or create profile
    private function getOrCreateProfile()
    {
        $user = $this->getCreatingUser();
        return UserProfile::firstOrCreate(
            ['user_id' => $user->id],
            ['onboarding_step' => 0]
        );
    }

    // Step 0: Profile Type Selection
    public function profileType()
    {
        $user = $this->getCreatingUser();
        $profile = $this->getOrCreateProfile();
        return view('admin.users.onboarding.profile-type', compact('user', 'profile'));
    }

    public function storeProfileType(Request $request)
    {
        $request->validate([
            'profile_type' => 'required|in:normal,business',
        ]);

        $user = $this->getCreatingUser();
        $user->update(['profile_type' => $request->profile_type]);
        
        $profile = $this->getOrCreateProfile();
        $profile->update([
            'profile_type' => $request->profile_type,
            'onboarding_step' => 1,
        ]);

        return redirect()->route('admin.users.onboarding.step1');
    }

    // Step 1: Choose Category
    public function step1()
    {
        $user = $this->getCreatingUser();
        $profile = $this->getOrCreateProfile();
        return view('admin.users.onboarding.step1', compact('user', 'profile'));
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

        return response()->json(['success' => true, 'next' => route('admin.users.onboarding.step2')]);
    }

    // Step 2: Preferences
    public function step2()
    {
        $user = $this->getCreatingUser();
        $profile = $this->getOrCreateProfile();
        return view('admin.users.onboarding.step2', compact('user', 'profile'));
    }

    public function storeStep2(Request $request)
    {
        $this->getOrCreateProfile()->update([
            'preferences' => json_encode($request->preferences ?? []),
            'onboarding_step' => 3,
        ]);

        return response()->json(['success' => true, 'next' => route('admin.users.onboarding.step3')]);
    }

    // Step 3: Location
    public function step3()
    {
        $user = $this->getCreatingUser();
        $profile = $this->getOrCreateProfile();
        return view('admin.users.onboarding.step3', compact('user', 'profile'));
    }

    public function storeStep3(Request $request)
    {
        $this->getOrCreateProfile()->update([
            'home_location' => $request->home_location,
            'country' => $request->country,
            'city' => $request->city,
            'latitude' => $request->home_location_lat,
            'longitude' => $request->home_location_lng,
            'travel_location' => $request->travel_location,
            'onboarding_step' => 4,
        ]);

        return response()->json(['success' => true, 'next' => route('admin.users.onboarding.step4')]);
    }

    // Step 4: Languages
    public function step4()
    {
        $user = $this->getCreatingUser();
        $profile = $this->getOrCreateProfile();
        return view('admin.users.onboarding.step4', compact('user', 'profile'));
    }

    public function storeStep4(Request $request)
    {
        $this->getOrCreateProfile()->update([
            'languages' => json_encode($request->languages ?? []),
            'onboarding_step' => 5,
        ]);

        return response()->json(['success' => true, 'next' => route('admin.users.onboarding.step5')]);
    }

    // Step 5: Basic Information
    public function step5()
    {
        $user = $this->getCreatingUser();
        $profile = $this->getOrCreateProfile();
        return view('admin.users.onboarding.step5', compact('user', 'profile'));
    }

    public function storeStep5(Request $request)
    {
        $profile = $this->getOrCreateProfile();
        
        // Check if couple category
        if ($profile->category === 'couple') {
            // Handle couple data
            $coupleData = [
                'date_of_birth_her' => $request->date_of_birth_her ?? null,
                'sexuality_her' => $request->sexuality_her ?? null,
                'relationship_status_her' => $request->relationship_status_her ?? null,
                'experience_her' => $request->experience_her ?? null,
                'smoking_her' => $request->smoking_her ?? null,
                'travel_options_her' => $request->travel_options_her ?? null,
                'date_of_birth_him' => $request->date_of_birth_him ?? null,
                'sexuality_him' => $request->sexuality_him ?? null,
                'relationship_status_him' => $request->relationship_status_him ?? null,
                'experience_him' => $request->experience_him ?? null,
                'smoking_him' => $request->smoking_him ?? null,
                'travel_options_him' => $request->travel_options_him ?? null,
            ];
            $profile->update([
                'couple_data' => $coupleData,
                'onboarding_step' => 6,
            ]);
        } else {
            // Handle single mode
            $profile->update([
                'date_of_birth' => $request->date_of_birth,
                'sexuality' => $request->sexuality,
                'relationship_status' => $request->relationship_status,
                'experience' => $request->experience,
                'smoking' => $request->smoking,
                'travel_options' => $request->travel_options,
                'onboarding_step' => 6,
            ]);
        }

        return response()->json(['success' => true, 'next' => route('admin.users.onboarding.step6')]);
    }

    // Step 6: Personal Details
    public function step6()
    {
        $user = $this->getCreatingUser();
        $profile = $this->getOrCreateProfile();
        return view('admin.users.onboarding.step6', compact('user', 'profile'));
    }

    public function storeStep6(Request $request)
    {
        $profile = $this->getOrCreateProfile();
        
        // Check if couple category
        if ($profile->category === 'couple') {
            // Update couple data with personal details
            $coupleData = $profile->couple_data ?? [];
            $coupleData = array_merge($coupleData, [
                'weight_her' => $request->weight_her ?? null,
                'height_her' => $request->height_her ?? null,
                'body_type_her' => $request->body_type_her ?? null,
                'eye_color_her' => $request->eye_color_her ?? null,
                'hair_color_her' => $request->hair_color_her ?? null,
                'tattoos_her' => $request->tattoos_her ?? null,
                'piercings_her' => $request->piercings_her ?? null,
                'race_her' => $request->race_her ?? null,
                'weight_him' => $request->weight_him ?? null,
                'height_him' => $request->height_him ?? null,
                'body_type_him' => $request->body_type_him ?? null,
                'eye_color_him' => $request->eye_color_him ?? null,
                'hair_color_him' => $request->hair_color_him ?? null,
                'tattoos_him' => $request->tattoos_him ?? null,
                'piercings_him' => $request->piercings_him ?? null,
                'dick_size_him' => $request->dick_size_him ?? null,
            ]);
            $profile->update([
                'couple_data' => $coupleData,
                'onboarding_step' => 7,
            ]);
        } else {
            // Handle single mode
            $profile->update([
                'weight' => $request->weight,
                'height' => $request->height,
                'body_type' => $request->body_type,
                'eye_color' => $request->eye_color,
                'hair_color' => $request->hair_color,
                'tattoos' => $request->tattoos,
                'piercings' => $request->piercings,
                'boob_size' => $request->boob_size ?? null,
                'dick_size' => $request->dick_size ?? null,
                'onboarding_step' => 7,
            ]);
        }

        return response()->json(['success' => true, 'next' => route('admin.users.onboarding.step7')]);
    }

    // Step 7: Add Photos
    public function step7()
    {
        $user = $this->getCreatingUser();
        $profile = $this->getOrCreateProfile();
        return view('admin.users.onboarding.step7', compact('user', 'profile'));
    }

    public function storeStep7(Request $request)
    {
        $profile = $this->getOrCreateProfile();
        $user = $this->getCreatingUser();
        
        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profiles', 'public');
            $profile->update(['profile_photo' => $path]);
            $user->update(['profile_image' => $path]);
        }


        if ($request->hasFile('album_photos')) {
            $albumPhotos = [];
            foreach ($request->file('album_photos') as $photo) {
                $albumPhotos[] = $photo->store('albums', 'public');
            }
            $profile->update(['album_photos' => json_encode($albumPhotos)]);
        }

        $profile->update(['onboarding_step' => 8]);

        return response()->json(['success' => true, 'next' => route('admin.users.onboarding.step8')]);
    }

    // Step 8: Tell Your Story
    public function step8()
    {
        $user = $this->getCreatingUser();
        $profile = $this->getOrCreateProfile();
        return view('admin.users.onboarding.step8', compact('user', 'profile'));
    }

    public function storeStep8(Request $request)
    {
        $profile = $this->getOrCreateProfile();
        
        // Check if couple category
        if ($profile->category === 'couple') {
            // Update couple data with story
            $coupleData = $profile->couple_data ?? [];
            $coupleData = array_merge($coupleData, [
                'bio_her' => $request->bio_her ?? null,
                'looking_for_her' => $request->looking_for_her ?? null,
                'additional_notes_her' => $request->additional_notes_her ?? null,
                'bio_him' => $request->bio_him ?? null,
                'looking_for_him' => $request->looking_for_him ?? null,
                'additional_notes_him' => $request->additional_notes_him ?? null,
            ]);
            $profile->update([
                'couple_data' => $coupleData,
                'onboarding_step' => 9,
            ]);
        } else {
            // Handle single mode
            $profile->update([
                'bio' => $request->bio,
                'looking_for' => $request->looking_for,
                'additional_notes' => $request->additional_notes,
                'onboarding_step' => 9,
            ]);
        }

        return response()->json(['success' => true, 'next' => route('admin.users.onboarding.step9')]);
    }

    // Step 9: Privacy Settings
    public function step9()
    {
        $user = $this->getCreatingUser();
        $profile = $this->getOrCreateProfile();
        return view('admin.users.onboarding.step9', compact('user', 'profile'));
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

        // Clear the session
        session()->forget('admin_creating_user_id');

        return response()->json(['success' => true, 'next' => route('admin.users.onboarding.complete')]);
    }

    // Complete
    public function complete()
    {
        return view('admin.users.onboarding.complete');
    }

    // Skip step
    public function skip($step)
    {
        $profile = $this->getOrCreateProfile();
        $profile->update(['onboarding_step' => $step + 1]);

        return response()->json(['success' => true, 'next' => route('admin.users.onboarding.step' . ($step + 1))]);
    }
}

