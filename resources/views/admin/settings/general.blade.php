@extends('layouts.admin')

@section('title', 'Settings - Admin Panel')
@section('page-title', 'Settings')

@section('content')
    <div class="pt-[14px] pb-8">
        <h2 class="text-[#0A0A0A] text-[24px] font-medium font-['poppins']">Settings</h2>
        <p class="text-[#717182] font-['poppins']">Manage admin profile and system settings</p>
    </div>

    <!-- Admin Profile Section -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 mb-6">
        <div class="flex items-center gap-3 mb-4">
            <i class="ri-user-line text-[#FF8FA3] text-xl"></i>
            <div>
                <h3 class="text-lg font-semibold text-[#0A0A0A]">Admin Profile</h3>
                <p class="text-sm text-[#717182]">Manage your admin account details</p>
            </div>
        </div>

        <form action="{{ route('admin.settings.general.update') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FF8FA3]">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FF8FA3]">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <input type="text" value="{{ $user->is_admin ? 'Super Admin' : 'Admin' }}" readonly class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                    <input type="text" value="Operations" readonly class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-500">
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="px-6 py-2 bg-[#FF8FA3] text-white rounded-lg hover:bg-[#FF7A91] transition-colors">
                    Save Profile Changes
                </button>
            </div>
        </form>
    </div>

    <!-- Management Toggles Section -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 mb-6">
        <h3 class="text-lg font-semibold text-[#0A0A0A] mb-4">Management Toggles</h3>
        <div class="space-y-4">
            @php
                $managementToggles = [
                    ['name' => 'role_management', 'title' => 'Role Management', 'description' => 'Configure admin roles and permissions.'],
                    ['name' => 'user_management', 'title' => 'User Management', 'description' => 'Edit, suspend, and delete users.'],
                    ['name' => 'content_moderation', 'title' => 'Content Moderation', 'description' => 'Review and approve content.'],
                    ['name' => 'reports_management', 'title' => 'Reports Management', 'description' => 'Handle user reports.'],
                    ['name' => 'system_settings', 'title' => 'System Settings', 'description' => 'Modify system configuration.'],
                    ['name' => 'analytics_access', 'title' => 'Analytics Access', 'description' => 'View analytics and reports.'],
                ];
            @endphp
            @foreach($managementToggles as $toggle)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                        <h4 class="font-semibold text-[#0A0A0A] mb-1">{{ $toggle['title'] }}</h4>
                        <p class="text-sm text-[#717182]">{{ $toggle['description'] }}</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="{{ $toggle['name'] }}" value="1" class="sr-only peer" disabled>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#FF8FA3]/30 rounded-full peer dark:bg-gray-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-[#FF8FA3]"></div>
                    </label>
                </div>
            @endforeach
        </div>
    </div>

    <!-- System Preferences Section -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 mb-6">
        <div class="flex items-center gap-3 mb-4">
            <i class="ri-settings-3-line text-[#FF8FA3] text-xl"></i>
            <div>
                <h3 class="text-lg font-semibold text-[#0A0A0A]">System Settings</h3>
                <p class="text-sm text-[#717182]">Configure global system preferences</p>
            </div>
        </div>

        <form action="{{ route('admin.settings.general.update') }}" method="POST" class="space-y-4">
            @csrf
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                        <h4 class="font-semibold text-[#0A0A0A] mb-1">Dark Mode</h4>
                        <p class="text-sm text-[#717182]">Enable dark theme for admin panel</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="dark_mode" value="1" class="sr-only peer" disabled>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#FF8FA3]/30 rounded-full peer dark:bg-gray-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-[#FF8FA3]"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                        <h4 class="font-semibold text-[#0A0A0A] mb-1">Global Messaging</h4>
                        <p class="text-sm text-[#717182]">Enable or disable messaging for all users</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="global_messaging_enabled" value="1" {{ old('global_messaging_enabled', $settings['global_messaging_enabled'] ?? true) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#FF8FA3]/30 rounded-full peer dark:bg-gray-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-[#FF8FA3]"></div>
                    </label>
                </div>

                @if(!($settings['global_messaging_enabled'] ?? true))
                    <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg flex items-center gap-3">
                        <i class="ri-error-warning-line text-yellow-600 text-xl"></i>
                        <p class="text-sm text-yellow-800">Global messaging is disabled. All users cannot send messages.</p>
                    </div>
                @endif

                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                        <h4 class="font-semibold text-[#0A0A0A] mb-1">Maintenance Mode</h4>
                        <p class="text-sm text-[#717182]">Temporarily disable user access</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="maintenance_mode" value="1" {{ old('maintenance_mode', $settings['maintenance_mode']) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#FF8FA3]/30 rounded-full peer dark:bg-gray-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-[#FF8FA3]"></div>
                    </label>
                </div>

                @if($settings['maintenance_mode'])
                    <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg flex items-center gap-3">
                        <i class="ri-error-warning-line text-yellow-600 text-xl"></i>
                        <p class="text-sm text-yellow-800">Maintenance mode is enabled. Users cannot access the application.</p>
                    </div>
                @endif
            </div>

            <div class="pt-4">
                <button type="submit" class="px-6 py-2 bg-[#FF8FA3] text-white rounded-lg hover:bg-[#FF7A91] transition-colors">
                    Save System Settings
                </button>
            </div>
        </form>
    </div>

    <!-- Notification Preferences Section -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 mb-6">
        <div class="flex items-center gap-3 mb-4">
            <i class="ri-notification-line text-[#FF8FA3] text-xl"></i>
            <div>
                <h3 class="text-lg font-semibold text-[#0A0A0A]">Notification Preferences</h3>
                <p class="text-sm text-[#717182]">Control admin notification settings</p>
            </div>
        </div>

        <div class="space-y-4">
            @php
                $notificationToggles = [
                    ['name' => 'push_notifications', 'title' => 'Push Notifications', 'description' => 'Receive browser notifications.'],
                    ['name' => 'email_notifications', 'title' => 'Email Notifications', 'description' => 'Receive email alerts.'],
                    ['name' => 'new_user_alerts', 'title' => 'New User Alerts', 'description' => 'Alert when new users register.'],
                    ['name' => 'report_alerts', 'title' => 'Report Alerts', 'description' => 'Alert when users are reported.'],
                    ['name' => 'verification_alerts', 'title' => 'Verification Alerts', 'description' => 'Alert for new verification submissions.'],
                ];
            @endphp
            @foreach($notificationToggles as $toggle)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                        <h4 class="font-semibold text-[#0A0A0A] mb-1">{{ $toggle['title'] }}</h4>
                        <p class="text-sm text-[#717182]">{{ $toggle['description'] }}</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="{{ $toggle['name'] }}" value="1" class="sr-only peer" disabled>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#FF8FA3]/30 rounded-full peer dark:bg-gray-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-[#FF8FA3]"></div>
                    </label>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Security Section -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-[#0A0A0A] mb-4">Security</h3>
        
        <div class="mb-4">
            <h4 class="font-semibold text-[#0A0A0A] mb-4">Change Password</h4>
            <form action="{{ route('admin.settings.password.update') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Current password</label>
                    <input type="password" name="current_password" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FF8FA3]">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">New password</label>
                    <input type="password" name="password" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FF8FA3]">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm new password</label>
                    <input type="password" name="password_confirmation" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FF8FA3]">
                </div>

                <div class="pt-4">
                    <button type="submit" class="px-6 py-2 bg-[#FF8FA3] text-white rounded-lg hover:bg-[#FF7A91] transition-colors">
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
