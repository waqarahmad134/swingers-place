@extends('layouts.admin')

@section('title', 'Registration Control - Admin Panel')
@section('page-title', 'Registration Control')

@section('content')
    <div class="pt-[14px] pb-8">
        <h2 class="text-[#0A0A0A] text-[24px] font-medium font-['poppins']">Registration Control</h2>
        <p class="text-[#717182] font-['poppins']">Manage new user registrations and settings</p>
    </div>

    <form action="{{ route('admin.registration-control.update') }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Top Row of Control Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Open Registration Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                        <i class="ri-close-circle-line text-red-600 text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-[#0A0A0A] mb-1">Open Registration</h3>
                        <p class="text-sm text-[#717182] mb-4">Allow new users to sign up freely</p>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="open_registration" value="1" {{ $settings['open_registration'] ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#FF8FA3]/30 rounded-full peer dark:bg-gray-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-[#FF8FA3]"></div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Email Verification Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-full bg-[#FFF5F7] flex items-center justify-center flex-shrink-0">
                        <i class="ri-mail-line text-[#FF8FA3] text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-[#0A0A0A] mb-1">Email Verification</h3>
                        <p class="text-sm text-[#717182] mb-4">Require users to verify email</p>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="email_verification" value="1" {{ $settings['email_verification'] ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#FF8FA3]/30 rounded-full peer dark:bg-gray-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-[#FF8FA3]"></div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Admin Approval Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-full bg-[#FFF5F7] flex items-center justify-center flex-shrink-0">
                        <i class="ri-shield-user-line text-[#FF8FA3] text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-[#0A0A0A] mb-1">Admin Approval</h3>
                        <p class="text-sm text-[#717182] mb-4">Manually approve new users</p>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="admin_approval" value="1" {{ $settings['admin_approval'] ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#FF8FA3]/30 rounded-full peer dark:bg-gray-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-[#FF8FA3]"></div>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Current Status Section -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-[#0A0A0A] mb-2">Current Status</h3>
                    <p class="text-sm text-[#717182]">New registrations are currently {{ $settings['current_status'] === 'open' ? 'enabled' : 'disabled' }}</p>
                </div>
                <button type="button" class="px-6 py-2 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors">
                    {{ ucfirst($settings['current_status']) }}
                </button>
            </div>
        </div>

        <!-- Regional Controls Section -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-[#0A0A0A] mb-2">Regional Controls (Optional)</h3>
            <p class="text-sm text-[#717182] mb-6">Control which regions can register on your platform</p>
            
            <div class="space-y-4">
                <!-- North America -->
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                        <h4 class="font-semibold text-[#0A0A0A] mb-1">North America</h4>
                        <p class="text-sm text-[#717182]">Allow registrations from this region</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="regions[north_america]" value="1" {{ $settings['regions']['north_america'] ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#FF8FA3]/30 rounded-full peer dark:bg-gray-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-[#FF8FA3]"></div>
                    </label>
                </div>

                <!-- Europe -->
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                        <h4 class="font-semibold text-[#0A0A0A] mb-1">Europe</h4>
                        <p class="text-sm text-[#717182]">Allow registrations from this region</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="regions[europe]" value="1" {{ $settings['regions']['europe'] ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#FF8FA3]/30 rounded-full peer dark:bg-gray-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-[#FF8FA3]"></div>
                    </label>
                </div>

                <!-- Asia -->
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                        <h4 class="font-semibold text-[#0A0A0A] mb-1">Asia</h4>
                        <p class="text-sm text-[#717182]">Allow registrations from this region</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="regions[asia]" value="1" {{ $settings['regions']['asia'] ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#FF8FA3]/30 rounded-full peer dark:bg-gray-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-[#FF8FA3]"></div>
                    </label>
                </div>

                <!-- Other Regions -->
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                        <h4 class="font-semibold text-[#0A0A0A] mb-1">Other Regions</h4>
                        <p class="text-sm text-[#717182]">Allow registrations from other regions</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="regions[other_regions]" value="1" {{ $settings['regions']['other_regions'] ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#FF8FA3]/30 rounded-full peer dark:bg-gray-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-[#FF8FA3]"></div>
                    </label>
                </div>
            </div>
        </div>

        <!-- Save Button -->
        <div class="mt-8 flex justify-end">
            <button type="submit" class="px-6 py-2 bg-[#FF8FA3] text-white rounded-lg font-medium hover:bg-[#FF7A91] transition-colors">
                Save Changes
            </button>
        </div>
    </form>
@endsection

