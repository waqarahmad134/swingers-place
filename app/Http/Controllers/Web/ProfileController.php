<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Show the user profile page.
     */
    public function index(): View
    {
        $user = Auth::user();
        $profile = $user->profile;
        
        // Calculate age from date_of_birth if available
        $age = null;
        if ($profile && $profile->date_of_birth) {
            $age = \Carbon\Carbon::parse($profile->date_of_birth)->age;
        }
        
        // Get join date
        $joinDate = $user->created_at->format('F Y');
        
        // Always own profile when viewing account profile
        $isOwnProfile = true;
        
        return view('pages.profile.index', compact('user', 'profile', 'age', 'joinDate', 'isOwnProfile'));
    }

    /**
     * Show the profile edit form.
     */
    public function edit(): View
    {
        $user = Auth::user();
        $profile = $user->profile;
        
        // Decode JSON fields safely
        $preferences = $profile && $profile->preferences 
            ? (is_array($profile->preferences) ? $profile->preferences : json_decode($profile->preferences, true) ?? [])
            : [];
        $languages = $profile && $profile->languages 
            ? (is_array($profile->languages) ? $profile->languages : json_decode($profile->languages, true) ?? [])
            : [];
        
        // Get couple data if exists
        $coupleData = $profile && $profile->couple_data 
            ? (is_array($profile->couple_data) ? $profile->couple_data : json_decode($profile->couple_data, true) ?? [])
            : [];
        
        return view('pages.profile.edit', compact('user', 'profile', 'preferences', 'languages', 'coupleData'));
    }

    /**
     * Update the user profile.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $profile = $user->profile ?? \App\Models\UserProfile::create(['user_id' => $user->id]);

        $isCouple = $request->category === 'couple';
        
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'gender' => ['nullable', 'in:male,female,other,prefer_not_to_say'],
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:5120'],
            'cover_photo' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:5120'],
            'category' => ['nullable', 'string'],
            'home_location' => ['nullable', 'string', 'max:255'],
            'preferences' => ['nullable', 'array'],
            'languages' => ['nullable', 'array'],
            'profile_visible' => ['nullable', 'boolean'],
            'allow_wall_posts' => ['nullable', 'boolean'],
            'show_online_status' => ['nullable', 'boolean'],
            'show_last_active' => ['nullable', 'boolean'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'current_password' => ['nullable', 'string'],
        ];

        // Add validation rules based on category
        if ($isCouple) {
            // Couple fields
            $rules['date_of_birth_her'] = ['nullable', 'date'];
            $rules['date_of_birth_him'] = ['nullable', 'date'];
            $rules['sexuality_her'] = ['nullable', 'string'];
            $rules['sexuality_him'] = ['nullable', 'string'];
            $rules['relationship_status_her'] = ['nullable', 'string'];
            $rules['relationship_status_him'] = ['nullable', 'string'];
            $rules['smoking_her'] = ['nullable', 'string'];
            $rules['smoking_him'] = ['nullable', 'string'];
            $rules['experience_her'] = ['nullable', 'string'];
            $rules['experience_him'] = ['nullable', 'string'];
            $rules['travel_options_her'] = ['nullable', 'string'];
            $rules['travel_options_him'] = ['nullable', 'string'];
            $rules['bio_her'] = ['nullable', 'string'];
            $rules['bio_him'] = ['nullable', 'string'];
            $rules['weight_her'] = ['nullable', 'integer'];
            $rules['weight_him'] = ['nullable', 'integer'];
            $rules['height_her'] = ['nullable', 'integer'];
            $rules['height_him'] = ['nullable', 'integer'];
            $rules['body_type_her'] = ['nullable', 'string', 'max:255'];
            $rules['body_type_him'] = ['nullable', 'string', 'max:255'];
            $rules['eye_color_her'] = ['nullable', 'string', 'max:255'];
            $rules['eye_color_him'] = ['nullable', 'string', 'max:255'];
            $rules['hair_color_her'] = ['nullable', 'string', 'max:255'];
            $rules['hair_color_him'] = ['nullable', 'string', 'max:255'];
            $rules['tattoos_her'] = ['nullable', 'string'];
            $rules['tattoos_him'] = ['nullable', 'string'];
            $rules['piercings_her'] = ['nullable', 'string', 'max:255'];
            $rules['piercings_him'] = ['nullable', 'string', 'max:255'];
            $rules['boob_size_her'] = ['nullable', 'string', 'max:255'];
            $rules['dick_size_him'] = ['nullable', 'string', 'max:255'];
        } else {
            // Single fields
            $rules['date_of_birth'] = ['nullable', 'date'];
            $rules['bio'] = ['nullable', 'string'];
            $rules['sexuality'] = ['nullable', 'string'];
            $rules['relationship_status'] = ['nullable', 'string'];
        }

        $validated = $request->validate($rules);

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            // Delete old images if exist
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }
            if ($profile->profile_photo && Storage::disk('public')->exists($profile->profile_photo)) {
                Storage::disk('public')->delete($profile->profile_photo);
            }
            
            // Store new image
            $imagePath = $request->file('profile_image')->store('profiles', 'public');
            $user->profile_image = $imagePath;
            $profile->profile_photo = $imagePath; // Also save to profile for consistency
        }

        // Handle cover photo upload
        if ($request->hasFile('cover_photo')) {
            // Delete old cover photo if exists
            if ($profile->cover_photo && Storage::disk('public')->exists($profile->cover_photo)) {
                Storage::disk('public')->delete($profile->cover_photo);
            }
            
            // Store new cover photo
            $coverPath = $request->file('cover_photo')->store('cover_photos', 'public');
            $profile->cover_photo = $coverPath;
        }

        // Update user data
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->gender = $validated['gender'] ?? null;
        $user->save();

        // Update profile data
        $profile->category = $validated['category'] ?? null;
        $profile->home_location = $validated['home_location'] ?? null;
        
        // Handle preferences array - use null coalescing to avoid undefined key error
        $preferences = $validated['preferences'] ?? [];
        $profile->preferences = !empty($preferences) ? json_encode($preferences) : null;
        
        // Handle languages array - use null coalescing to avoid undefined key error
        $languages = $validated['languages'] ?? [];
        $profile->languages = !empty($languages) ? json_encode($languages) : null;
        
        $profile->profile_visible = $request->has('profile_visible') ? true : false;
        $profile->allow_wall_posts = $request->has('allow_wall_posts') ? true : false;
        $profile->show_online_status = $request->has('show_online_status') ? true : false;
        $profile->show_last_active = $request->has('show_last_active') ? true : false;

        // Handle couple vs single data
        if ($isCouple) {
            // Store couple data in JSON format
            $coupleData = [
                'date_of_birth_her' => $validated['date_of_birth_her'] ?? null,
                'date_of_birth_him' => $validated['date_of_birth_him'] ?? null,
                'sexuality_her' => $validated['sexuality_her'] ?? null,
                'sexuality_him' => $validated['sexuality_him'] ?? null,
                'relationship_status_her' => $validated['relationship_status_her'] ?? null,
                'relationship_status_him' => $validated['relationship_status_him'] ?? null,
                'smoking_her' => $validated['smoking_her'] ?? null,
                'smoking_him' => $validated['smoking_him'] ?? null,
                'experience_her' => $validated['experience_her'] ?? null,
                'experience_him' => $validated['experience_him'] ?? null,
                'travel_options_her' => $validated['travel_options_her'] ?? null,
                'travel_options_him' => $validated['travel_options_him'] ?? null,
                'bio_her' => $validated['bio_her'] ?? null,
                'bio_him' => $validated['bio_him'] ?? null,
                'weight_her' => $validated['weight_her'] ?? null,
                'weight_him' => $validated['weight_him'] ?? null,
                'height_her' => $validated['height_her'] ?? null,
                'height_him' => $validated['height_him'] ?? null,
                'body_type_her' => $validated['body_type_her'] ?? null,
                'body_type_him' => $validated['body_type_him'] ?? null,
                'eye_color_her' => $validated['eye_color_her'] ?? null,
                'eye_color_him' => $validated['eye_color_him'] ?? null,
                'hair_color_her' => $validated['hair_color_her'] ?? null,
                'hair_color_him' => $validated['hair_color_him'] ?? null,
                'tattoos_her' => $validated['tattoos_her'] ?? null,
                'tattoos_him' => $validated['tattoos_him'] ?? null,
                'piercings_her' => $validated['piercings_her'] ?? null,
                'piercings_him' => $validated['piercings_him'] ?? null,
                'boob_size_her' => $validated['boob_size_her'] ?? null,
                'dick_size_him' => $validated['dick_size_him'] ?? null,
            ];
            // Store couple data in JSON format
            $profile->couple_data = $coupleData;
            // Also set main bio to combined or first person's bio
            $profile->bio = $validated['bio_her'] ?? $validated['bio_him'] ?? null;
        } else {
            // Single mode - use regular fields
            $profile->date_of_birth = $validated['date_of_birth'] ?? null;
            $profile->bio = $validated['bio'] ?? null;
            $profile->sexuality = $validated['sexuality'] ?? null;
            $profile->relationship_status = $validated['relationship_status'] ?? null;
        }
        
        $profile->save();

        // Update password if provided
        if (!empty($validated['password']) && !empty($validated['current_password'])) {
            if (Hash::check($validated['current_password'], $user->password)) {
                $user->password = bcrypt($validated['password']);
                $user->save();
            } else {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }
        }

        return redirect()->route('account.profile')
            ->with('success', 'Profile updated successfully!');
    }
}

