@extends('layouts.app')

@section('title', $user->name . ' - Profile - ' . config('app.name'))

@section('content')
<div class="min-h-screen">
    <!-- Main Content Area with Sidebar -->
    <div class="flex gap-0 w-full">
        <!-- Main Content -->
        <div id="main-content" class="flex-1 transition-all duration-300 ease-in-out min-w-0">
            <!-- Profile Tab Content -->
            <div id="tab-profile" class="tab-content">
                @include('pages.profile.tabs.profile', ['user' => $user, 'profile' => $profile, 'age' => $age, 'joinDate' => $joinDate, 'isOwnProfile' => $isOwnProfile ?? false])
            </div>
            
            <!-- Account Tab Content -->
            <div id="tab-account" class="tab-content hidden">
                <div class="py-8 px-4 sm:px-6 lg:px-8">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Account Settings</h1>
                    
                    @if(session('success'))
                        <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-700 dark:border-green-800 dark:bg-green-900/40 dark:text-green-300">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700 dark:border-red-800 dark:bg-red-900/40 dark:text-red-300">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form id="account-form" method="POST" action="{{ route('account.profile.update') }}" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <!-- Account Information -->
                        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Account Information</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Display Name</label>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Joining Date</label>
                                    <div class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white opacity-75 cursor-not-allowed">
                                        {{ $user->created_at->format('F j, Y') }}
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">This field cannot be changed</p>
                                </div>
                            </div>
                        </div>

                        <!-- Change Password -->
                        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                <i class="ri-lock-line"></i>
                                Change Password
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Leave blank if you don't want to change your password.</p>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Current Password</label>
                                    <input type="password" name="current_password" class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all" placeholder="Enter current password">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">New Password</label>
                                    <input type="password" name="password" class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all" placeholder="Enter new password">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Confirm New Password</label>
                                    <input type="password" name="password_confirmation" class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all" placeholder="Confirm new password">
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end gap-3 mb-6">
                            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-[linear-gradient(90deg,#9810FA_0%,#E60076_100%)] text-white text-base font-semibold rounded-full hover:shadow-lg hover:scale-[1.02] transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:ring-offset-2">
                                <i class="ri-save-line"></i>
                                Save Changes
                            </button>
                        </div>
                    </form>

                    <!-- Danger Zone -->
                    <div class="bg-red-50 dark:bg-red-900/20 rounded-2xl p-6 shadow-sm border-2 border-red-200 dark:border-red-800">
                        <h3 class="text-lg font-bold text-red-900 dark:text-red-300 mb-2 flex items-center gap-2">
                            <i class="ri-delete-bin-line"></i>
                            Danger Zone
                        </h3>
                        <p class="text-sm text-red-700 dark:text-red-400 mb-4">Once you delete your account, there is no going back. Please be certain.</p>
                        <div class="flex gap-2">
                            <input type="text" id="delete-confirm-input" placeholder="Type DELETE to confirm" class="flex-1 rounded-lg border border-red-300 dark:border-red-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                            <button type="button" id="delete-account-btn" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg flex items-center gap-2 transition-colors disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                                <i class="ri-delete-bin-line"></i>
                                Delete Account
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Location Tab Content -->
            <div id="tab-location" class="tab-content hidden">
                <div class="px-4 sm:px-6 lg:px-8">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Location Settings</h1>
                    
                    <form id="location-form" method="POST" action="{{ route('account.profile.update') }}" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <!-- Hidden fields for required validation -->
                        <input type="hidden" name="name" value="{{ $user->name }}">
                        <input type="hidden" name="email" value="{{ $user->email }}">
                        
                        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                            <div class="space-y-4">
                                <!-- Home Location -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Home Location
                                    </label>
                                    <input type="text" id="profile-home_location" name="home_location" 
                                           value="{{ $profile->home_location ?? '' }}"
                                           placeholder="Search for your city..."
                                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                                    <input type="hidden" id="profile-home_location_lat" name="latitude" value="{{ $profile->latitude ?? '' }}">
                                    <input type="hidden" id="profile-home_location_lng" name="longitude" value="{{ $profile->longitude ?? '' }}">
                                </div>

                                <!-- Country and City Fields -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Country -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Country
                                        </label>
                                        <input type="text" id="profile-country" name="country" 
                                               value="{{ $profile->country ?? '' }}"
                                               placeholder="Country will auto-fill from location..."
                                               class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                                    </div>

                                    <!-- City -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            City
                                        </label>
                                        <input type="text" id="profile-city" name="city" 
                                               value="{{ $profile->city ?? '' }}"
                                               placeholder="City will auto-fill from location..."
                                               class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                                    </div>
                                </div>

                                <!-- Map Display -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Location Map
                                    </label>
                                    <div id="profile-map" class="rounded-xl h-64 w-full border border-gray-200 dark:border-gray-600" style="display: {{ ($profile && $profile->latitude && $profile->longitude) ? 'block' : 'none' }};"></div>
                                    <div id="profile-map-placeholder" class="bg-gray-100 dark:bg-gray-700 rounded-xl h-64 flex items-center justify-center border-2 border-dashed border-gray-300 dark:border-gray-600" style="display: {{ ($profile && $profile->latitude && $profile->longitude) ? 'none' : 'flex' }};">
                                        <div class="text-center">
                                            <i class="ri-map-pin-2-line text-4xl text-gray-400 mb-2"></i>
                                            <p class="text-gray-500 dark:text-gray-400 text-sm">Map will appear when you select a location</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Save Button -->
                            <div class="mt-6">
                                <button type="submit" class="w-full py-3.5 px-4 bg-[linear-gradient(90deg,#9810FA_0%,#E60076_100%)] text-white text-base font-semibold rounded-full hover:shadow-lg hover:scale-[1.02] transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:ring-offset-2">
                                    Save Location
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Preferences Tab Content -->
            <div id="tab-preferences" class="tab-content hidden">
                <div class="py-8 px-4 sm:px-6 lg:px-8">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Preferences</h1>
                    
                    @if(session('success'))
                        <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-700 dark:border-green-800 dark:bg-green-900/40 dark:text-green-300">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700 dark:border-red-800 dark:bg-red-900/40 dark:text-red-300">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form id="preferences-form" method="POST" action="{{ route('account.profile.update') }}" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <!-- Hidden fields for required validation -->
                        <input type="hidden" name="name" value="{{ $user->name }}">
                        <input type="hidden" name="email" value="{{ $user->email }}">
                        
                        <!-- What Are You Looking For -->
                        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">What Are You Looking For?</h3>
                            <div class="space-y-3">
                                @php
                                    $preferenceOptions = ['full_swap', 'exhibitionist', 'still_exploring', 'others', 'soft_swap', 'voyeur', 'hotwife'];
                                @endphp
                                @foreach($preferenceOptions as $option)
                                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                        <label class="text-gray-900 dark:text-white font-medium capitalize">{{ str_replace('_', ' ', $option) }}</label>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="preferences[]" value="{{ $option }}" {{ in_array($option, $preferences ?? []) ? 'checked' : '' }} class="sr-only peer">
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 dark:peer-focus:ring-purple-800 rounded-full peer dark:bg-gray-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-purple-600"></div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Languages -->
                        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                <i class="ri-global-line text-[#9810FA]"></i>
                                Languages
                            </h3>
                            <div class="space-y-3">
                                @php
                                    $languageOptions = ['english', 'spanish', 'french', 'german', 'italian', 'portuguese'];
                                @endphp
                                @foreach($languageOptions as $lang)
                                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                        <label class="text-gray-900 dark:text-white font-medium capitalize">{{ $lang }}</label>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="languages[]" value="{{ $lang }}" {{ in_array($lang, $languages ?? []) ? 'checked' : '' }} class="sr-only peer">
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 dark:peer-focus:ring-purple-800 rounded-full peer dark:bg-gray-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-purple-600"></div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Additional Details -->
                        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Additional Details</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Sexuality</label>
                                    <select name="sexuality" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                        <option value="">Select</option>
                                        <option value="straight" {{ ($profile && $profile->sexuality === 'straight') ? 'selected' : '' }}>Straight</option>
                                        <option value="gay" {{ ($profile && $profile->sexuality === 'gay') ? 'selected' : '' }}>Gay</option>
                                        <option value="bisexual" {{ ($profile && $profile->sexuality === 'bisexual') ? 'selected' : '' }}>Bisexual</option>
                                        <option value="lesbian" {{ ($profile && $profile->sexuality === 'lesbian') ? 'selected' : '' }}>Lesbian</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Relationship Status</label>
                                    <select name="relationship_status" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                        <option value="">Select</option>
                                        <option value="single" {{ ($profile && $profile->relationship_status === 'single') ? 'selected' : '' }}>Single</option>
                                        <option value="in_relationship" {{ ($profile && $profile->relationship_status === 'in_relationship') ? 'selected' : '' }}>In a Relationship</option>
                                        <option value="married" {{ ($profile && $profile->relationship_status === 'married') ? 'selected' : '' }}>Married</option>
                                        <option value="open" {{ ($profile && $profile->relationship_status === 'open') ? 'selected' : '' }}>Open</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end gap-3 mb-6">
                            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-[linear-gradient(90deg,#9810FA_0%,#E60076_100%)] text-white text-base font-semibold rounded-full hover:shadow-lg hover:scale-[1.02] transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:ring-offset-2">
                                <i class="ri-save-line"></i>
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Privacy Tab Content -->
            <div id="tab-privacy" class="tab-content hidden">
                <div class="py-8 px-4 sm:px-6 lg:px-8">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Privacy Settings</h1>
                    
                    @if(session('success'))
                        <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-700 dark:border-green-800 dark:bg-green-900/40 dark:text-green-300">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700 dark:border-red-800 dark:bg-red-900/40 dark:text-red-300">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form id="privacy-form" method="POST" action="{{ route('account.profile.update') }}" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <!-- Hidden fields for required validation -->
                        <input type="hidden" name="name" value="{{ $user->name }}">
                        <input type="hidden" name="email" value="{{ $user->email }}">
                        
                        <!-- Privacy Settings -->
                        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Privacy Settings</h3>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                    <div>
                                        <label class="text-gray-900 dark:text-white font-medium">Show my profile to everyone</label>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Your profile will be visible to all members</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="profile_visible" value="1" {{ ($profile && $profile->profile_visible) ? 'checked' : '' }} class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 dark:peer-focus:ring-purple-800 rounded-full peer dark:bg-gray-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-purple-600"></div>
                                    </label>
                                </div>
                                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                    <div>
                                        <label class="text-gray-900 dark:text-white font-medium">Allow wall posts</label>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Let others post on your wall</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="allow_wall_posts" value="1" {{ ($profile && $profile->allow_wall_posts) ? 'checked' : '' }} class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 dark:peer-focus:ring-purple-800 rounded-full peer dark:bg-gray-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-purple-600"></div>
                                    </label>
                                </div>
                                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                    <div>
                                        <label class="text-gray-900 dark:text-white font-medium">Show online status</label>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Display when you're online</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="show_online_status" value="1" {{ ($profile && $profile->show_online_status) ? 'checked' : '' }} class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 dark:peer-focus:ring-purple-800 rounded-full peer dark:bg-gray-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-purple-600"></div>
                                    </label>
                                </div>
                                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                    <div>
                                        <label class="text-gray-900 dark:text-white font-medium">Show last active</label>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Display when you were last active</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="show_last_active" value="1" {{ ($profile && $profile->show_last_active ?? true) ? 'checked' : '' }} class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 dark:peer-focus:ring-purple-800 rounded-full peer dark:bg-gray-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-purple-600"></div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Who Can See -->
                        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                <i class="ri-eye-line text-[#9810FA]"></i>
                                Who Can See...
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">My photos</label>
                                    <select name="photo_visibility" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                        <option value="everyone" selected>Everyone</option>
                                        <option value="friends">Friends</option>
                                        <option value="private">Private</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">My videos</label>
                                    <select name="video_visibility" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                        <option value="everyone" selected>Everyone</option>
                                        <option value="friends">Friends</option>
                                        <option value="private">Private</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">My friends list</label>
                                    <select name="friends_list_visibility" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                        <option value="everyone" selected>Everyone</option>
                                        <option value="friends">Friends</option>
                                        <option value="private">Private</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">My events</label>
                                    <select name="events_visibility" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                        <option value="everyone" selected>Everyone</option>
                                        <option value="friends">Friends</option>
                                        <option value="private">Private</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end gap-3 mb-6">
                            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-[linear-gradient(90deg,#9810FA_0%,#E60076_100%)] text-white text-base font-semibold rounded-full hover:shadow-lg hover:scale-[1.02] transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:ring-offset-2">
                                <i class="ri-save-line"></i>
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Settings Sidebar (Right Side) -->
        <div id="settings-sidebar" class="min-h-screen bg-white dark:bg-gray-800 border-l border-gray-200 dark:border-gray-700 overflow-y-auto shadow-2xl transition-all duration-300 ease-in-out" style="width: 0; min-width: 0; overflow: hidden;">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Settings</h2>
                    <button id="close-sidebar" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                        <i class="ri-close-line text-2xl"></i>
                    </button>
                </div>
                
                <nav class="space-y-2">
                    <button data-tab="profile" class="settings-tab w-full flex items-center gap-3 px-4 py-3 rounded-lg text-left transition-colors bg-[#9810FA] text-white">
                        <i class="ri-user-line text-xl"></i>
                        <span class="font-semibold">Profile</span>
                    </button>
                    
                    <button data-tab="account" class="settings-tab w-full flex items-center gap-3 px-4 py-3 rounded-lg text-left transition-colors text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="ri-account-box-line text-xl"></i>
                        <span class="font-semibold">Account</span>
                    </button>
                    
                    <button data-tab="location" class="settings-tab w-full flex items-center gap-3 px-4 py-3 rounded-lg text-left transition-colors text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="ri-map-pin-line text-xl"></i>
                        <span class="font-semibold">Location</span>
                    </button>
                    
                    <button data-tab="preferences" class="settings-tab w-full flex items-center gap-3 px-4 py-3 rounded-lg text-left transition-colors text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="ri-heart-line text-xl"></i>
                        <span class="font-semibold">Preferences</span>
                    </button>
                    
                    <button data-tab="privacy" class="settings-tab w-full flex items-center gap-3 px-4 py-3 rounded-lg text-left transition-colors text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="ri-shield-line text-xl"></i>
                        <span class="font-semibold">Privacy</span>
                    </button>
                </nav>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('settings-sidebar');
    const mainContent = document.getElementById('main-content');
    const closeBtn = document.getElementById('close-sidebar');
    const settingsToggleBtn = document.getElementById('settings-toggle-btn');
    const tabButtons = document.querySelectorAll('.settings-tab');
    const tabContents = document.querySelectorAll('.tab-content');
    
    // Check if sidebar is open
    function isSidebarOpen() {
        return sidebar && sidebar.style.width !== '0px' && sidebar.style.width !== '0';
    }
    
    // Open sidebar function
    function openSidebar() {
        if (sidebar) {
            sidebar.style.width = '320px'; // w-80 = 320px
            sidebar.style.minWidth = '320px';
            sidebar.style.overflow = 'auto';
        }
    }
    
    // Close sidebar function
    function closeSidebar() {
        if (sidebar) {
            sidebar.style.width = '0';
            sidebar.style.minWidth = '0';
            sidebar.style.overflow = 'hidden';
        }
    }
    
    // Toggle sidebar function
    function toggleSidebar() {
        if (isSidebarOpen()) {
            closeSidebar();
        } else {
            openSidebar();
        }
    }
    
    // Initialize sidebar - already set in HTML
    
    // Check if we should open sidebar (from settings icon via URL)
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('settings') === 'true') {
        openSidebar();
        // Clean up URL
        const newUrl = window.location.pathname;
        window.history.replaceState({}, '', newUrl);
    }
    
    // Close sidebar handlers
    if (closeBtn) {
        closeBtn.addEventListener('click', closeSidebar);
    }
    
    // Tab switching
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const tabName = this.getAttribute('data-tab');
            
            // Update active tab button
            tabButtons.forEach(btn => {
                btn.classList.remove('bg-[#9810FA]', 'text-white');
                btn.classList.add('text-gray-700', 'dark:text-gray-300', 'hover:bg-gray-100', 'dark:hover:bg-gray-700');
            });
            this.classList.add('bg-[#9810FA]', 'text-white');
            this.classList.remove('text-gray-700', 'dark:text-gray-300', 'hover:bg-gray-100', 'dark:hover:bg-gray-700');
            
            // Show corresponding content
            tabContents.forEach(content => {
                content.classList.add('hidden');
            });
            const targetTab = document.getElementById('tab-' + tabName);
            if (targetTab) {
                targetTab.classList.remove('hidden');
            }
        });
    });
    
    // ESC key to close sidebar
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && isSidebarOpen()) {
            closeSidebar();
        }
    });
    
    // Make toggle function globally available
    window.toggleSettingsSidebar = toggleSidebar;
    window.openSettingsSidebar = openSidebar;
    window.closeSettingsSidebar = closeSidebar;
    
    // Account deletion confirmation
    const deleteConfirmInput = document.getElementById('delete-confirm-input');
    const deleteAccountBtn = document.getElementById('delete-account-btn');
    
    if (deleteConfirmInput && deleteAccountBtn) {
        deleteConfirmInput.addEventListener('input', function() {
            if (this.value.trim().toUpperCase() === 'DELETE') {
                deleteAccountBtn.disabled = false;
            } else {
                deleteAccountBtn.disabled = true;
            }
        });
        
        deleteAccountBtn.addEventListener('click', function() {
            if (this.disabled) return;
            
            if (confirm('Are you absolutely sure you want to delete your account? This action cannot be undone. All your data will be permanently deleted.')) {
                // Create a form to submit DELETE request
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("account.profile.delete") }}';
                
                // Add CSRF token
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);
                
                // Add method spoofing for DELETE
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);
                
                // Append form to body and submit
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
});
</script>

