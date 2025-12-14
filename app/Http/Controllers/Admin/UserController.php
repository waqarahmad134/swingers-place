<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\Response;

class UserController extends Controller
{
    /**
     * Get the correct route prefix based on user role
     */
    protected function getRoutePrefix(): string
    {
        return auth()->user()->is_editor ? 'editor' : 'admin';
    }

    public function index(Request $request): View
    {
        $search = $request->get('search', '');
        $status = $request->get('status', 'all'); // all, active, pending, verified, banned
        
        $query = User::with('profile');
        
        // Apply search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }
        
        // Apply status filter
        switch ($status) {
            case 'active':
                // Active: is_active = true and email verified
                $query->where('is_active', true)
                      ->whereNotNull('email_verified_at');
                break;
                
            case 'pending':
                // Pending: email not verified yet
                $query->whereNull('email_verified_at');
                break;
                
            case 'verified':
                // Verified: is_admin = true (admins are verified) OR has email verified
                $query->where(function ($q) {
                    $q->where('is_admin', true)
                      ->orWhereNotNull('email_verified_at');
                });
                break;
                
            case 'banned':
                // Banned: is_active = false
                $query->where('is_active', false);
                break;
                
            case 'all':
            default:
                // Show all users
                break;
        }
        
        $users = $query->latest()->paginate(15)->withQueryString();
        
        // Get counts for each status
        $counts = [
            'all' => User::count(),
            'active' => User::where('is_active', true)->whereNotNull('email_verified_at')->count(),
            'pending' => User::whereNull('email_verified_at')->count(),
            'verified' => User::where(function ($q) {
                $q->where('is_admin', true)
                  ->orWhereNotNull('email_verified_at');
            })->count(),
            'banned' => User::where('is_active', false)->count(),
        ];
        
        return view('admin.users.index', compact('users', 'search', 'status', 'counts'));
    }

    public function create(): View|RedirectResponse
    {
        // Always default to normal profile type
        if (!session('admin_creating_profile_type')) {
            session(['admin_creating_profile_type' => 'normal']);
        }
        
        $profileType = session('admin_creating_profile_type', 'normal');
        return view('admin.users.create', compact('profileType'));
    }

