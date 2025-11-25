<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PhotoModerationController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->get('status', 'pending'); // pending, flagged, approved, rejected, all
        
        // For now, we'll use sample data structure
        // In the future, you can create a photo_moderations table
        $users = User::whereNotNull('profile_image')->take(4)->get();
        
        // Sample data structure - replace with actual database queries
        $samplePhotos = [];
        
        if ($users->count() > 0) {
            $userImages = [
                asset('admin-assets/user 1.jpg'),
                asset('admin-assets/user 2.jpg'),
                asset('admin-assets/user 4.jpg'),
                asset('admin-assets/user 5.jpg'),
            ];
            
            foreach ($users as $index => $user) {
                $samplePhotos[] = [
                    'id' => $user->id,
                    'user' => $user,
                    'image' => $user->profile_image ? asset('storage/' . $user->profile_image) : ($userImages[$index] ?? asset('admin-assets/user 1.jpg')),
                    'uploaded_at' => $user->updated_at ?? now()->subDays(rand(1, 5)),
                    'status' => 'pending',
                ];
            }
        }
        
        // Filter by status
        if ($status !== 'all') {
            $samplePhotos = array_filter($samplePhotos, function($p) use ($status) {
                return $p['status'] === $status;
            });
        }
        
        // Stats
        $stats = [
            'pending' => count(array_filter($samplePhotos, fn($p) => $p['status'] === 'pending')),
            'flagged' => count(array_filter($samplePhotos, fn($p) => $p['status'] === 'flagged')),
            'approved' => count(array_filter($samplePhotos, fn($p) => $p['status'] === 'approved')),
            'rejected' => count(array_filter($samplePhotos, fn($p) => $p['status'] === 'rejected')),
        ];
        
        return view('admin.photo-moderation.index', [
            'photos' => $samplePhotos,
            'currentStatus' => $status,
            'stats' => $stats,
        ]);
    }
    
    public function approve($id)
    {
        // TODO: Implement approval logic
        return redirect()->route('admin.photo-moderation.index', ['status' => 'pending'])
            ->with('success', 'Photo approved successfully!');
    }
    
    public function reject($id)
    {
        // TODO: Implement rejection logic
        return redirect()->route('admin.photo-moderation.index', ['status' => 'pending'])
            ->with('success', 'Photo rejected successfully!');
    }
}

