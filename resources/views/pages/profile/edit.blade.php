@extends('layouts.app')

@section('title', 'Edit Profile - ' . config('app.name'))

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('success'))
            <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-700 dark:border-green-800 dark:bg-green-900/40 dark:text-green-300">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Left Panel - Profile Picture -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="relative inline-block">
                        @if($user->profile_image)
                            <img src="{{ asset('storage/' . $user->profile_image) }}" alt="{{ $user->name }}" class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-lg">
                        @else
                            <div class="w-32 h-32 rounded-full bg-gradient-to-br from-purple-400 to-pink-400 flex items-center justify-center text-white text-3xl font-bold border-4 border-white shadow-lg">
                                {{ strtoupper(substr($user->first_name ?? $user->name, 0, 1)) }}{{ strtoupper(substr($user->last_name ?? '', 0, 1)) }}
                            </div>
                        @endif
                        <!-- Change Profile Picture Button -->
                        <label for="profile_image" class="absolute bottom-0 right-0 w-10 h-10 bg-purple-600 rounded-full flex items-center justify-center cursor-pointer hover:bg-purple-700 transition-colors shadow-lg">
                            <i class="ri-camera-line text-white text-lg"></i>
                            <input type="file" id="profile_image" name="profile_image" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp" class="hidden">
                        </label>
                    </div>
                    
                    <!-- Change Cover Photo Button -->
                    <label for="cover_photo" class="mt-4 w-full bg-purple-600 text-white py-2 px-4 rounded-lg flex items-center justify-center gap-2 cursor-pointer hover:bg-purple-700 transition-colors">
                        <i class="ri-camera-line"></i>
                        <span>Change Cover Photo</span>
                        <input type="file" id="cover_photo" name="cover_photo" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp" class="hidden">
                    </label>
                </div>
            </div>

            <!-- Right Panel - Main Content -->
            <div class="lg:col-span-3">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <!-- Tabs -->
                    <div class="border-b border-gray-200 dark:border-gray-700">
                        <div class="flex space-x-1 p-2">
                            <button onclick="switchTab('account')" id="tab-account" class="tab-button active px-6 py-3 text-sm font-semibold rounded-lg transition-colors bg-gray-800 dark:bg-gray-700 text-white">
                                <i class="ri-user-line mr-2"></i>
                                Account
                            </button>
                            <button onclick="switchTab('preferences')" id="tab-preferences" class="tab-button px-6 py-3 text-sm font-semibold rounded-lg transition-colors text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i class="ri-heart-line mr-2"></i>
                                Preferences
                            </button>
                            <button onclick="switchTab('privacy')" id="tab-privacy" class="tab-button px-6 py-3 text-sm font-semibold rounded-lg transition-colors text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i class="ri-shield-line mr-2"></i>
                                Privacy
                            </button>
                        </div>
                    </div>

                    <!-- Tab Content -->
                    <div class="p-6">
                        <form id="profile-form" action="{{ route('account.profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Account Tab -->
                            <div id="content-account" class="tab-content">
                                <!-- Account Type -->
                                <div class="mb-8">
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Account Type</h3>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Current Account Type</label>
                                        <select name="category" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                            <option value="single_male" {{ ($profile && $profile->category === 'single_male') ? 'selected' : '' }}>Single Male</option>
                                            <option value="single_female" {{ ($profile && $profile->category === 'single_female') ? 'selected' : '' }}>Single Female</option>
                                            <option value="couple" {{ ($profile && $profile->category === 'couple') ? 'selected' : '' }}>Couple</option>
                                            <option value="transgender" {{ ($profile && $profile->category === 'transgender') ? 'selected' : '' }}>Transgender</option>
                                            <option value="group" {{ ($profile && $profile->category === 'group') ? 'selected' : '' }}>Group</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Basic Information -->
                                <div class="mb-8">
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Basic Information</h3>
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Display Name</label>
                                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
                                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date of Birth</label>
                                            <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $profile && $profile->date_of_birth ? $profile->date_of_birth->format('Y-m-d') : '') }}" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Gender</label>
                                            <select name="gender" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                                <option value="">Select Gender</option>
                                                <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>Male</option>
                                                <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>Female</option>
                                                <option value="other" {{ old('gender', $user->gender) === 'other' ? 'selected' : '' }}>Other</option>
                                                <option value="prefer_not_to_say" {{ old('gender', $user->gender) === 'prefer_not_to_say' ? 'selected' : '' }}>Prefer not to say</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bio</label>
                                            <textarea name="bio" rows="4" placeholder="Tell us about yourself..." class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">{{ old('bio', $profile && $profile->bio ? $profile->bio : '') }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Location -->
                                <div class="mb-8">
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Location</h3>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Location</label>
                                        <input type="text" name="home_location" value="{{ old('home_location', $profile && $profile->home_location ? $profile->home_location : '') }}" placeholder="Los Angeles, CA" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                    </div>
                                </div>

                                <!-- Change Password -->
                                <div class="mb-8">
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                        <i class="ri-lock-line"></i>
                                        Change Password
                                    </h3>
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Current Password</label>
                                            <input type="password" name="current_password" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">New Password</label>
                                            <input type="password" name="password" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Confirm New Password</label>
                                            <input type="password" name="password_confirmation" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                        </div>
                                        <button type="button" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg transition-colors">
                                            Update Password
                                        </button>
                                    </div>
                                </div>

                                <!-- Danger Zone -->
                                <div class="mb-8 border-2 border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900/20 rounded-lg p-6">
                                    <h3 class="text-lg font-bold text-red-900 dark:text-red-300 mb-2 flex items-center gap-2">
                                        <i class="ri-delete-bin-line"></i>
                                        Danger Zone
                                    </h3>
                                    <p class="text-sm text-red-700 dark:text-red-400 mb-4">Once you delete your account, there is no going back. Please be certain.</p>
                                    <div class="flex gap-2">
                                        <input type="text" placeholder="Type DELETE to confirm" class="flex-1 rounded-lg border border-red-300 dark:border-red-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2">
                                        <button type="button" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg flex items-center gap-2 transition-colors">
                                            <i class="ri-delete-bin-line"></i>
                                            Delete Account
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Preferences Tab -->
                            <div id="content-preferences" class="tab-content hidden">
                                <!-- What Are You Looking For -->
                                <div class="mb-8">
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">What Are You Looking For?</h3>
                                    <div class="space-y-3">
                                        @php
                                            $preferenceOptions = ['full_swap', 'exhibitionist', 'still_exploring', 'others', 'soft_swap', 'voyeur', 'hotwife'];
                                        @endphp
                                        @foreach($preferenceOptions as $option)
                                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                                <label class="text-gray-900 dark:text-white font-medium capitalize">{{ str_replace('_', ' ', $option) }}</label>
                                                <label class="relative inline-flex items-center cursor-pointer">
                                                    <input type="checkbox" name="preferences[]" value="{{ $option }}" {{ in_array($option, $preferences) ? 'checked' : '' }} class="sr-only peer">
                                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 dark:peer-focus:ring-purple-800 rounded-full peer dark:bg-gray-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-purple-600"></div>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Languages -->
                                <div class="mb-8">
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                        <i class="ri-global-line text-purple-600"></i>
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
                                                    <input type="checkbox" name="languages[]" value="{{ $lang }}" {{ in_array($lang, $languages) ? 'checked' : '' }} class="sr-only peer">
                                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 dark:peer-focus:ring-purple-800 rounded-full peer dark:bg-gray-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-purple-600"></div>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Additional Details -->
                                <div class="mb-8">
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

                                <!-- Danger Zone -->
                                <div class="mb-8 border-2 border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900/20 rounded-lg p-6">
                                    <h3 class="text-lg font-bold text-red-900 dark:text-red-300 mb-2 flex items-center gap-2">
                                        <i class="ri-delete-bin-line"></i>
                                        Danger Zone
                                    </h3>
                                    <p class="text-sm text-red-700 dark:text-red-400 mb-4">Once you delete your account, there is no going back. Please be certain.</p>
                                    <div class="flex gap-2">
                                        <input type="text" placeholder="Type DELETE to confirm" class="flex-1 rounded-lg border border-red-300 dark:border-red-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2">
                                        <button type="button" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg flex items-center gap-2 transition-colors">
                                            <i class="ri-delete-bin-line"></i>
                                            Delete Account
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Privacy Tab -->
                            <div id="content-privacy" class="tab-content hidden">
                                <!-- Privacy Settings -->
                                <div class="mb-8">
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
                                <div class="mb-8">
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                        <i class="ri-eye-line text-purple-600"></i>
                                        Who Can See...
                                    </h3>
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">My photos</label>
                                            <select class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                                <option value="everyone" selected>Everyone</option>
                                                <option value="friends">Friends</option>
                                                <option value="private">Private</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">My videos</label>
                                            <select class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                                <option value="everyone" selected>Everyone</option>
                                                <option value="friends">Friends</option>
                                                <option value="private">Private</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">My friends list</label>
                                            <select class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                                <option value="everyone" selected>Everyone</option>
                                                <option value="friends">Friends</option>
                                                <option value="private">Private</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">My events</label>
                                            <select class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                                <option value="everyone" selected>Everyone</option>
                                                <option value="friends">Friends</option>
                                                <option value="private">Private</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Danger Zone -->
                                <div class="mb-8 border-2 border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900/20 rounded-lg p-6">
                                    <h3 class="text-lg font-bold text-red-900 dark:text-red-300 mb-2 flex items-center gap-2">
                                        <i class="ri-delete-bin-line"></i>
                                        Danger Zone
                                    </h3>
                                    <p class="text-sm text-red-700 dark:text-red-400 mb-4">Once you delete your account, there is no going back. Please be certain.</p>
                                    <div class="flex gap-2">
                                        <input type="text" placeholder="Type DELETE to confirm" class="flex-1 rounded-lg border border-red-300 dark:border-red-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2">
                                        <button type="button" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg flex items-center gap-2 transition-colors">
                                            <i class="ri-delete-bin-line"></i>
                                            Delete Account
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center justify-end gap-3 border-t border-gray-200 dark:border-gray-700 pt-6 mt-6">
                                <a href="{{ route('account.profile') }}" class="rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-6 py-2 text-sm font-semibold text-gray-700 dark:text-gray-300 shadow-sm transition-colors hover:bg-gray-50 dark:hover:bg-gray-600">
                                    Cancel
                                </a>
                                <button type="submit" class="rounded-lg bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-2 text-sm font-semibold text-white shadow-sm transition-all hover:from-purple-700 hover:to-pink-700">
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function switchTab(tab) {
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });
        
        // Remove active class from all tabs
        document.querySelectorAll('.tab-button').forEach(button => {
            button.classList.remove('active', 'bg-gray-800', 'dark:bg-gray-700', 'text-white');
            button.classList.add('text-gray-600', 'dark:text-gray-400', 'hover:bg-gray-100', 'dark:hover:bg-gray-700');
        });
        
        // Show selected tab content
        document.getElementById('content-' + tab).classList.remove('hidden');
        
        // Add active class to selected tab
        const activeTab = document.getElementById('tab-' + tab);
        activeTab.classList.add('active', 'bg-gray-800', 'dark:bg-gray-700', 'text-white');
        activeTab.classList.remove('text-gray-600', 'dark:text-gray-400', 'hover:bg-gray-100', 'dark:hover:bg-gray-700');
    }

    // Profile image preview
    document.getElementById('profile_image')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.querySelector('.relative.inline-block img, .relative.inline-block div');
                if (img && img.tagName === 'IMG') {
                    img.src = e.target.result;
                } else if (img) {
                    const newImg = document.createElement('img');
                    newImg.src = e.target.result;
                    newImg.className = 'w-32 h-32 rounded-full object-cover border-4 border-white shadow-lg';
                    newImg.alt = '{{ $user->name }}';
                    img.parentNode.replaceChild(newImg, img);
                }
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
@endsection
