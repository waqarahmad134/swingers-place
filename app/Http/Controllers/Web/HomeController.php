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

        // Get users with companies (only public profiles)
        $users = User::where('profile_type', 'public')
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
    public function showProfile($id)
    {
        $user = User::where('id', $id)
            ->where('profile_type', 'public')
            ->where('is_active', true)
            ->firstOrFail();

        return view('pages.user.profile', compact('user'));
    }
}

