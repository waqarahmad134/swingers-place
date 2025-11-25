<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'pending_verifications' => 47, // TODO: Implement verification system
            'reported_profiles' => 12, // TODO: Implement report system
            'new_registrations_today' => User::whereDate('created_at', today())->count(),
            'growth_rate' => 24.5, // TODO: Calculate actual growth rate
        ];

        return view('admin.dashboard', compact('stats'));
    }
}

