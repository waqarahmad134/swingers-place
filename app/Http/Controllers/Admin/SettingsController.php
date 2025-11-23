<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function general(): View
    {
        $settings = [
            'site_name' => config('app.name'),
            'logo_url' => config('app.logo_url', null),
            'meta_description' => config('app.meta_description', null),
            'app_env' => config('app.env', 'local'),
            'app_debug' => config('app.debug', false),
            'maintenance_mode' => config('app.maintenance_mode', false),
        ];

        return view('admin.settings.general', compact('settings'));
    }

    public function updateGeneral(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'site_name' => ['required', 'string', 'max:255'],
            'logo_url' => ['nullable', 'string', 'max:1000'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'app_env' => ['required', 'string', 'in:local,production,staging,testing'],
            'app_debug' => ['nullable', 'boolean'],
            'maintenance_mode' => ['nullable', 'boolean'],
        ]);

        try {
            $envPath = base_path('.env');
            
            if (!File::exists($envPath)) {
                return redirect()->route('admin.settings.general')
                    ->with('error', '.env file not found. Please create it first.');
            }

            // Read the .env file
            $envContent = File::get($envPath);
            
            // Helper function to properly escape env values
            $escapeEnvValue = function($value) {
                // Remove any existing quotes
                $value = trim($value, '"\'');
                
                // If value contains spaces, quotes, or special chars, wrap in quotes
                if (preg_match('/[\s"\'#\$\\\\]/', $value)) {
                    // Escape backslashes and quotes
                    $value = str_replace('\\', '\\\\', $value);
                    $value = str_replace('"', '\\"', $value);
                    return '"' . $value . '"';
                }
                return $value;
            };
            
            // Helper function to update or add env variable
            $updateEnv = function($key, $value) use (&$envContent, $escapeEnvValue) {
                $escapedValue = $escapeEnvValue($value);
                
                // Pattern to match key at start of line (with optional spaces around =)
                $pattern = "/^{$key}\s*=.*/m";
                
                if (preg_match($pattern, $envContent)) {
                    $envContent = preg_replace($pattern, "{$key}={$escapedValue}", $envContent);
                } else {
                    // Add at the end if not found
                    $envContent = rtrim($envContent) . "\n{$key}={$escapedValue}";
                }
            };
            
            // Update APP_NAME (Site Name)
            $updateEnv('APP_NAME', $validated['site_name']);
            
            // Update APP_LOGO_URL
            if (!empty($validated['logo_url'])) {
                $updateEnv('APP_LOGO_URL', $validated['logo_url']);
            } else {
                // Remove if empty
                $envContent = preg_replace("/^APP_LOGO_URL\s*=.*\n?/m", '', $envContent);
            }
            
            // Update APP_META_DESCRIPTION
            if (!empty($validated['meta_description'])) {
                $updateEnv('APP_META_DESCRIPTION', $validated['meta_description']);
            } else {
                // Remove if empty
                $envContent = preg_replace("/^APP_META_DESCRIPTION\s*=.*\n?/m", '', $envContent);
            }
            
            // Update APP_ENV
            $updateEnv('APP_ENV', $validated['app_env']);
            
            // Update APP_DEBUG
            $appDebug = $request->has('app_debug') ? 'true' : 'false';
            $updateEnv('APP_DEBUG', $appDebug);
            
            // Update maintenance mode
            $maintenanceMode = $request->has('maintenance_mode') ? 'true' : 'false';
            $updateEnv('APP_MAINTENANCE_MODE', $maintenanceMode);

            // Clean up multiple consecutive newlines
            $envContent = preg_replace("/\n{3,}/", "\n\n", $envContent);
            
            // Write the file
            File::put($envPath, $envContent);
            
            // Clear config cache - use try-catch to prevent connection reset
            try {
                // Use a timeout to prevent hanging
                set_time_limit(5);
                Artisan::call('config:clear');
            } catch (\Exception $artisanException) {
                // If config:clear fails, we can still continue
                // The settings are saved, just need manual cache clear
                // Silently continue - settings are already saved
            }
            
            // Use redirect instead of back() to avoid potential issues
            return redirect()->route('admin.settings.general')
                ->with('success', 'Settings saved successfully!');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.settings.general')
                ->withInput()
                ->with('error', 'Failed to save settings: ' . $e->getMessage());
        }
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

