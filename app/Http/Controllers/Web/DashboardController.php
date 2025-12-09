<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the members page.
     */
    public function members(Request $request): View
    {
        $currentUserId = auth()->id();
        
        // Build query and always exclude current user
        $query = User::with('profile')
            ->where('is_active', true)
            ->where('is_admin', false)
            ->where('id', '!=', $currentUserId);

        // Search filter (general search - name, location, interests)
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhereHas('profile', function ($profileQuery) use ($search) {
                      $profileQuery->where('home_location', 'like', "%{$search}%")
                                   ->orWhere('bio', 'like', "%{$search}%");
                  });
            });
        }

        // Gender filter
        if ($request->filled('filter_gender')) {
            $query->where('gender', $request->get('filter_gender'));
        }

        // Location filter
        if ($request->filled('filter_location')) {
            $location = $request->get('filter_location');
            $query->whereHas('profile', function ($q) use ($location) {
                $q->where('home_location', 'like', "%{$location}%");
            });
        }

        // Category filter
        if ($request->filled('filter_category')) {
            $category = $request->get('filter_category');
            $query->whereHas('profile', function ($q) use ($category) {
                $q->where('category', $category);
            });
        }

        // Company filter (still available if needed)
        if ($request->filled('filter_company')) {
            $company = $request->get('filter_company');
            $query->where('company', 'like', "%{$company}%");
        }

        // Country filter (still available if needed)
        if ($request->filled('filter_country')) {
            $country = $request->get('filter_country');
            $query->whereHas('profile', function ($q) use ($country) {
                $q->where('country', 'like', "%{$country}%");
            });
        }

        // City filter (still available if needed)
        if ($request->filled('filter_city')) {
            $city = $request->get('filter_city');
            $query->whereHas('profile', function ($q) use ($city) {
                $q->where('city', 'like', "%{$city}%");
            });
        }

        // Profile Type filter (still available if needed)
        if ($request->filled('filter_profile_type')) {
            $profileType = $request->get('filter_profile_type');
            $query->whereHas('profile', function ($q) use ($profileType) {
                $q->where('profile_type', $profileType);
            });
        }

        // Legacy category filter (from old filter bar)
        if ($request->filled('category') && $request->get('category') !== 'All Categories') {
            $category = $request->get('category');
            $query->whereHas('profile', function ($q) use ($category) {
                $q->where('category', $category);
            });
        }

        // Eye Color filter (still available if needed)
        if ($request->filled('filter_eye_color')) {
            $eyeColor = $request->get('filter_eye_color');
            $query->whereHas('profile', function ($q) use ($eyeColor) {
                $q->where('eye_color', $eyeColor);
            });
        }

        // Preferences (Things They Like) filter (still available if needed)
        if ($request->filled('filter_preferences')) {
            $preference = $request->get('filter_preferences');
            $query->whereHas('profile', function ($q) use ($preference) {
                $q->whereJsonContains('preferences', $preference);
            });
        }

        // Age range filter
        if ($request->filled('age_range') && $request->get('age_range') !== 'Any Age') {
            $ageRange = $request->get('age_range');
            $query->whereHas('profile', function ($q) use ($ageRange) {
                if ($ageRange === '18-25') {
                    $q->whereBetween('date_of_birth', [
                        now()->subYears(25)->format('Y-m-d'),
                        now()->subYears(18)->format('Y-m-d')
                    ]);
                } elseif ($ageRange === '26-35') {
                    $q->whereBetween('date_of_birth', [
                        now()->subYears(35)->format('Y-m-d'),
                        now()->subYears(26)->format('Y-m-d')
                    ]);
                } elseif ($ageRange === '36-45') {
                    $q->whereBetween('date_of_birth', [
                        now()->subYears(45)->format('Y-m-d'),
                        now()->subYears(36)->format('Y-m-d')
                    ]);
                } elseif ($ageRange === '45+') {
                    $q->where('date_of_birth', '<=', now()->subYears(45)->format('Y-m-d'));
                }
            });
        }

        // Online only filter
        if ($request->boolean('online_only')) {
            $fiveMinutesAgo = now()->subMinutes(5);
            $now = now();
            
            // Filter users who are online based on isOnline() logic
            $query->where(function ($q) use ($fiveMinutesAgo, $now) {
                // User must have last_seen_at
                $q->whereNotNull('last_seen_at')
                  // last_seen_at must be within last 5 minutes
                  ->where('last_seen_at', '>', $fiveMinutesAgo)
                  // Either no scheduled_offline_at or it hasn't passed yet
                  ->where(function ($subQ) use ($now) {
                      $subQ->whereNull('scheduled_offline_at')
                           ->orWhere('scheduled_offline_at', '>', $now);
                  });
            })
            // Also respect privacy setting: only exclude if profile exists AND show_online_status is explicitly false
            ->where(function ($q) {
                // Users without profile are included (default to showing online)
                $q->whereDoesntHave('profile')
                  // OR users with profile where show_online_status is not explicitly false
                  ->orWhereHas('profile', function ($profileQuery) {
                      $profileQuery->where(function ($pq) {
                          // Include if null (default) or true
                          $pq->whereNull('show_online_status')
                             ->orWhere('show_online_status', true);
                      });
                  });
            });
        }

        // Sort by
        $sortBy = $request->get('sort_by', 'All');
        if ($sortBy !== 'All') {
            switch ($sortBy) {
                case 'Newest':
                    $query->latest();
                    break;
                case 'Best Match':
                    // For now, just use newest as best match
                    $query->latest();
                    break;
                case 'Distance':
                    // If user has location, sort by distance (for now just by location)
                    $query->whereHas('profile', function ($q) {
                        $q->whereNotNull('home_location');
                    })->latest();
                    break;
                case 'Popularity':
                    // For now, use created_at as popularity indicator
                    $query->oldest();
                    break;
            }
        }

        $members = $query->paginate(20)->withQueryString();
        return view('pages.dashboard.member', compact('members'));
    }
}

