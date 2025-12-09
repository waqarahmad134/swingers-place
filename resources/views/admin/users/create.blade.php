@extends('layouts.admin')

@section('title', 'Create User - Admin Panel')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-600 hover:text-primary dark:text-gray-400">
            ← Back to Users
        </a>
    </div>

    <div class="min-h-[calc(100vh-200px)] flex items-center justify-center py-12 px-4">
        <div class="w-full max-w-2xl">
            <!-- Register Card -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl p-8 md:p-10 border border-gray-100 dark:border-gray-700">
                <!-- Header -->
                <div class="text-center mb-8">
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-2">
                        Create New User
                    </h1>
                </div>

                <!-- Register Form -->
                <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-5" id="register-form" novalidate>
                    @csrf

                    <!-- Username -->
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Username <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="username"
                            name="username"
                            value="{{ old('username') }}"
                            required
                            autofocus
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all"
                            placeholder="Username"
                        >
                        <!-- Username validation message -->
                        <div id="username-validation" class="mt-2 hidden">
                            <p id="username-message" class="text-sm"></p>
                        </div>
                        <!-- Username suggestions -->
                        <div id="username-suggestions" class="mt-2 hidden">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Suggestions:</p>
                            <div id="suggestions-list" class="flex flex-wrap gap-2"></div>
                        </div>
                        @error('username')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all"
                            placeholder="you@example.com"
                        >
                        <!-- Email validation message -->
                        <div id="email-validation" class="mt-2 hidden">
                            <p id="email-message" class="text-sm"></p>
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Password
                            </label>
                            <div class="relative">
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all"
                                    placeholder="••••••••"
                                >
                                <button 
                                    type="button" 
                                    onclick="togglePasswordRegister('password')"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
                                >
                                    <i id="eye-icon-password" class="ri-eye-off-line text-xl"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Confirm Password
                            </label>
                            <div class="relative">
                                <input
                                    type="password"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all"
                                    placeholder="••••••••"
                                >
                                <button 
                                    type="button" 
                                    onclick="togglePasswordRegister('password_confirmation')"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
                                >
                                    <i id="eye-icon-password_confirmation" class="ri-eye-off-line text-xl"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Terms and Conditions Checkbox -->
                    <div class="flex items-start gap-2">
                        <input
                            type="checkbox"
                            id="terms_accepted"
                            name="terms_accepted"
                            value="1"
                            required
                            class="mt-1 h-4 w-4 rounded border-gray-300 text-[#9810FA] focus:ring-[#9810FA]"
                        >
                        <label for="terms_accepted" class="text-sm text-gray-700 dark:text-gray-300">
                            I confirm that this user agrees to the <a href="{{ route('terms') }}" target="_blank" class="text-[#9810FA] hover:text-[#E60076] underline">Terms of Service</a> and <a href="{{ route('privacy') }}" target="_blank" class="text-[#9810FA] hover:text-[#E60076] underline">Privacy Policy</a> <span class="text-red-500">*</span>
                        </label>
                    </div>
                    @error('terms_accepted')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror

                    <!-- Admin Checkbox -->
                    <div class="flex items-start gap-2 border-t pt-4 dark:border-gray-700">
                        <input
                            type="checkbox"
                            id="is_admin"
                            name="is_admin"
                            value="1"
                            {{ old('is_admin') ? 'checked' : '' }}
                            class="mt-1 h-4 w-4 rounded border-gray-300 text-[#9810FA] focus:ring-[#9810FA]"
                        >
                        <label for="is_admin" class="text-sm text-gray-700 dark:text-gray-300">
                            Admin (can access admin panel)
                        </label>
                    </div>

                    <!-- Profile Photo Section -->
                    <div id="profile-photo-section" class="mt-6">
                        <!-- Profile Photo Upload -->
                        <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-2xl p-8 text-center hover:border-[#9810FA] transition-all cursor-pointer relative" 
                                onclick="if(!document.getElementById('profile-preview').querySelector('img')) { document.getElementById('profile_photo').click(); }">
                            <input type="file" id="profile_photo" name="profile_photo" accept="image/*" class="hidden" onchange="previewProfileImage(this, 'profile-preview')">
                            <div id="profile-preview">
                                <i class="ri-camera-line text-5xl text-gray-400 mb-3"></i>
                                <p class="font-semibold text-gray-900 dark:text-white mb-1">Upload Profile Photo</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Click to select or drag and drop</p>
                            </div>
                            <!-- Delete Button (hidden by default) -->
                            <button 
                                type="button" 
                                id="delete-profile-photo-btn" 
                                onclick="event.stopPropagation(); deleteProfilePhoto();"
                                class="hidden absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-2 transition-colors"
                                title="Remove photo"
                            >
                                <i class="ri-delete-bin-line text-lg"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Category Selection Section -->
                    <div id="category-section" class="mt-6 p-6 bg-gray-50 dark:bg-gray-700 rounded-xl border border-gray-200 dark:border-gray-600">
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                You are a *
                            </h3>
                        </div>
                        
                        <div class="grid grid-cols-4 gap-4">
                            <!-- Couple -->
                            <label class="category-option">
                                <input type="radio" name="category" value="couple" class="sr-only category-input" required>
                                <div class="category-card flex flex-col items-center p-2 border-2 border-gray-200 dark:border-gray-700 rounded-2xl cursor-pointer hover:border-[#9810FA] transition-all text-center">
                                    <div class="">
                                        <img src="{{ asset('assets/couple_icon.svg') }}" alt="Couple" class="w-10 h-10">
                                    </div>
                                    <p class="font-semibold text-gray-900 dark:text-white">Couple</p>
                                </div>
                            </label>

                            <!-- Female -->
                            <label class="category-option">
                                <input type="radio" name="category" value="single_female" class="sr-only category-input" required>
                                <div class="category-card flex flex-col items-center p-2 border-2 border-gray-200 dark:border-gray-700 rounded-2xl cursor-pointer hover:border-[#9810FA] transition-all text-center">
                                    <div class="">
                                        <img src="{{ asset('assets/female.svg') }}" alt="Female" class="w-10 h-10">
                                    </div>
                                    <p class="font-semibold text-gray-900 dark:text-white">Female</p>
                                </div>
                            </label>

                            <!-- Male -->
                            <label class="category-option">
                                <input type="radio" name="category" value="single_male" class="sr-only category-input" required>
                                <div class="category-card flex flex-col items-center p-2 border-2 border-gray-200 dark:border-gray-700 rounded-2xl cursor-pointer hover:border-[#9810FA] transition-all text-center">
                                    <div class="">
                                        <img src="{{ asset('assets/male.svg') }}" alt="Male" class="w-10 h-10">
                                    </div>
                                    <p class="font-semibold text-gray-900 dark:text-white">Male</p>
                                </div>
                            </label>

                            <!-- Transgender -->
                            <label class="category-option">
                                <input type="radio" name="category" value="transsexual" class="sr-only category-input" required>
                                <div class="category-card flex flex-col items-center p-2 border-2 border-gray-200 dark:border-gray-700 rounded-2xl cursor-pointer hover:border-[#9810FA] transition-all text-center">
                                    <div class="">
                                        <img src="{{ asset('assets/transgender.svg') }}" alt="Transgender" class="w-10 h-10">
                                    </div>
                                    <p class="font-semibold text-gray-900 dark:text-white">Transgender</p>
                                </div>
                            </label>
                        </div>

                        <!-- Basic Information Section -->
                        <div id="basic-info-section" class="hidden mt-6">
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                    Basic Information
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    Tell us more about yourself
                                </p>
                            </div>
                            
                            <!-- Couple Mode: Show Her and Him sections -->
                            <div id="couple-info" class="hidden">
                                <!-- Her Section -->
                                <div class="border-2 border-pink-200 dark:border-pink-800 rounded-2xl p-6 mb-6">
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                        <span class="text-pink-500">👩</span> Her Information
                                    </h3>
                                    
                                    <div class="grid grid-cols-2 gap-4">
                                        <!-- Date of Birth - Her -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Date of Birth
                                            </label>
                                            <div class="relative">
                                                <input type="date" id="date_of_birth_her" name="date_of_birth_her" 
                                                       max="{{ date('Y-m-d', strtotime('-18 years')) }}"
                                                       class="w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                                                <i class="ri-calendar-line absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                                            </div>
                                        </div>

                                        <!-- Sexuality - Her -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Sexuality
                                            </label>
                                            <select id="sexuality_her" name="sexuality_her" 
                                                    class="w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                                                <option value="">Select...</option>
                                                <option value="heterosexual">Heterosexual</option>
                                                <option value="bisexual">Bisexual</option>
                                                <option value="homosexual">Homosexual</option>
                                                <option value="pansexual">Pansexual</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Him Section -->
                                <div class="border-2 border-blue-200 dark:border-blue-800 rounded-2xl p-6">
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                        <span class="text-blue-500">👨</span> Him Information
                                    </h3>
                                    
                                    <div class="grid grid-cols-2 gap-4">
                                        <!-- Date of Birth - Him -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Date of Birth
                                            </label>
                                            <div class="relative">
                                                <input type="date" id="date_of_birth_him" name="date_of_birth_him" 
                                                       max="{{ date('Y-m-d', strtotime('-18 years')) }}"
                                                       class="w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                                                <i class="ri-calendar-line absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                                            </div>
                                        </div>

                                        <!-- Sexuality - Him -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Sexuality
                                            </label>
                                            <select id="sexuality_him" name="sexuality_him" 
                                                    class="w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                                                <option value="">Select...</option>
                                                <option value="heterosexual">Heterosexual</option>
                                                <option value="bisexual">Bisexual</option>
                                                <option value="homosexual">Homosexual</option>
                                                <option value="pansexual">Pansexual</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Single Mode: Single fields -->
                            <div id="single-info" class="hidden">
                                <div class="grid grid-cols-2 gap-4">
                                    <!-- Date of Birth -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Date of Birth
                                        </label>
                                        <div class="relative">
                                            <input type="date" id="date_of_birth" name="date_of_birth" 
                                                   max="{{ date('Y-m-d', strtotime('-18 years')) }}"
                                                   class="w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                                            <i class="ri-calendar-line absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                                        </div>
                                    </div>

                                    <!-- Sexuality -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Sexuality
                                        </label>
                                        <select id="sexuality" name="sexuality" 
                                                class="w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                                            <option value="">Select...</option>
                                            <option value="heterosexual">Heterosexual</option>
                                            <option value="bisexual">Bisexual</option>
                                            <option value="homosexual">Homosexual</option>
                                            <option value="pansexual">Pansexual</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Preferences Section -->
                        <div id="preferences-section" class="hidden mt-6">
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                    What Do You Prefer? (Choose 1 or more)*
                                </h3>
                            </div>
                            
                            <div class="grid grid-cols-4 gap-4">
                                <!-- Full Swap -->
                                <label class="preference-option">
                                    <input type="checkbox" name="preferences[]" value="full_swap" class="sr-only preference-input">
                                    <div class="preference-card flex flex-col items-center p-2 border-2 border-gray-200 dark:border-gray-700 rounded-2xl cursor-pointer hover:border-[#9810FA] transition-all text-center">
                                        <div class="">
                                            <img src="{{ asset('assets/couple_icon.svg') }}" alt="Couple" class="w-10 h-10">
                                        </div>
                                        <p class="font-semibold text-gray-900 dark:text-white">Couple</p>
                                    </div>
                                </label>

                                <!-- Soft Swap -->
                                <label class="preference-option">
                                    <input type="checkbox" name="preferences[]" value="soft_swap" class="sr-only preference-input">
                                    <div class="preference-card flex flex-col items-center p-2 border-2 border-gray-200 dark:border-gray-700 rounded-2xl cursor-pointer hover:border-[#9810FA] transition-all text-center">
                                        <div class="">
                                            <img src="{{ asset('assets/female.svg') }}" alt="Female" class="w-10 h-10">
                                        </div>
                                        <p class="font-semibold text-gray-900 dark:text-white">Female</p>
                                    </div>
                                </label>

                                <!-- Exhibitionist -->
                                <label class="preference-option">
                                    <input type="checkbox" name="preferences[]" value="exhibitionist" class="sr-only preference-input">
                                    <div class="preference-card flex flex-col items-center p-2 border-2 border-gray-200 dark:border-gray-700 rounded-2xl cursor-pointer hover:border-[#9810FA] transition-all text-center">
                                        <div class="">
                                            <img src="{{ asset('assets/male.svg') }}" alt="Male" class="w-10 h-10">
                                        </div>
                                        <p class="font-semibold text-gray-900 dark:text-white">Male</p>
                                    </div>
                                </label>

                                <!-- Voyeur -->
                                <label class="preference-option">
                                    <input type="checkbox" name="preferences[]" value="voyeur" class="sr-only preference-input">
                                    <div class="preference-card flex flex-col items-center p-2 border-2 border-gray-200 dark:border-gray-700 rounded-2xl cursor-pointer hover:border-[#9810FA] transition-all text-center">
                                        <div class="">
                                            <img src="{{ asset('assets/transgender.svg') }}" alt="Transgender" class="w-10 h-10">
                                        </div>
                                        <p class="font-semibold text-gray-900 dark:text-white">Transgender</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Location Section -->
                        <div id="location-section" class="hidden mt-6">
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                    Location
                                </h3>
                            </div>
                            
                            <div class="space-y-4">
                                <!-- Home Location -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Home Location
                                    </label>
                                    <input type="text" id="home_location" name="home_location" 
                                           placeholder="Search for your city..."
                                           class="w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                                    <input type="hidden" id="home_location_lat" name="home_location_lat">
                                    <input type="hidden" id="home_location_lng" name="home_location_lng">
                                </div>

                                <!-- Country and City Fields -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Country -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Country
                                        </label>
                                        <input type="text" id="country" name="country" 
                                               placeholder="Country will auto-fill from location..."
                                               class="w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                                    </div>

                                    <!-- City -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            City
                                        </label>
                                        <input type="text" id="city" name="city" 
                                               placeholder="City will auto-fill from location..."
                                               class="w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                                    </div>
                                </div>

                                <!-- Map Display -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Location Map
                                    </label>
                                    <div id="map" class="rounded-xl h-64 w-full border border-gray-200 dark:border-gray-600" style="display: none;"></div>
                                    <div id="map-placeholder" class="bg-gray-100 dark:bg-gray-700 rounded-xl h-64 flex items-center justify-center border-2 border-dashed border-gray-300 dark:border-gray-600">
                                        <div class="text-center">
                                            <i class="ri-map-pin-2-line text-4xl text-gray-400 mb-2"></i>
                                            <p class="text-gray-500 dark:text-gray-400 text-sm">Map will appear when you select a location</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Describe Yourself Section -->
                        <div id="describe-section" class="hidden mt-6">
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                    Bio
                                </h3>
                            </div>
                            
                            <div class="space-y-4">
                                <!-- Describe Yourself -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Describe Yourself
                                    </label>
                                    <textarea 
                                        id="bio" 
                                        name="bio" 
                                        rows="4" 
                                        placeholder="Tell us about yourself, your interests, and what makes you unique..."
                                        class="w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all resize-none"
                                    ></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Create Button -->
                    <button
                        type="submit"
                        class="w-full py-3.5 px-4 bg-[linear-gradient(90deg,#9810FA_0%,#E60076_100%)] text-white text-base font-semibold rounded-full hover:shadow-lg hover:scale-[1.02] transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:ring-offset-2 mt-6"
                    >
                        Create User
                    </button>

                    <!-- Cancel Link -->
                    <div class="mt-4 text-center">
                        <a href="{{ route('admin.users.index') }}" class="text-sm font-semibold text-[#9810FA] hover:text-[#E60076] transition-colors">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Password Toggle Script -->
<script>
    function togglePasswordRegister(fieldId) {
        const passwordInput = document.getElementById(fieldId);
        const eyeIcon = document.getElementById('eye-icon-' + fieldId);
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.remove('ri-eye-off-line');
            eyeIcon.classList.add('ri-eye-line');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove('ri-eye-line');
            eyeIcon.classList.add('ri-eye-off-line');
        }
    }

    // Username validation with real-time check
    let usernameCheckTimeout;
    const usernameInput = document.getElementById('username');
    const usernameValidation = document.getElementById('username-validation');
    const usernameMessage = document.getElementById('username-message');
    const usernameSuggestions = document.getElementById('username-suggestions');
    const suggestionsList = document.getElementById('suggestions-list');
    let isUsernameValid = false;

    usernameInput.addEventListener('input', function() {
        const username = this.value.trim();
        
        // Clear previous timeout
        clearTimeout(usernameCheckTimeout);
        
        // Hide validation and suggestions initially
        usernameValidation.classList.add('hidden');
        usernameSuggestions.classList.add('hidden');
        suggestionsList.innerHTML = '';
        isUsernameValid = false;
        
        // Remove previous validation classes
        usernameInput.classList.remove('border-red-500', 'border-green-500');
        
        if (username.length === 0) {
            return;
        }
        
        // Debounce the API call
        usernameCheckTimeout = setTimeout(function() {
            checkUsernameAvailability(username);
        }, 500);
    });

    function checkUsernameAvailability(username) {
        fetch(`{{ route('check-username') }}?username=${encodeURIComponent(username)}`)
            .then(response => response.json())
            .then(data => {
                usernameValidation.classList.remove('hidden');
                
                if (data.available) {
                    // Username is available
                    usernameMessage.textContent = data.message;
                    usernameMessage.className = 'text-sm text-green-600 dark:text-green-400';
                    usernameInput.classList.remove('border-red-500');
                    usernameInput.classList.add('border-green-500');
                    usernameSuggestions.classList.add('hidden');
                    isUsernameValid = true;
                } else {
                    // Username is taken
                    usernameMessage.textContent = data.message;
                    usernameMessage.className = 'text-sm text-red-600 dark:text-red-400';
                    usernameInput.classList.remove('border-green-500');
                    usernameInput.classList.add('border-red-500');
                    isUsernameValid = false;
                    
                    // Show suggestions if available
                    if (data.suggestions && data.suggestions.length > 0) {
                        usernameSuggestions.classList.remove('hidden');
                        suggestionsList.innerHTML = '';
                        
                        data.suggestions.forEach(function(suggestion) {
                            const suggestionBtn = document.createElement('button');
                            suggestionBtn.type = 'button';
                            suggestionBtn.textContent = suggestion;
                            suggestionBtn.className = 'px-3 py-1.5 text-sm bg-[#9810FA] text-white rounded-lg hover:bg-[#E60076] transition-colors cursor-pointer';
                            suggestionBtn.addEventListener('click', function() {
                                usernameInput.value = suggestion;
                                usernameInput.dispatchEvent(new Event('input'));
                            });
                            suggestionsList.appendChild(suggestionBtn);
                        });
                    }
                }
            })
            .catch(error => {
                console.error('Error checking username:', error);
            });
    }

    // Email validation with real-time check
    let emailCheckTimeout;
    const emailInput = document.getElementById('email');
    const emailValidation = document.getElementById('email-validation');
    const emailMessage = document.getElementById('email-message');
    let isEmailValid = false;
    let isEmailAvailable = false;

    emailInput.addEventListener('input', function() {
        const email = this.value.trim();
        
        // Clear previous timeout
        clearTimeout(emailCheckTimeout);
        
        // Hide validation initially
        emailValidation.classList.add('hidden');
        isEmailValid = false;
        isEmailAvailable = false;
        
        // Remove previous validation classes
        emailInput.classList.remove('border-red-500', 'border-green-500');
        
        if (email.length === 0) {
            return;
        }
        
        // First check email format
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            emailValidation.classList.remove('hidden');
            emailMessage.textContent = 'Please enter a valid email address';
            emailMessage.className = 'text-sm text-red-600 dark:text-red-400';
            emailInput.classList.remove('border-green-500');
            emailInput.classList.add('border-red-500');
            isEmailValid = false;
            return;
        }
        
        // Debounce the API call for availability check
        emailCheckTimeout = setTimeout(function() {
            checkEmailAvailability(email);
        }, 500);
    });

    function checkEmailAvailability(email) {
        fetch(`{{ route('check-email') }}?email=${encodeURIComponent(email)}`)
            .then(response => response.json())
            .then(data => {
                emailValidation.classList.remove('hidden');
                
                if (!data.valid) {
                    // Invalid email format
                    emailMessage.textContent = data.message;
                    emailMessage.className = 'text-sm text-red-600 dark:text-red-400';
                    emailInput.classList.remove('border-green-500');
                    emailInput.classList.add('border-red-500');
                    isEmailValid = false;
                    isEmailAvailable = false;
                } else if (data.available) {
                    // Email is valid and available
                    emailMessage.textContent = data.message;
                    emailMessage.className = 'text-sm text-green-600 dark:text-green-400';
                    emailInput.classList.remove('border-red-500');
                    emailInput.classList.add('border-green-500');
                    isEmailValid = true;
                    isEmailAvailable = true;
                } else {
                    // Email is valid but already registered
                    emailMessage.textContent = data.message;
                    emailMessage.className = 'text-sm text-red-600 dark:text-red-400';
                    emailInput.classList.remove('border-green-500');
                    emailInput.classList.add('border-red-500');
                    isEmailValid = true;
                    isEmailAvailable = false;
                }
            })
            .catch(error => {
                console.error('Error checking email:', error);
            });
    }

    // Handle category selection visual feedback and show basic info section
    document.querySelectorAll('.category-input').forEach(input => {
        input.addEventListener('change', function() {
            document.querySelectorAll('.category-card').forEach(card => {
                card.classList.remove('border-[#9810FA]', 'bg-pink-50', 'dark:bg-pink-900/10');
            });
            if(this.checked) {
                this.closest('.category-option').querySelector('.category-card').classList.add('border-[#9810FA]', 'bg-pink-50', 'dark:bg-pink-900/10');
                
                // Show basic info section based on category
                const basicInfoSection = document.getElementById('basic-info-section');
                const coupleInfo = document.getElementById('couple-info');
                const singleInfo = document.getElementById('single-info');
                const preferencesSection = document.getElementById('preferences-section');
                const locationSection = document.getElementById('location-section');
                const describeSection = document.getElementById('describe-section');
                
                if (basicInfoSection) {
                    basicInfoSection.classList.remove('hidden');
                    
                    if (this.value === 'couple') {
                        // Show couple sections (Her and Him)
                        coupleInfo.classList.remove('hidden');
                        singleInfo.classList.add('hidden');
                    } else {
                        // Show single section
                        coupleInfo.classList.add('hidden');
                        singleInfo.classList.remove('hidden');
                    }
                }
                
                // Show other sections
                if (preferencesSection) preferencesSection.classList.remove('hidden');
                if (locationSection) locationSection.classList.remove('hidden');
                if (describeSection) describeSection.classList.remove('hidden');
            }
        });
    });

    // Handle preference selection visual feedback
    document.querySelectorAll('.preference-input').forEach(input => {
        input.addEventListener('change', function() {
            const card = this.closest('.preference-option').querySelector('.preference-card');
            if(this.checked) {
                card.classList.add('border-[#9810FA]', 'bg-pink-50', 'dark:bg-pink-900/10');
            } else {
                card.classList.remove('border-[#9810FA]', 'bg-pink-50', 'dark:bg-pink-900/10');
            }
        });
    });