<!-- Google Maps Script for Location Tab -->
@php
    $googleMapsApiKey = config('services.google_maps.api_key');
@endphp
@if($googleMapsApiKey)
<script>
let profileMap;
let profileMarker;
let profileHomeLocationAutocomplete;

function extractCountryAndCityForProfile(addressComponents) {
    let country = '';
    let city = '';
    
    if (addressComponents) {
        for (let component of addressComponents) {
            const types = component.types;
            
            // Extract country
            if (types.includes('country')) {
                country = component.long_name;
            }
            
            // Extract city - try multiple types in order of preference
            if (!city && types.includes('locality')) {
                city = component.long_name;
            } else if (!city && types.includes('administrative_area_level_2')) {
                city = component.long_name;
            } else if (!city && types.includes('administrative_area_level_1')) {
                city = component.long_name;
            } else if (!city && types.includes('sublocality')) {
                city = component.long_name;
            }
        }
    }
    
    // Update country and city fields
    const countryInput = document.getElementById('profile-country');
    const cityInput = document.getElementById('profile-city');
    if (countryInput) countryInput.value = country;
    if (cityInput) cityInput.value = city;
}

function initProfileGoogleMaps() {
    // Initialize Home Location Autocomplete
    const homeLocationInput = document.getElementById('profile-home_location');
    if (homeLocationInput && typeof google !== 'undefined' && google.maps && google.maps.places) {
        try {
            profileHomeLocationAutocomplete = new google.maps.places.Autocomplete(homeLocationInput, {
                types: ['(cities)'],
                fields: ['formatted_address', 'geometry', 'name', 'address_components']
            });

            profileHomeLocationAutocomplete.addListener('place_changed', function() {
                const place = profileHomeLocationAutocomplete.getPlace();
                if (!place.geometry) {
                    return;
                }

                document.getElementById('profile-home_location_lat').value = place.geometry.location.lat();
                document.getElementById('profile-home_location_lng').value = place.geometry.location.lng();
                homeLocationInput.value = place.formatted_address || place.name;

                // Extract country and city from address components
                extractCountryAndCityForProfile(place.address_components);

                if (!profileMap) {
                    initProfileMap(place.geometry.location);
                } else {
                    profileMap.setCenter(place.geometry.location);
                    profileMarker.setPosition(place.geometry.location);
                }

                document.getElementById('profile-map-placeholder').style.display = 'none';
                document.getElementById('profile-map').style.display = 'block';
            });
        } catch (error) {
            console.error('Error initializing autocomplete:', error);
        }
    }
}

