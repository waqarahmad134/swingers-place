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
        // Check if profile type is selected, if not redirect to profile type selection
        if (!session('admin_creating_profile_type')) {
            return redirect()->route('admin.users.select-profile-type');
        }
        
        $profileType = session('admin_creating_profile_type');
        return view('admin.users.create', compact('profileType'));
    }
    
    // Profile type selection (before user creation)
    public function selectProfileType(): View
    {
        return view('admin.users.select-profile-type');
    }
    
    public function storeProfileType(Request $request): RedirectResponse
    {
        $request->validate([
            'profile_type' => 'required|in:normal,business',
        ]);
        
        // Store profile type in session
        session(['admin_creating_profile_type' => $request->profile_type]);
        
        return redirect()->route('admin.users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'name' => ['nullable', 'string', 'max:255'],
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
            'profile_type' => ['nullable', 'in:normal,business'],
            'is_admin' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
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

        // Get profile type from session or request
        $profileType = $validated['profile_type'] ?? session('admin_creating_profile_type', 'normal');

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
            ? \Illuminate\Support\Facades\Hash::make($validated['password']) 
            : \Illuminate\Support\Facades\Hash::make(uniqid('user_', true));

        $user = User::create([
            'name' => trim($fullName) ?: 'User',
            'username' => $validated['username'] ?? null,
            'first_name' => $validated['first_name'] ?? null,
            'last_name' => $validated['last_name'] ?? null,
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'password' => $password,
            'profile_type' => $profileType,
            'is_admin' => $request->boolean('is_admin', false),
            'is_active' => $request->boolean('is_active', true),
        ]);

        // Store user_id in session for onboarding
        session(['admin_creating_user_id' => $user->id]);
        
        // Clear profile type from session
        session()->forget('admin_creating_profile_type');
        
        // Create basic profile (not completed) - same as registration
        \App\Models\UserProfile::create([
            'user_id' => $user->id,
            'profile_type' => $profileType,
            'onboarding_completed' => false,
            'onboarding_step' => 1, // Will start at step 1 (category selection)
        ]);

        // Redirect to admin onboarding - start with step 1 (category selection)
        return redirect()->route('admin.users.onboarding.step1');
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
            'cover_photo' => $profile && $profile->cover_photo ? asset('storage/' . $profile->cover_photo) : null,
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
            'cover_photo' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:5120'],
            'remove_profile_image' => ['nullable', 'boolean'],
            'remove_cover_photo' => ['nullable', 'boolean'],
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
        
        // Handle cover photo upload
        if ($request->hasFile('cover_photo')) {
            if ($profile->cover_photo && Storage::disk('public')->exists($profile->cover_photo)) {
                Storage::disk('public')->delete($profile->cover_photo);
            }
            $coverPath = $request->file('cover_photo')->store('covers', 'public');
            $profile->cover_photo = $coverPath;
        } elseif ($request->boolean('remove_cover_photo')) {
            if ($profile->cover_photo && Storage::disk('public')->exists($profile->cover_photo)) {
                Storage::disk('public')->delete($profile->cover_photo);
            }
            $profile->cover_photo = null;
        }
        
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

        return redirect()->route('admin.users.index')
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

        return redirect()->route('admin.users.index')
            ->with('success', $message);
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully!');
    }
}