</script>

<!-- Google Maps Script for Location -->
@php
    $googleMapsApiKey = config('services.google_maps.api_key');
@endphp
@if($googleMapsApiKey)
<script>
let map;
let marker;
let homeLocationAutocomplete;

function extractCountryAndCity(addressComponents) {
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
    const countryInput = document.getElementById('country');
    const cityInput = document.getElementById('city');
    if (countryInput) countryInput.value = country;
    if (cityInput) cityInput.value = city;
}

function initGoogleMaps() {
    // Initialize Home Location Autocomplete
    const homeLocationInput = document.getElementById('home_location');
    if (homeLocationInput && typeof google !== 'undefined' && google.maps && google.maps.places) {
        try {
            homeLocationAutocomplete = new google.maps.places.Autocomplete(homeLocationInput, {
                types: ['(cities)'],
                fields: ['formatted_address', 'geometry', 'name', 'address_components']
            });

            homeLocationAutocomplete.addListener('place_changed', function() {
                const place = homeLocationAutocomplete.getPlace();
                if (!place.geometry) {
                    return;
                }

                document.getElementById('home_location_lat').value = place.geometry.location.lat();
                document.getElementById('home_location_lng').value = place.geometry.location.lng();
                homeLocationInput.value = place.formatted_address || place.name;

                // Extract country and city from address components
                extractCountryAndCity(place.address_components);

                if (!map) {
                    initMap(place.geometry.location);
                } else {
                    map.setCenter(place.geometry.location);
                    marker.setPosition(place.geometry.location);
                }

                document.getElementById('map-placeholder').style.display = 'none';
                document.getElementById('map').style.display = 'block';
            });
        } catch (error) {
            console.error('Error initializing autocomplete:', error);
        }
    }
}

