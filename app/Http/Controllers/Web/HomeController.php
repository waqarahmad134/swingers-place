<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Slide;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the landing page.
     */
    public function index()
    {
        // Redirect authenticated users to members page
        if (auth()->check()) {
            return redirect()->route('dashboard.members');
        }

        // Get active slides ordered by order field, then by creation date
        $slidesData = Slide::where('is_active', true)
            ->orderBy('order')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($slide) {
                return [
                    'image' => $slide->image ? asset('storage/' . $slide->image) : 'https://picsum.photos/id/1060/1600/900',
                    'title' => $slide->title,
                    'tagline' => $slide->tagline ?? '',
                    'subtitle' => $slide->subtitle ?? '',
                    'button_label' => $slide->button_label ?? '',
                    'button_url' => $slide->button_url ?? '#',
                ];
            })
            ->toArray();

        // If no slides exist, use default slides
        if (empty($slidesData)) {
            $slidesData = [
                [
                    'image' => 'https://picsum.photos/id/1060/1600/900',
                    'title' => 'Welcome',
                    'subtitle' => 'Get started by creating your first slide in the admin panel.',
                    'button_label' => 'Get Started',
                    'button_url' => '#',
                ],
            ];
        }

        // Get users with companies (only normal profiles)
        $users = User::where('profile_type', 'normal')
            ->whereNotNull('company')
            ->where('company', '!=', '')
            ->where('is_active', true)
            ->latest()
            ->get();

        // Get latest 4 unique companies
        $companies = $users->pluck('company')
            ->unique()
            ->filter()
            ->take(4)
            ->values();

        // Create tabs: "All" + company names
        $tabs = collect([['id' => 'all', 'label' => 'All']])
            ->merge($companies->map(fn($company) => [
                'id' => \Str::slug($company),
                'label' => $company
            ]))
            ->toArray();

        // Group users by company
        $tabbedUsers = [];
        $tabbedUsers['all'] = $users;

        foreach ($companies as $company) {
            $slug = \Str::slug($company);
            $tabbedUsers[$slug] = $users->filter(function($user) use ($company) {
                return $user->company === $company;
            });
        }

        return view('pages.home.index', [
            'slides' => $slidesData,
            'tabs' => $tabs,
            'tabbedUsers' => $tabbedUsers,
        ]);
    }

    /**
     * Display the user profile page.
     */
    public function showProfile($username)
    {
        // Try to find by username first
        $user = User::where('username', $username)
            ->where('profile_type', 'normal')
            ->where('is_active', true)
            ->first();
        
        // If not found and parameter is numeric, try ID (for backward compatibility with old links)
        if (!$user && is_numeric($username)) {
            $user = User::where('id', $username)
                ->where('profile_type', 'normal')
                ->where('is_active', true)
                ->first();
        }
        
        if (!$user) {
            abort(404);
        }

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
        
        // Check if viewing own profile
        $isOwnProfile = auth()->check() && auth()->id() == $user->id;
        
        // Decode JSON fields safely
        $preferences = $profile && $profile->preferences 
            ? (is_array($profile->preferences) ? $profile->preferences : json_decode($profile->preferences, true) ?? [])
            : [];
        $languages = $profile && $profile->languages 
            ? (is_array($profile->languages) ? $profile->languages : json_decode($profile->languages, true) ?? [])
            : [];
        
        return view('pages.profile.index', compact('user', 'profile', 'age', 'ageHer', 'ageHim', 'isCouple', 'coupleData', 'joinDate', 'isOwnProfile', 'preferences', 'languages'));
    }
}

