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
        
        return view('pages.profile.index', compact('user', 'profile', 'age', 'joinDate'));
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
        
        return view('pages.profile.edit', compact('user', 'profile', 'preferences', 'languages'));
    }

    /**
     * Update the user profile.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $profile = $user->profile ?? \App\Models\UserProfile::create(['user_id' => $user->id]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'gender' => ['nullable', 'in:male,female,other,prefer_not_to_say'],
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:5120'],
            'cover_photo' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:5120'],
            'category' => ['nullable', 'string'],
            'date_of_birth' => ['nullable', 'date'],
            'bio' => ['nullable', 'string'],
            'home_location' => ['nullable', 'string', 'max:255'],
            'preferences' => ['nullable', 'array'],
            'languages' => ['nullable', 'array'],
            'sexuality' => ['nullable', 'string'],
            'relationship_status' => ['nullable', 'string'],
            'profile_visible' => ['nullable', 'boolean'],
            'allow_wall_posts' => ['nullable', 'boolean'],
            'show_online_status' => ['nullable', 'boolean'],
            'show_last_active' => ['nullable', 'boolean'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'current_password' => ['nullable', 'string'],
        ]);

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }
            
            // Store new image
            $imagePath = $request->file('profile_image')->store('profiles', 'public');
            $user->profile_image = $imagePath;
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
        $profile->date_of_birth = $validated['date_of_birth'] ?? null;
        $profile->bio = $validated['bio'] ?? null;
        $profile->home_location = $validated['home_location'] ?? null;
        
        // Handle preferences array - use null coalescing to avoid undefined key error
        $preferences = $validated['preferences'] ?? [];
        $profile->preferences = !empty($preferences) ? json_encode($preferences) : null;
        
        // Handle languages array - use null coalescing to avoid undefined key error
        $languages = $validated['languages'] ?? [];
        $profile->languages = !empty($languages) ? json_encode($languages) : null;
        
        $profile->sexuality = $validated['sexuality'] ?? null;
        $profile->relationship_status = $validated['relationship_status'] ?? null;
        $profile->profile_visible = $request->has('profile_visible') ? true : false;
        $profile->allow_wall_posts = $request->has('allow_wall_posts') ? true : false;
        $profile->show_online_status = $request->has('show_online_status') ? true : false;
        $profile->show_last_active = $request->has('show_last_active') ? true : false;
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