function initMap(location) {
    const mapElement = document.getElementById('map');
    if (!mapElement) return;

    map = new google.maps.Map(mapElement, {
        center: location || { lat: 0, lng: 0 },
        zoom: 12,
        mapTypeControl: false,
        streetViewControl: false,
        fullscreenControl: false
    });

    marker = new google.maps.Marker({
        map: map,
        position: location || { lat: 0, lng: 0 },
        draggable: true,
        animation: google.maps.Animation.DROP
    });

    marker.addListener('dragend', function() {
        const position = marker.getPosition();
        document.getElementById('home_location_lat').value = position.lat();
        document.getElementById('home_location_lng').value = position.lng();
        
        const geocoder = new google.maps.Geocoder();
        geocoder.geocode({ location: position }, (results, status) => {
            if (status === 'OK' && results[0]) {
                document.getElementById('home_location').value = results[0].formatted_address;
                // Extract country and city from geocoded results
                extractCountryAndCity(results[0].address_components);
            }
        });
    });
}

// Load Google Maps API with proper async loading and callback
(function() {
    const script = document.createElement('script');
    script.src = 'https://maps.googleapis.com/maps/api/js?key={{ $googleMapsApiKey }}&loading=async&libraries=places&callback=initMapCallback';
    script.async = true;
    script.defer = true;
    document.head.appendChild(script);
})();

