<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the members page.
     */
    public function members(Request $request): View
    {
        $currentUserId = auth()->id();
        
        // Build query and always exclude current user, admins, and editors
        $query = User::with('profile')
            ->where('is_active', true)
            ->where('is_admin', false)
            ->where('is_editor', false)
            ->where('id', '!=', $currentUserId);

        // Search filter with category support
        if ($request->filled('search')) {
            $search = $request->get('search');
            $searchCategory = $request->get('search_category', 'all');
            
            $query->where(function ($q) use ($search, $searchCategory) {
                if ($searchCategory === 'login_name') {
                    // Search only in users table (name, username, email)
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('username', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%");
                } elseif ($searchCategory === 'profile_text') {
                    // Search only in profile table (bio, looking_for, additional_notes)
                    $q->whereHas('profile', function ($profileQuery) use ($search) {
                        $profileQuery->where('bio', 'like', "%{$search}%")
                                     ->orWhere('looking_for', 'like', "%{$search}%")
                                     ->orWhere('additional_notes', 'like', "%{$search}%");
                    });
                } else {
                    // Default: search in both users and profile tables (all)
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('username', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhereHas('profile', function ($profileQuery) use ($search) {
                          $profileQuery->where('home_location', 'like', "%{$search}%")
                                       ->orWhere('bio', 'like', "%{$search}%")
                                       ->orWhere('looking_for', 'like', "%{$search}%")
                                       ->orWhere('additional_notes', 'like', "%{$search}%");
                      });
                }
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

        // New filter sidebar filters - combine with OR logic
        $hasCategoryFilter = false;
        $categoryConditions = [];
        
        // Couples filter
        if ($request->boolean('filter_couples')) {
            $hasCategoryFilter = true;
            $categoryConditions[] = function ($q) {
                $q->whereHas('profile', function ($profileQ) {
                    $profileQ->where('category', 'couple');
                });
            };
        }
        
        // Female filter
        if ($request->boolean('filter_female')) {
            $hasCategoryFilter = true;
            $categoryConditions[] = function ($q) {
                $q->where(function ($subQ) {
                    $subQ->where('gender', 'female')
                         ->orWhereHas('profile', function ($profileQ) {
                             $profileQ->where('category', 'single_female');
                         });
                });
            };
        }
        
        // Male filter
        if ($request->boolean('filter_male')) {
            $hasCategoryFilter = true;
            $categoryConditions[] = function ($q) {
                $q->where(function ($subQ) {
                    $subQ->where('gender', 'male')
                         ->orWhereHas('profile', function ($profileQ) {
                             $profileQ->where('category', 'single_male');
                         });
                });
            };
        }
        
        // Business filter
        if ($request->boolean('filter_business')) {
            $hasCategoryFilter = true;
            $categoryConditions[] = function ($q) {
                $q->whereHas('profile', function ($profileQ) {
                    $profileQ->where(function ($pq) {
                        $pq->where('category', 'business')
                           ->orWhere('profile_type', 'business');
                    });
                });
            };
        }
        
        // Transgender filter
        if ($request->boolean('filter_transgender')) {
            $hasCategoryFilter = true;
            $categoryConditions[] = function ($q) {
                $q->where(function ($subQ) {
                    $subQ->where('gender', 'transgender')
                         ->orWhere('gender', 'other')
                         ->orWhere('gender', 'non-binary');
                });
            };
        }
        
        // Apply category filters with OR logic
        if ($hasCategoryFilter && !empty($categoryConditions)) {
            $query->where(function ($q) use ($categoryConditions) {
                foreach ($categoryConditions as $index => $condition) {
                    if ($index === 0) {
                        $condition($q);
                    } else {
                        $q->orWhere(function ($subQ) use ($condition) {
                            $condition($subQ);
                        });
                    }
                }
            });
        }
        
        // Looking for me/us filter (placeholder - would need a profile field for this)
        if ($request->boolean('filter_looking_for_me')) {
            // This would require a profile field like 'looking_for' or similar
            // For now, we'll skip this filter
        }
        
        // With photos only filter
        if ($request->boolean('with_photos_only')) {
            $query->where(function ($q) {
                $q->whereHas('profile', function ($profileQ) {
                    $profileQ->whereNotNull('profile_photo')
                             ->orWhereNotNull('album_photos');
                })
                ->orWhereNotNull('profile_image');
            });
        }
        
        // With videos only filter (placeholder - would need video field)
        if ($request->boolean('with_videos_only')) {
            // This would require a videos field in the profile
            // For now, we'll skip this filter
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

        // Check if random mode is requested
        $isRandom = $request->boolean('random');
        
        // Sort by
        $sortBy = $request->get('sort_by', 'Random');
        if ($isRandom || $sortBy === 'Random') {
            // Random ordering - use database random function
            // This will shuffle members differently on each page load
            $query->inRandomOrder();
        } elseif ($sortBy !== 'All' && $sortBy !== 'Random') {
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
        } else {
            // Default ordering: Random shuffle on each visit
            $query->inRandomOrder();
        }

        $members = $query->paginate(20)->withQueryString();
        return view('pages.dashboard.member', compact('members'));
    }

    /**
     * Display the search page.
     */
    public function search(Request $request): View|RedirectResponse
    {
        // If query is provided, redirect to members page with search parameters
        if ($request->filled('query')) {
            $queryParams = [
                'search' => $request->get('query'),
            ];
            
            // Add category filter if not 'all'
            if ($request->filled('category')) {
                $category = $request->get('category');
                // Only pass actionable categories (all, login_name, profile_text)
                $actionableCategories = ['all', 'login_name', 'profile_text'];
                if (in_array($category, $actionableCategories)) {
                    $queryParams['search_category'] = $category;
                }
            }
            
            return redirect()->route('dashboard.members', $queryParams);
        }
        
        return view('pages.dashboard.search');
    }
}