    public function store(Request $request): RedirectResponse
    {
        $accountType = $request->input('account_type', 'user');
        
        // Base rules for all account types
        $rules = [
            'account_type' => ['required', 'in:user,editor'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ];

        // Editor only needs basic info
        if ($accountType === 'editor') {
            $validated = $request->validate($rules);
            
            // Auto-set name from username
            $fullName = $validated['username'] ?? 'Editor';
            
            // Hash password
            $password = \Illuminate\Support\Facades\Hash::make($validated['password']);
            
            $user = User::create([
                'name' => $fullName,
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => $password,
                'is_editor' => true,
                'is_admin' => false,
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
            
            return redirect()->route($this->getRoutePrefix() . '.users.index')
                ->with('success', 'Editor account created successfully.');
        }
        
        // Regular user validation rules
        $rules = array_merge($rules, [
            'terms_accepted' => ['required', 'accepted'],
            'category' => ['required', 'in:couple,single_female,single_male,transsexual'],
            'preferences' => ['nullable', 'array'],
            'home_location' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'home_location_lat' => ['nullable', 'numeric'],
            'home_location_lng' => ['nullable', 'numeric'],
            'bio' => ['nullable', 'string'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:5120'],
            'is_admin' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        // Add category-specific validation
        $category = $request->input('category');
        if ($category === 'couple') {
            $rules['date_of_birth_her'] = ['nullable', 'date'];
            $rules['sexuality_her'] = ['nullable', 'string'];
            $rules['date_of_birth_him'] = ['nullable', 'date'];
            $rules['sexuality_him'] = ['nullable', 'string'];
        } else {
            $rules['date_of_birth'] = ['nullable', 'date'];
            $rules['sexuality'] = ['nullable', 'string'];
        }

        $validated = $request->validate($rules);

        // Get profile type - always normal for admin created users
        $profileType = 'normal';

        // Auto-set name from username
        $fullName = $validated['username'] ?? 'User';

        // Generate a password if not provided (required for authentication)
        $password = isset($validated['password']) && !empty($validated['password']) 
            ? \Illuminate\Support\Facades\Hash::make($validated['password']) 
            : \Illuminate\Support\Facades\Hash::make(uniqid('user_', true));

        $user = User::create([
            'name' => $fullName,
            'username' => $validated['username'] ?? null,
            'email' => $validated['email'] ?? null,
            'password' => $password,
            'profile_type' => $profileType,
            'is_admin' => $request->boolean('is_admin', false),
            'is_editor' => false,
            'is_active' => $request->boolean('is_active', true),
            'email_verified_at' => now(), // Auto-verify for admin-created users
        ]);

        // Handle profile photo upload
        $profilePhotoPath = null;
        if ($request->hasFile('profile_photo')) {
            $profilePhotoPath = $request->file('profile_photo')->store('profiles', 'public');
            $user->profile_image = $profilePhotoPath;
            $user->save();
        }

        // Get preferences
        $preferences = $validated['preferences'] ?? [];

        // Get basic info based on category
        $dateOfBirth = null;
        $sexuality = null;
        $coupleData = null;
        
        if ($category === 'couple') {
            // Store couple data in JSON
            $coupleData = [
                'date_of_birth_her' => $validated['date_of_birth_her'] ?? null,
                'sexuality_her' => $validated['sexuality_her'] ?? null,
                'date_of_birth_him' => $validated['date_of_birth_him'] ?? null,
                'sexuality_him' => $validated['sexuality_him'] ?? null,
            ];
        } else {
            $dateOfBirth = $validated['date_of_birth'] ?? null;
            $sexuality = $validated['sexuality'] ?? null;
        }

        // Create profile with all data (onboarding completed)
        \App\Models\UserProfile::create([
            'user_id' => $user->id,
            'profile_type' => $profileType,
            'category' => $category,
            'preferences' => !empty($preferences) ? json_encode($preferences) : null,
            'home_location' => $validated['home_location'] ?? null,
            'country' => $validated['country'] ?? null,
            'city' => $validated['city'] ?? null,
            'latitude' => $validated['home_location_lat'] ?? null,
            'longitude' => $validated['home_location_lng'] ?? null,
            'date_of_birth' => $dateOfBirth,
            'sexuality' => $sexuality,
            'couple_data' => $coupleData ? json_encode($coupleData) : null,
            'bio' => $validated['bio'] ?? null,
            'profile_photo' => $profilePhotoPath,
            'onboarding_completed' => true,
            'onboarding_step' => 9,
        ]);

        return redirect()->route($this->getRoutePrefix() . '.users.index')
            ->with('success', 'User created successfully.');
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }
    
    public function getData(User $user)
    {
        $profile = $user->profile;
        $age = null;
        if ($profile && $profile->date_of_birth) {
            $age = \Carbon\Carbon::parse($profile->date_of_birth)->age;
        }
        
        return response()->json([
            'id' => $user->id,
            'first_name' => $user->first_name ?? '',
            'last_name' => $user->last_name ?? '',
            'category' => $profile->category ?? '',
            'age' => $age,
            'location' => $profile->home_location ?? '',
            'gender' => $user->gender ?? '',
            'email' => $user->email,
            'bio' => $profile->bio ?? '',
            'profile_image' => $user->profile_image ? asset('storage/' . $user->profile_image) : null,
            'profile_photo' => $profile && $profile->profile_photo ? asset('storage/' . $profile->profile_photo) : null,
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'age' => ['nullable', 'integer', 'min:18', 'max:120'],
            'location' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', 'in:male,female,other,prefer_not_to_say'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'bio' => ['nullable', 'string', 'max:1000'],
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:5120'],
            'remove_profile_image' => ['nullable', 'boolean'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:255'],
            'profile_type' => ['nullable', 'in:normal,business'],
            'company' => ['nullable', 'string', 'max:255'],
            'website_url' => ['nullable', 'url', 'max:255'],
            'address' => ['nullable', 'string'],
            'business_address' => ['nullable', 'string'],
            'ssn' => ['nullable', 'string', 'max:255'],
            'is_admin' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = bcrypt($validated['password']);
        }

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }
            
            // Store new image
            $imagePath = $request->file('profile_image')->store('profiles', 'public');
            $validated['profile_image'] = $imagePath;
        } elseif ($request->boolean('remove_profile_image')) {
            // Remove profile image if requested
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $validated['profile_image'] = null;
        } else {
            // Keep existing image
            unset($validated['profile_image']);
        }

        $validated['is_admin'] = $request->boolean('is_admin', false);
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['profile_type'] = $validated['profile_type'] ?? $user->profile_type ?? 'normal';

        // Update user fields
        $userFields = ['first_name', 'last_name', 'email', 'gender', 'profile_image', 'password', 'phone', 'profile_type', 'company', 'website_url', 'address', 'business_address', 'ssn', 'is_admin', 'is_active'];
        $userData = array_intersect_key($validated, array_flip($userFields));
        $user->update($userData);

        // Update or create profile
        $profile = $user->profile ?? $user->profile()->create(['user_id' => $user->id]);
        
        // Calculate date_of_birth from age if provided
        if (isset($validated['age']) && $validated['age']) {
            $profile->date_of_birth = now()->subYears($validated['age'])->format('Y-m-d');
        }
        
        // Update profile fields
        $profileFields = ['category', 'home_location', 'bio'];
        foreach ($profileFields as $field) {
            if (isset($validated[$field])) {
                $profile->$field = $validated[$field];
            }
        }
        
        $profile->save();

        return redirect()->route($this->getRoutePrefix() . '.users.index')
            ->with('success', 'User updated successfully!');
    }

    public function verify(User $user): RedirectResponse
    {
        // Toggle verification status
        if ($user->email_verified_at) {
            // Unverify (set to pending)
            $user->email_verified_at = null;
            $message = 'User account unverified successfully!';
        } else {
            // Verify account
            $user->email_verified_at = now();
            $message = 'User account verified successfully!';
        }
        
        $user->save();

        return redirect()->route($this->getRoutePrefix() . '.users.index')
            ->with('success', $message);
    }

    public function toggleStatus(User $user): RedirectResponse
    {
        // Prevent admin/editor from banning themselves
        if ($user->id === auth()->id()) {
            return redirect()->route($this->getRoutePrefix() . '.users.index')
                ->with('error', 'You cannot change your own status!');
        }

        // Toggle active status
        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'activated' : 'banned';
        $message = "User account {$status} successfully!";

        return redirect()->route($this->getRoutePrefix() . '.users.index')
            ->with('success', $message);
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return redirect()->route($this->getRoutePrefix() . '.users.index')
            ->with('success', 'User deleted successfully!');
    }

    public function toggleOnlineStatus(User $user): \Illuminate\Http\JsonResponse
    {
        $isOnline = request()->boolean('is_online');
        
        if ($isOnline) {
            // Force online: set last_seen_at to now and clear scheduled offline (if any)
            $user->last_seen_at = now();
            $user->scheduled_offline_at = null; // Clear scheduled offline when manually setting online
            $message = 'User set to online';
        } else {
            // Force offline: set last_seen_at to 10 minutes ago (will appear offline)
            $user->last_seen_at = now()->subMinutes(10);
            $message = 'User set to offline';
        }
        
        $user->save();

        return response()->json([
            'success' => true,
            'message' => $message,
            'is_online' => $user->isOnline(),
        ]);
    }

    /**
     * Set scheduled offline time for a user (admin only).
     */
    public function setScheduledOffline(User $user): \Illuminate\Http\JsonResponse
    {
        $scheduledTime = request()->input('scheduled_offline_at');
        
        if ($scheduledTime) {
            try {
                $user->scheduled_offline_at = \Carbon\Carbon::parse($scheduledTime);
                $user->save();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Scheduled offline time set successfully',
                    'scheduled_offline_at' => $user->scheduled_offline_at->format('Y-m-d H:i:s'),
                    'is_online' => $user->isOnline(),
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid date/time format',
                ], 400);
            }
        } else {
            // Clear scheduled offline time
            $user->scheduled_offline_at = null;
            $user->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Scheduled offline time cleared',
                'is_online' => $user->isOnline(),
            ]);
        }
    }

    /**
     * Toggle message blocking for a user (admin only).
     */
    public function toggleMessageBlock(User $user): \Illuminate\Http\JsonResponse
    {
        $canMessage = request()->boolean('can_message', true);
        
        $user->can_message = $canMessage;
        $user->save();
        
        $status = $canMessage ? 'enabled' : 'blocked';
        $message = "User messaging {$status} successfully";
        
        return response()->json([
            'success' => true,
            'message' => $message,
            'can_message' => $user->can_message,
        ]);
    }
}