function initProfileMap(location) {
    const mapElement = document.getElementById('profile-map');
    if (!mapElement) return;

    profileMap = new google.maps.Map(mapElement, {
        center: location || { lat: 0, lng: 0 },
        zoom: 12,
        mapTypeControl: false,
        streetViewControl: false,
        fullscreenControl: false
    });

    profileMarker = new google.maps.Marker({
        map: profileMap,
        position: location || { lat: 0, lng: 0 },
        draggable: true,
        animation: google.maps.Animation.DROP
    });

    profileMarker.addListener('dragend', function() {
        const position = profileMarker.getPosition();
        document.getElementById('profile-home_location_lat').value = position.lat();
        document.getElementById('profile-home_location_lng').value = position.lng();
        
        const geocoder = new google.maps.Geocoder();
        geocoder.geocode({ location: position }, (results, status) => {
            if (status === 'OK' && results[0]) {
                document.getElementById('profile-home_location').value = results[0].formatted_address;
                // Extract country and city from geocoded results
                extractCountryAndCityForProfile(results[0].address_components);
            }
        });
    });
}

// Load Google Maps API with proper async loading and callback
(function() {
    // Check if script already exists
    if (document.querySelector('script[src*="maps.googleapis.com"]')) {
        // Script already loaded, initialize directly
        if (typeof google !== 'undefined' && google.maps) {
            initProfileGoogleMaps();
            // Initialize map if location exists
            @if($profile && $profile->latitude && $profile->longitude)
                initProfileMap({ lat: {{ $profile->latitude }}, lng: {{ $profile->longitude }} });
            @endif
        }
    } else {
        const script = document.createElement('script');
        script.src = 'https://maps.googleapis.com/maps/api/js?key={{ $googleMapsApiKey }}&loading=async&libraries=places&callback=initProfileMapCallback';
        script.async = true;
        script.defer = true;
        document.head.appendChild(script);
    }
})();

// Callback function for when Google Maps loads
window.initProfileMapCallback = function() {
    if (typeof google !== 'undefined' && google.maps) {
        initProfileGoogleMaps();
        // Initialize map if location exists
        @if($profile && $profile->latitude && $profile->longitude)
            initProfileMap({ lat: {{ $profile->latitude }}, lng: {{ $profile->longitude }} });
        @endif
    }
};
</script>
@else
<script>
console.warn('Google Maps API key is not configured. Please add GOOGLE_MAPS_API_KEY to your .env file.');
</script>
@endif
@endpush
@endsection
