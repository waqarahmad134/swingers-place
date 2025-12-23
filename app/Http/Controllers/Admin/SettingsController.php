<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MessageSetting;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function general(): View
    {
        $user = Auth::user();
        $messageSettings = MessageSetting::getSettings();
        $settings = [
            'maintenance_mode' => config('app.maintenance_mode', false),
            'global_messaging_enabled' => $messageSettings->global_messaging_enabled,
        ];

        return view('admin.settings.general', compact('settings', 'user'));
    }

    public function updateGeneral(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'maintenance_mode' => ['nullable', 'boolean'],
            'global_messaging_enabled' => ['nullable', 'boolean'],
        ]);

        try {
            $user = Auth::user();
            
            if ($user) {
                // Update user profile if name/email provided
                if (isset($validated['name']) && $validated['name']) {
                    $user->name = $validated['name'];
                }
                if (isset($validated['email']) && $validated['email']) {
                    $user->email = $validated['email'];
                }
                $user->save();
            }
            
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
            
            // Update global messaging setting
            if (isset($validated['global_messaging_enabled'])) {
                MessageSetting::updateSettings([
                    'global_messaging_enabled' => $request->boolean('global_messaging_enabled'),
                ]);
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

        $user = Auth::user();

        if (!$user || !Hash::check($validated['current_password'], $user->password)) {
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

    public function website(): View
    {
        $settings = SiteSetting::getSettings();
        return view('admin.settings.website', compact('settings'));
    }

    public function updateWebsite(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'site_title' => ['nullable', 'string', 'max:255'],
            'site_description' => ['nullable', 'string', 'max:500'],
            'site_keywords' => ['nullable', 'string', 'max:1000'],
            'site_icon' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,ico,svg', 'max:2048'],
            'site_favicon' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,ico,svg', 'max:2048'],
            'og_title' => ['nullable', 'string', 'max:255'],
            'og_description' => ['nullable', 'string', 'max:500'],
            'og_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:5120'],
            'og_site_name' => ['nullable', 'string', 'max:255'],
            'og_type' => ['nullable', 'string', 'max:50'],
            'og_url' => ['nullable', 'url', 'max:500'],
            'twitter_card_type' => ['nullable', 'string', 'in:summary,summary_large_image'],
            'twitter_title' => ['nullable', 'string', 'max:255'],
            'twitter_description' => ['nullable', 'string', 'max:500'],
            'twitter_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:5120'],
            'header_scripts' => ['nullable', 'string'],
            'footer_scripts' => ['nullable', 'string'],
            'custom_css' => ['nullable', 'string'],
            'custom_js' => ['nullable', 'string'],
        ]);

        try {
            $settings = SiteSetting::getSettings();
            
            // Handle site icon upload
            if ($request->hasFile('site_icon')) {
                $icon = $request->file('site_icon');
                $iconName = 'icon_' . time() . '_' . Str::random(10) . '.' . $icon->getClientOriginalExtension();
                $iconPath = 'images/site/' . $iconName;
                
                $uploadPath = public_path('images/site');
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0755, true);
                }
                
                // Delete old icon if exists
                if ($settings->site_icon && File::exists(public_path($settings->site_icon))) {
                    File::delete(public_path($settings->site_icon));
                }
                
                $icon->move($uploadPath, $iconName);
                $validated['site_icon'] = $iconPath;
            } else {
                unset($validated['site_icon']);
            }

            // Handle favicon upload
            if ($request->hasFile('site_favicon')) {
                $favicon = $request->file('site_favicon');
                $faviconName = 'favicon_' . time() . '_' . Str::random(10) . '.' . $favicon->getClientOriginalExtension();
                $faviconPath = 'images/site/' . $faviconName;
                
                $uploadPath = public_path('images/site');
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0755, true);
                }
                
                // Delete old favicon if exists
                if ($settings->site_favicon && File::exists(public_path($settings->site_favicon))) {
                    File::delete(public_path($settings->site_favicon));
                }
                
                $favicon->move($uploadPath, $faviconName);
                $validated['site_favicon'] = $faviconPath;
            } else {
                unset($validated['site_favicon']);
            }

            // Handle OG image upload
            if ($request->hasFile('og_image')) {
                $image = $request->file('og_image');
                $imageName = 'og_' . time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $imagePath = 'images/site/' . $imageName;
                
                $uploadPath = public_path('images/site');
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0755, true);
                }
                
                // Delete old OG image if exists
                if ($settings->og_image && File::exists(public_path($settings->og_image))) {
                    File::delete(public_path($settings->og_image));
                }
                
                $image->move($uploadPath, $imageName);
                $validated['og_image'] = $imagePath;
            } else {
                unset($validated['og_image']);
            }

            // Handle Twitter image upload
            if ($request->hasFile('twitter_image')) {
                $image = $request->file('twitter_image');
                $imageName = 'twitter_' . time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $imagePath = 'images/site/' . $imageName;
                
                $uploadPath = public_path('images/site');
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0755, true);
                }
                
                // Delete old Twitter image if exists
                if ($settings->twitter_image && File::exists(public_path($settings->twitter_image))) {
                    File::delete(public_path($settings->twitter_image));
                }
                
                $image->move($uploadPath, $imageName);
                $validated['twitter_image'] = $imagePath;
            } else {
                unset($validated['twitter_image']);
            }

            SiteSetting::updateSettings($validated);
            
            return redirect()->route('admin.settings.website')
                ->with('success', 'Website settings updated successfully!');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.settings.website')
                ->withInput()
                ->with('error', 'Failed to update website settings: ' . $e->getMessage());
        }
    }
}
