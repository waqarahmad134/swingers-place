<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function general(): View
    {
        $user = auth()->user();
        $settings = [
            'maintenance_mode' => config('app.maintenance_mode', false),
        ];

        return view('admin.settings.general', compact('settings', 'user'));
    }

    public function updateGeneral(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'maintenance_mode' => ['nullable', 'boolean'],
        ]);

        try {
            $user = auth()->user();
            
            // Update user profile if name/email provided
            if (isset($validated['name']) && $validated['name']) {
                $user->name = $validated['name'];
            }
            if (isset($validated['email']) && $validated['email']) {
                $user->email = $validated['email'];
            }
            $user->save();
            
            // Update maintenance mode in .env
            if (isset($validated['maintenance_mode'])) {
                $envPath = base_path('.env');
                if (File::exists($envPath)) {
                    $envContent = File::get($envPath);
                    $maintenanceMode = $request->has('maintenance_mode') ? 'true' : 'false';
                    $pattern = "/^APP_MAINTENANCE_MODE\s*=.*/m";
                    if (preg_match($pattern, $envContent)) {
                        $envContent = preg_replace($pattern, "APP_MAINTENANCE_MODE={$maintenanceMode}", $envContent);
                    } else {
                        $envContent = rtrim($envContent) . "\nAPP_MAINTENANCE_MODE={$maintenanceMode}";
                    }
                    File::put($envPath, $envContent);
                }
            }
            
            return redirect()->route('admin.settings.general')
                ->with('success', 'Settings saved successfully!');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.settings.general')
                ->withInput()
                ->with('error', 'Failed to save settings: ' . $e->getMessage());
        }
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = auth()->user();

        if (!Hash::check($validated['current_password'], $user->password)) {
            return redirect()->route('admin.settings.general')
                ->with('error', 'Current password is incorrect.');
        }

        $user->password = Hash::make($validated['password']);
        $user->save();

        return redirect()->route('admin.settings.general')
            ->with('success', 'Password updated successfully!');
    }

    public function robots(): View
    {
        $robotsPath = public_path('robots.txt');
        $content = File::exists($robotsPath) ? File::get($robotsPath) : '';

        return view('admin.settings.robots', compact('content'));
    }

    public function updateRobots(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'content' => ['required', 'string'],
        ]);

        $robotsPath = public_path('robots.txt');
        
        try {
            File::put($robotsPath, $validated['content']);
            
            return back()->with('success', 'robots.txt updated successfully');
        } catch (\Exception $e) {
            return back()->withErrors(['content' => 'Failed to update robots.txt: ' . $e->getMessage()]);
        }
    }
}
