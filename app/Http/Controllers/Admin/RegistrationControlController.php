<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RegistrationSetting;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RegistrationControlController extends Controller
{
    public function index(): View
    {
        $settingsModel = RegistrationSetting::getSettings();
        
        // Convert model to array format expected by the view
        $settings = [
            'open_registration' => $settingsModel->open_registration,
            'email_verification' => $settingsModel->email_verification,
            'admin_approval' => $settingsModel->admin_approval,
            'current_status' => $settingsModel->open_registration ? 'open' : 'closed', // Derived from open_registration
            'regions' => $settingsModel->regions ?? [
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
            'current_status' => ['nullable', 'in:open,closed'], // This updates open_registration
            'regions' => ['nullable', 'array'],
            'regions.north_america' => ['nullable', 'boolean'],
            'regions.europe' => ['nullable', 'boolean'],
            'regions.asia' => ['nullable', 'boolean'],
            'regions.other_regions' => ['nullable', 'boolean'],
        ]);
        
        // Prepare data for update
        $data = [];
        
        if ($request->has('open_registration')) {
            $data['open_registration'] = $request->boolean('open_registration');
        }
        
        if ($request->has('email_verification')) {
            $data['email_verification'] = $request->boolean('email_verification');
        }
        
        if ($request->has('admin_approval')) {
            $data['admin_approval'] = $request->boolean('admin_approval');
        }
        
        // Update open_registration based on current_status (they're the same thing)
        if ($request->has('current_status')) {
            $data['open_registration'] = $request->input('current_status') === 'open';
        }
        
        if ($request->has('regions')) {
            $data['regions'] = [
                'north_america' => $request->boolean('regions.north_america', false),
                'europe' => $request->boolean('regions.europe', false),
                'asia' => $request->boolean('regions.asia', false),
                'other_regions' => $request->boolean('regions.other_regions', false),
            ];
        }
        
        // Update settings in database
        RegistrationSetting::updateSettings($data);
        
        return redirect()->route('admin.registration-control.index')
            ->with('success', 'Registration settings updated successfully!');
    }
}

