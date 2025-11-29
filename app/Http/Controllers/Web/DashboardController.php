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
        $query = User::with('profile')
            ->where('is_active', true)
            ->where('is_admin', false)
            ->where('id', '!=', auth()->id());

        // Search filter
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

        // Category filter
        if ($request->filled('category') && $request->get('category') !== 'All Categories') {
            $category = $request->get('category');
            $query->whereHas('profile', function ($q) use ($category) {
                $q->where('category', $category);
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