// Callback function for when Google Maps loads
window.initMapCallback = function() {
    if (typeof google !== 'undefined' && google.maps) {
        initGoogleMaps();
    }
};
</script>
@else
<script>
console.warn('Google Maps API key is not configured. Please add GOOGLE_MAPS_API_KEY to your .env file.');
</script>
@endif

<script>
// Profile Photo Preview Function
function previewProfileImage(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById(previewId).innerHTML = `
                <img src="${e.target.result}" class="max-h-48 rounded-lg mx-auto">
                <p class="text-sm text-green-600 dark:text-green-400 mt-3">✓ Photo selected</p>
            `;
            // Show delete button
            const deleteBtn = document.getElementById('delete-profile-photo-btn');
            if (deleteBtn) deleteBtn.classList.remove('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// Delete Profile Photo Function
function deleteProfilePhoto() {
    const profilePhotoInput = document.getElementById('profile_photo');
    const profilePreview = document.getElementById('profile-preview');
    const deleteBtn = document.getElementById('delete-profile-photo-btn');
    
    if (!profilePhotoInput || !profilePreview || !deleteBtn) return;
    
    // Reset file input
    profilePhotoInput.value = '';
    
    // Reset preview to default state
    profilePreview.innerHTML = `
        <i class="ri-camera-line text-5xl text-gray-400 mb-3"></i>
        <p class="font-semibold text-gray-900 dark:text-white mb-1">Upload Profile Photo</p>
        <p class="text-sm text-gray-500 dark:text-gray-400">Click to select or drag and drop</p>
    `;
    
    // Hide delete button
    deleteBtn.classList.add('hidden');
}
</script>
@endsection
