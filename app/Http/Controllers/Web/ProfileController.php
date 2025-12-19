<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
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
        
        // Check if it's a couple profile
        $isCouple = $profile && $profile->category === 'couple';
        $coupleData = $profile && $profile->couple_data 
            ? (is_array($profile->couple_data) ? $profile->couple_data : json_decode($profile->couple_data, true) ?? [])
            : [];
        
        // Calculate age(s) based on profile type
        $age = null;
        $ageHer = null;
        $ageHim = null;
        
        if ($isCouple && !empty($coupleData)) {
            // Couple profile - calculate both ages
            if (!empty($coupleData['date_of_birth_her'])) {
                $ageHer = \Carbon\Carbon::parse($coupleData['date_of_birth_her'])->age;
            }
            if (!empty($coupleData['date_of_birth_him'])) {
                $ageHim = \Carbon\Carbon::parse($coupleData['date_of_birth_him'])->age;
            }
        } else {
            // Single profile - calculate single age
            if ($profile && $profile->date_of_birth) {
                $age = \Carbon\Carbon::parse($profile->date_of_birth)->age;
            }
        }
        
        // Get join date
        $joinDate = $user->created_at->format('F Y');
        
        // Always own profile when viewing account profile
        $isOwnProfile = true;
        
        // Decode JSON fields safely
        $preferences = $profile && $profile->preferences 
            ? (is_array($profile->preferences) ? $profile->preferences : json_decode($profile->preferences, true) ?? [])
            : [];
        $languages = $profile && $profile->languages 
            ? (is_array($profile->languages) ? $profile->languages : json_decode($profile->languages, true) ?? [])
            : [];
        
        // Get match profiles (12 profiles that match this user)
        $matchedProfiles = $this->getMatchedProfiles($user, $profile, 12);
        
        // Get current user's likes/dislikes for matched profiles
        $userLikes = \App\Models\UserLike::where('user_id', $user->id)
            ->whereIn('liked_user_id', $matchedProfiles->pluck('id'))
            ->get()
            ->keyBy('liked_user_id');
        
        // Get like count and status for the profile being viewed (own profile, so not liked by self)
        $likesCount = $user->likesReceived()->where('type', 'like')->count();
        $isLikedByCurrentUser = false; // Can't like own profile
        
        return view('pages.profile.index', compact('user', 'profile', 'age', 'ageHer', 'ageHim', 'isCouple', 'coupleData', 'joinDate', 'isOwnProfile', 'preferences', 'languages', 'matchedProfiles', 'userLikes', 'likesCount', 'isLikedByCurrentUser'));
    }
    
    /**
     * Get matched profiles based on user preferences and profile data.
     */
    private function getMatchedProfiles($currentUser, $currentProfile, $limit = 12)
    {
        if (!$currentProfile) {
            return collect([]);
        }
        
        $query = User::with('profile')
            ->where('profile_type', 'normal')
            ->where('is_active', true)
            ->where('id', '!=', $currentUser->id)
            ->whereHas('profile', function($q) {
                $q->where('profile_visible', true)
                  ->where('onboarding_completed', true);
            });
        
        // Match by category (same category gets priority)
        if ($currentProfile->category) {
            $query->whereHas('profile', function($q) use ($currentProfile) {
                $q->where('category', $currentProfile->category);
            });
        }
        
        // Match by location (same country or city)
        if ($currentProfile->country) {
            $query->whereHas('profile', function($q) use ($currentProfile) {
                $q->where('country', $currentProfile->country);
            });
        }
        
        // Get initial matches
        $matches = $query->take($limit)->get();
        
        // If we don't have enough matches, get more without location filter
        if ($matches->count() < $limit) {
            $remaining = $limit - $matches->count();
            $matchedIds = $matches->pluck('id')->toArray();
            
            $additionalQuery = User::with('profile')
                ->where('profile_type', 'normal')
                ->where('is_active', true)
                ->where('id', '!=', $currentUser->id)
                ->whereNotIn('id', $matchedIds)
                ->whereHas('profile', function($q) use ($currentProfile) {
                    $q->where('profile_visible', true)
                      ->where('onboarding_completed', true);
                    
                    if ($currentProfile->category) {
                        $q->where('category', $currentProfile->category);
                    }
                })
                ->take($remaining)
                ->get();
            
            $matches = $matches->merge($additionalQuery);
        }
        
        // If still not enough, get any active users
        if ($matches->count() < $limit) {
            $remaining = $limit - $matches->count();
            $matchedIds = $matches->pluck('id')->toArray();
            
            $anyUsers = User::with('profile')
                ->where('profile_type', 'normal')
                ->where('is_active', true)
                ->where('id', '!=', $currentUser->id)
                ->whereNotIn('id', $matchedIds)
                ->whereHas('profile', function($q) {
                    $q->where('profile_visible', true)
                      ->where('onboarding_completed', true);
                })
                ->take($remaining)
                ->get();
            
            $matches = $matches->merge($anyUsers);
        }
        
        return $matches->take($limit);
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

        // Determine if this is a couple profile - check request first, then existing profile
        $isCouple = $request->has('category') ? $request->category === 'couple' : ($profile->category === 'couple');
        
        // Build validation rules only for fields that are present in the request (PATCH-like behavior)
        $rules = [];
        
        // Always validate name and email if they're present (location form sends them as hidden fields)
        if ($request->has('name') && $request->filled('name')) {
            $rules['name'] = ['required', 'string', 'max:255'];
        }
        if ($request->has('email') && $request->filled('email')) {
            $rules['email'] = ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id];
        }
        
        // Validate only fields that are present in the request
        if ($request->has('gender')) {
            $rules['gender'] = ['nullable', 'in:male,female,other,prefer_not_to_say'];
        }
        if ($request->hasFile('profile_image')) {
            $rules['profile_image'] = ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:5120'];
        }
        if ($request->hasFile('profile_photo')) {
            $rules['profile_photo'] = ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:5120'];
        }
        if ($request->has('category')) {
            $rules['category'] = ['nullable', 'string'];
        }
        if ($request->has('home_location')) {
            $rules['home_location'] = ['nullable', 'string', 'max:255'];
        }
        if ($request->has('country')) {
            $rules['country'] = ['nullable', 'string', 'max:255'];
        }
        if ($request->has('city')) {
            $rules['city'] = ['nullable', 'string', 'max:255'];
        }
        if ($request->has('latitude')) {
            $rules['latitude'] = ['nullable', 'numeric'];
        }
        if ($request->has('longitude')) {
            $rules['longitude'] = ['nullable', 'numeric'];
        }
        if ($request->has('preferences')) {
            $rules['preferences'] = ['nullable', 'array'];
        }
        if ($request->has('languages')) {
            $rules['languages'] = ['nullable', 'array'];
        }
        if ($request->has('profile_visible')) {
            $rules['profile_visible'] = ['nullable', 'boolean'];
        }
        if ($request->has('allow_wall_posts')) {
            $rules['allow_wall_posts'] = ['nullable', 'boolean'];
        }
        if ($request->has('show_online_status')) {
            $rules['show_online_status'] = ['nullable', 'boolean'];
        }
        if ($request->has('show_last_active')) {
            $rules['show_last_active'] = ['nullable', 'boolean'];
        }
        if ($request->has('password')) {
            $rules['password'] = ['nullable', 'string', 'min:8', 'confirmed'];
            $rules['current_password'] = ['nullable', 'required_with:password', 'string'];
        }
        
        // Photo upload validation
        if ($request->hasFile('non_adult_photos')) {
            $rules['non_adult_photos.*'] = ['image', 'mimes:jpeg,jpg,png,gif,webp', 'max:5120'];
        }
        if ($request->hasFile('adult_photos')) {
            $rules['adult_photos.*'] = ['image', 'mimes:jpeg,jpg,png,gif,webp', 'max:5120'];
        }
        if ($request->hasFile('album_photos')) {
            $rules['album_photos.*'] = ['image', 'mimes:jpeg,jpg,png,gif,webp', 'max:5120'];
        }
        // Video upload validation
        if ($request->hasFile('videos')) {
            $rules['videos.*'] = ['file', 'mimes:mp4,avi,mov,wmv,flv,webm', 'max:102400']; // 100MB max per video
        }

        // Add validation rules for couple fields only if they're present in request
        if ($isCouple) {
            $coupleFields = [
                'date_of_birth_her', 'date_of_birth_him', 'sexuality_her', 'sexuality_him',
                'relationship_status_her', 'relationship_status_him', 'smoking_her', 'smoking_him',
                'experience_her', 'experience_him', 'travel_options_her', 'travel_options_him',
                'bio_her', 'bio_him', 'weight_her', 'weight_him', 'height_her', 'height_him',
                'body_type_her', 'body_type_him', 'eye_color_her', 'eye_color_him',
                'hair_color_her', 'hair_color_him', 'tattoos_her', 'tattoos_him',
                'piercings_her', 'piercings_him', 'boob_size_her', 'dick_size_him'
            ];
            
            foreach ($coupleFields as $field) {
                if ($request->has($field)) {
                    if (in_array($field, ['date_of_birth_her', 'date_of_birth_him'])) {
                        $rules[$field] = ['nullable', 'date'];
                    } elseif (in_array($field, ['weight_her', 'weight_him', 'height_her', 'height_him'])) {
                        $rules[$field] = ['nullable', 'integer'];
                    } elseif (in_array($field, ['eye_color_her', 'eye_color_him'])) {
                        $rules[$field] = ['nullable', 'string', 'in:Brown,Blue,Green,Gray,Hazel,Amber,Black,Other'];
                    } elseif (in_array($field, ['hair_color_her', 'hair_color_him'])) {
                        $rules[$field] = ['nullable', 'string', 'in:Black,Brown,Blonde,Red,Gray,White,Auburn,Chestnut,Other'];
                    } elseif (in_array($field, ['piercings_her', 'piercings_him'])) {
                        $rules[$field] = ['nullable', 'string', 'in:yes,no,prefer_not_to_say'];
                    } elseif (in_array($field, ['body_type_her', 'body_type_him', 'boob_size_her', 'dick_size_him'])) {
                        $rules[$field] = ['nullable', 'string', 'max:255'];
                    } else {
                        $rules[$field] = ['nullable', 'string'];
                    }
                }
            }
        } else {
            // Single fields - only validate if present in request
            $singleFields = [
                'date_of_birth' => ['nullable', 'date'],
                'bio' => ['nullable', 'string'],
                'sexuality' => ['nullable', 'string', 'in:straight,gay,bisexual,lesbian,prefer_not_to_say'],
                'relationship_status' => ['nullable', 'string', 'in:single,in_relationship,married,open,prefer_not_to_say'],
                'relationship_orientation' => ['nullable', 'string', 'in:monogamous,polyamorous,swinger,open,prefer_not_to_say'],
                'smoking' => ['nullable', 'string', 'in:no,yes,prefer_not_to_say'],
                'piercings' => ['nullable', 'string', 'in:no,yes,prefer_not_to_say'],
                'tattoos' => ['nullable', 'string', 'in:no,yes,prefer_not_to_say'],
                'looks_important' => ['nullable', 'string', 'in:no,yes,prefer_not_to_say'],
                'intelligence_important' => ['nullable', 'string', 'in:no,yes,prefer_not_to_say'],
            ];
            
            foreach ($singleFields as $field => $rule) {
                if ($request->has($field)) {
                    $rules[$field] = $rule;
                }
            }
        }

        $validated = !empty($rules) ? $request->validate($rules) : [];

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
        
        // Handle profile_photo upload (from pictures tab)
        if ($request->hasFile('profile_photo')) {
            // Delete old images if exist
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }
            if ($profile->profile_photo && Storage::disk('public')->exists($profile->profile_photo)) {
                Storage::disk('public')->delete($profile->profile_photo);
            }
            
            // Store new image
            $imagePath = $request->file('profile_photo')->store('profiles', 'public');
            $user->profile_image = $imagePath;
            $profile->profile_photo = $imagePath;
        }
        
        // Handle remove profile photo
        if ($request->has('remove_profile_photo') && $request->boolean('remove_profile_photo')) {
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }
            if ($profile->profile_photo && Storage::disk('public')->exists($profile->profile_photo)) {
                Storage::disk('public')->delete($profile->profile_photo);
            }
            $user->profile_image = null;
            $profile->profile_photo = null;
        }

        // Update user data - only if fields are present in request
        if (isset($validated['name'])) {
            $user->name = $validated['name'];
        }
        if (isset($validated['email'])) {
            $user->email = $validated['email'];
        }
        if (isset($validated['gender'])) {
            $user->gender = $validated['gender'];
        }
        $user->save();

        // Update profile data - only update fields that are present in request
        if (isset($validated['category'])) {
            $profile->category = $validated['category'];
        }
        if (isset($validated['home_location'])) {
            $profile->home_location = $validated['home_location'];
        }
        if (isset($validated['country'])) {
            $profile->country = $validated['country'];
        }
        if (isset($validated['city'])) {
            $profile->city = $validated['city'];
        }
        if (isset($validated['latitude'])) {
            $profile->latitude = $validated['latitude'];
        }
        if (isset($validated['longitude'])) {
            $profile->longitude = $validated['longitude'];
        }
        
        // Handle preferences array - only update if present
        if (isset($validated['preferences'])) {
            $preferences = $validated['preferences'];
            $profile->preferences = !empty($preferences) ? json_encode($preferences) : null;
        }
        
        // Handle languages array - only update if present
        if (isset($validated['languages'])) {
            $languages = $validated['languages'];
            $profile->languages = !empty($languages) ? json_encode($languages) : null;
        }
        
        // Handle boolean fields - only update if present
        if ($request->has('profile_visible')) {
            $profile->profile_visible = $request->boolean('profile_visible');
        }
        if ($request->has('allow_wall_posts')) {
            $profile->allow_wall_posts = $request->boolean('allow_wall_posts');
        }
        if ($request->has('show_online_status')) {
            $profile->show_online_status = $request->boolean('show_online_status');
        }
        if ($request->has('show_last_active')) {
            $profile->show_last_active = $request->boolean('show_last_active');
        }

        // Handle couple vs single data - merge with existing data instead of replacing
        if ($isCouple) {
            // Get existing couple data
            $existingCoupleData = $profile->couple_data 
                ? (is_array($profile->couple_data) ? $profile->couple_data : json_decode($profile->couple_data, true) ?? [])
                : [];
            
            // Merge only fields that are present in the request
            $coupleFields = [
                'date_of_birth_her', 'date_of_birth_him', 'sexuality_her', 'sexuality_him',
                'relationship_status_her', 'relationship_status_him', 'smoking_her', 'smoking_him',
                'experience_her', 'experience_him', 'travel_options_her', 'travel_options_him',
                'bio_her', 'bio_him', 'weight_her', 'weight_him', 'height_her', 'height_him',
                'body_type_her', 'body_type_him', 'eye_color_her', 'eye_color_him',
                'hair_color_her', 'hair_color_him', 'tattoos_her', 'tattoos_him',
                'piercings_her', 'piercings_him', 'boob_size_her', 'dick_size_him'
            ];
            
            foreach ($coupleFields as $field) {
                if (isset($validated[$field])) {
                    $existingCoupleData[$field] = $validated[$field];
                }
            }
            
            // Store merged couple data
            $profile->couple_data = $existingCoupleData;
            
            // Update main bio only if bio_her or bio_him is being updated
            if (isset($validated['bio_her']) || isset($validated['bio_him'])) {
                $profile->bio = $validated['bio_her'] ?? $validated['bio_him'] ?? $profile->bio;
            }
        } else {
            // Single mode - only update fields that are present
            if (isset($validated['date_of_birth'])) {
                $profile->date_of_birth = $validated['date_of_birth'];
            }
            if (isset($validated['bio'])) {
                $profile->bio = $validated['bio'];
            }
            if (isset($validated['sexuality'])) {
                $profile->sexuality = $validated['sexuality'];
            }
            if (isset($validated['relationship_status'])) {
                $profile->relationship_status = $validated['relationship_status'];
            }
            if (isset($validated['relationship_orientation'])) {
                $profile->relationship_orientation = $validated['relationship_orientation'];
            }
            if (isset($validated['smoking'])) {
                $profile->smoking = $validated['smoking'];
            }
            if (isset($validated['piercings'])) {
                $profile->piercings = $validated['piercings'];
            }
            if (isset($validated['tattoos'])) {
                $profile->tattoos = $validated['tattoos'];
            }
            if (isset($validated['looks_important'])) {
                $profile->looks_important = $validated['looks_important'];
            }
            if (isset($validated['intelligence_important'])) {
                $profile->intelligence_important = $validated['intelligence_important'];
            }
        }
        
        // Handle photo uploads (Adult, Non-Adult, Album)
        $albumPhotos = $profile->album_photos 
            ? (is_array($profile->album_photos) ? $profile->album_photos : json_decode($profile->album_photos, true) ?? [])
            : [];
        
        // Initialize structure if needed
        if (!isset($albumPhotos['adult'])) $albumPhotos['adult'] = [];
        if (!isset($albumPhotos['non_adult'])) $albumPhotos['non_adult'] = [];
        if (!isset($albumPhotos['album'])) $albumPhotos['album'] = [];
        
        // Handle Non-Adult Photos
        $deletedNonAdult = $request->input('deleted_non_adult_photos', []);
        
        // If request has existing_non_adult_photos, use it; otherwise preserve existing from database
        if ($request->has('existing_non_adult_photos')) {
            $existingNonAdult = $request->input('existing_non_adult_photos', []);
            // Filter out deleted photos from existing
            $albumPhotos['non_adult'] = array_filter($existingNonAdult, function($photo) use ($deletedNonAdult) {
                return !in_array($photo, $deletedNonAdult);
            });
        } else {
            // Preserve existing photos from database, but remove deleted ones
            $albumPhotos['non_adult'] = array_filter($albumPhotos['non_adult'], function($photo) use ($deletedNonAdult) {
                return !in_array($photo, $deletedNonAdult);
            });
        }
        
        // Add new uploaded photos
        if ($request->hasFile('non_adult_photos')) {
            foreach ($request->file('non_adult_photos') as $file) {
                $path = $file->store('profiles/non-adult', 'public');
                $albumPhotos['non_adult'][] = $path;
            }
        }
        
        // Delete removed non-adult photos from storage
        foreach ($deletedNonAdult as $photoPath) {
            if (Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }
        }
        
        $albumPhotos['non_adult'] = array_values($albumPhotos['non_adult']); // Re-index
        
        // Handle Adult Photos
        $deletedAdult = $request->input('deleted_adult_photos', []);
        
        // If request has existing_adult_photos, use it; otherwise preserve existing from database
        if ($request->has('existing_adult_photos')) {
            $existingAdult = $request->input('existing_adult_photos', []);
            // Filter out deleted photos from existing
            $albumPhotos['adult'] = array_filter($existingAdult, function($photo) use ($deletedAdult) {
                return !in_array($photo, $deletedAdult);
            });
        } else {
            // Preserve existing photos from database, but remove deleted ones
            $albumPhotos['adult'] = array_filter($albumPhotos['adult'], function($photo) use ($deletedAdult) {
                return !in_array($photo, $deletedAdult);
            });
        }
        
        // Add new uploaded photos
        if ($request->hasFile('adult_photos')) {
            foreach ($request->file('adult_photos') as $file) {
                $path = $file->store('profiles/adult', 'public');
                $albumPhotos['adult'][] = $path;
            }
        }
        
        // Delete removed adult photos from storage
        foreach ($deletedAdult as $photoPath) {
            if (Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }
        }
        
        $albumPhotos['adult'] = array_values($albumPhotos['adult']); // Re-index
        
        // Handle Album Photos
        $existingAlbum = $request->input('existing_album_photos', []);
        $deletedAlbum = $request->input('deleted_album_photos', []);
        
        // Filter out deleted photos from existing
        $albumPhotos['album'] = array_filter($existingAlbum, function($photo) use ($deletedAlbum) {
            return !in_array($photo, $deletedAlbum);
        });
        
        // Add new uploaded photos
        if ($request->hasFile('album_photos')) {
            foreach ($request->file('album_photos') as $file) {
                $path = $file->store('profiles/album', 'public');
                $albumPhotos['album'][] = $path;
            }
        }
        
        // Delete removed album photos from storage
        foreach ($deletedAlbum as $photoPath) {
            if (Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }
        }
        
        $albumPhotos['album'] = array_values($albumPhotos['album']); // Re-index
        
        // Update album_photos in profile
        $profile->album_photos = $albumPhotos;
        
        // Handle Videos
        $videos = $profile->videos 
            ? (is_array($profile->videos) ? $profile->videos : json_decode($profile->videos, true) ?? [])
            : [];
        
        $deletedVideos = $request->input('deleted_videos', []);
        
        // Preserve existing videos, but remove deleted ones
        $videos = array_filter($videos, function($video) use ($deletedVideos) {
            return !in_array($video, $deletedVideos);
        });
        
        // Add new uploaded videos
        if ($request->hasFile('videos')) {
            foreach ($request->file('videos') as $file) {
                $path = $file->store('profiles/videos', 'public');
                $videos[] = $path;
            }
        }
        
        // Delete removed videos from storage
        foreach ($deletedVideos as $videoPath) {
            if (Storage::disk('public')->exists($videoPath)) {
                Storage::disk('public')->delete($videoPath);
            }
        }
        
        $videos = array_values($videos); // Re-index
        
        // Update videos in profile
        $profile->videos = $videos;
        
        $profile->save();

        // Update password if provided
        if (!empty($validated['password'])) {
            if (empty($validated['current_password'])) {
                return back()->withErrors(['current_password' => 'Current password is required to change your password.']);
            }
            
            if (Hash::check($validated['current_password'], $user->password)) {
                $user->password = bcrypt($validated['password']);
                $user->save();
            } else {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }
        }

        $message = !empty($validated['password']) 
            ? 'Account information and password updated successfully!' 
            : 'Account information updated successfully!';

        return redirect()->route('account.profile')
            ->with('success', $message);
    }

    /**
     * Delete the user account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $profile = $user->profile;

        // Delete profile images if they exist
        if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
            Storage::disk('public')->delete($user->profile_image);
        }

        // Delete profile photos if they exist
        if ($profile) {
            if ($profile->profile_photo && Storage::disk('public')->exists($profile->profile_photo)) {
                Storage::disk('public')->delete($profile->profile_photo);
            }
            

            // Delete album photos if they exist
            if ($profile->album_photos) {
                $albumPhotos = is_array($profile->album_photos) 
                    ? $profile->album_photos 
                    : json_decode($profile->album_photos, true) ?? [];
                
                foreach ($albumPhotos as $photo) {
                    if ($photo && Storage::disk('public')->exists($photo)) {
                        Storage::disk('public')->delete($photo);
                    }
                }
            }

            // Delete the profile
            $profile->delete();
        }

        // Logout the user before deleting account
        Auth::logout();

        // Delete the user account (soft delete)
        $user->delete();

        // Invalidate the session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'Your account has been deleted successfully.');
    }
}

