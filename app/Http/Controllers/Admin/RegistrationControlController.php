<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RegistrationControlController extends Controller
{
    public function index(): View
    {
        // Sample settings - in the future, this can come from a database or config
        $settings = [
            'open_registration' => false,
            'email_verification' => true,
            'admin_approval' => false,
            'current_status' => 'open', // open or closed
            'regions' => [
                'north_america' => true,
                'europe' => true,
                'asia' => true,
                'other_regions' => true,
            ],
        ];
        
        return view('admin.registration-control.index', compact('settings'));
    }
    
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'open_registration' => ['nullable', 'boolean'],
            'email_verification' => ['nullable', 'boolean'],
            'admin_approval' => ['nullable', 'boolean'],
            'regions' => ['nullable', 'array'],
            'regions.north_america' => ['nullable', 'boolean'],
            'regions.europe' => ['nullable', 'boolean'],
            'regions.asia' => ['nullable', 'boolean'],
            'regions.other_regions' => ['nullable', 'boolean'],
        ]);
        
        // TODO: Save settings to database or config file
        // For now, just redirect with success message
        
        return redirect()->route('admin.registration-control.index')
            ->with('success', 'Registration settings updated successfully!');
    }
}

