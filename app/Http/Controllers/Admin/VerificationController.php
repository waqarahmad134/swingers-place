<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Verification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VerificationController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->get('status', 'pending'); // pending, approved, rejected, all
        
        $query = Verification::with(['user', 'reviewer']);
        
        // Filter by status
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        $verifications = $query->latest()->paginate(12);
        
        return view('admin.verification.index', [
            'verifications' => $verifications,
            'currentStatus' => $status,
        ]);
    }
    
    public function approve(Request $request, Verification $verification): RedirectResponse
    {
        $verification->update([
            'status' => 'approved',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);
        
        // Optionally verify the user's email as well
        if ($verification->user) {
            $verification->user->update([
                'email_verified_at' => now(),
            ]);
        }
        
        return redirect()->route('admin.verification.index', ['status' => 'pending'])
            ->with('success', 'Verification approved successfully!');
    }
    
    public function reject(Request $request, Verification $verification): RedirectResponse
    {
        $verification->update([
            'status' => 'rejected',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);
        
        return redirect()->route('admin.verification.index', ['status' => 'pending'])
            ->with('success', 'Verification rejected successfully!');
    }
}

