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

        <form id="profile-form" action="{{ route('account.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <!-- Left Panel - Profile Picture -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 space-y-4">
                        <!-- Profile Picture -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Profile Picture</label>
                            <div class="relative inline-block">
                                @if(($profile && $profile->profile_photo) || $user->profile_image)
                                    <img id="profile-preview" src="{{ asset('storage/' . (($profile && $profile->profile_photo) ? $profile->profile_photo : $user->profile_image)) }}" alt="{{ $user->name }}" class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-lg">
                                @else
                                    <div id="profile-preview" class="w-32 h-32 rounded-full bg-gradient-to-br from-purple-400 to-pink-400 flex items-center justify-center text-white text-3xl font-bold border-4 border-white shadow-lg">
                                        {{ strtoupper(substr($user->first_name ?? $user->name, 0, 1)) }}{{ strtoupper(substr($user->last_name ?? '', 0, 1)) }}
                                    </div>
                                @endif
                                <!-- Change Profile Picture Button -->
                                <label for="profile_image" class="absolute bottom-0 right-0 w-10 h-10 bg-purple-600 rounded-full flex items-center justify-center cursor-pointer hover:bg-purple-700 transition-colors shadow-lg">
                                    <i class="ri-camera-line text-white text-lg"></i>
                                    <input type="file" id="profile_image" name="profile_image" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp" class="hidden">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Panel - Main Content -->
                <div class="lg:col-span-3">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                        <!-- Tabs -->
                        <div class="border-b border-gray-200 dark:border-gray-700">
                            <div class="flex space-x-1 p-2">
                                <button type="button" onclick="switchTab('account')" id="tab-account" class="tab-button active px-6 py-3 text-sm font-semibold rounded-lg transition-colors bg-gray-800 dark:bg-gray-700 text-white">
                                    <i class="ri-user-line mr-2"></i>
                                    Account
                                </button>
                            </div>
                        </div>

                        <!-- Tab Content -->
                        <div class="p-6">

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
                                    @php
                                        $isCouple = ($profile && $profile->category === 'couple');
                                        // Use coupleData passed from controller or decode if needed
                                        $coupleData = $coupleData ?? ($profile && $profile->couple_data ? (is_array($profile->couple_data) ? $profile->couple_data : json_decode($profile->couple_data, true) ?? []) : []);
                                    @endphp

                                    @if($isCouple)
                                        <!-- Couple Mode: Show Her and Him sections -->
                                        
                                        <!-- Account Level Fields (shown for both single and couple) -->
                                        <div class="mb-6 space-y-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Display Name</label>
                                                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
                                                <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                            </div>
                                        </div>
                                        
                                        <!-- Her Section -->
                                        <div class="border-2 border-pink-200 dark:border-pink-800 rounded-2xl p-6 mb-6">
                                            <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                                <span class="text-pink-500">👩</span> Her Information
                                            </h4>
                                            
                                            <div class="space-y-4">
                                                <div class="grid grid-cols-2 gap-4">
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date of Birth</label>
                                                        <input type="date" name="date_of_birth_her" value="{{ old('date_of_birth_her', $coupleData['date_of_birth_her'] ?? '') }}" max="{{ date('Y-m-d', strtotime('-18 years')) }}" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Sexuality</label>
                                                        <select name="sexuality_her" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                                            <option value="">Select...</option>
                                                            <option value="heterosexual" {{ old('sexuality_her', $coupleData['sexuality_her'] ?? '') === 'heterosexual' ? 'selected' : '' }}>Heterosexual</option>
                                                            <option value="bisexual" {{ old('sexuality_her', $coupleData['sexuality_her'] ?? '') === 'bisexual' ? 'selected' : '' }}>Bisexual</option>
                                                            <option value="homosexual" {{ old('sexuality_her', $coupleData['sexuality_her'] ?? '') === 'homosexual' ? 'selected' : '' }}>Homosexual</option>
                                                            <option value="pansexual" {{ old('sexuality_her', $coupleData['sexuality_her'] ?? '') === 'pansexual' ? 'selected' : '' }}>Pansexual</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="grid grid-cols-2 gap-4">
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Relationship Status</label>
                                                        <select name="relationship_status_her" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                                            <option value="">Select...</option>
                                                            <option value="single" {{ old('relationship_status_her', $coupleData['relationship_status_her'] ?? '') === 'single' ? 'selected' : '' }}>Single</option>
                                                            <option value="relationship" {{ old('relationship_status_her', $coupleData['relationship_status_her'] ?? '') === 'relationship' ? 'selected' : '' }}>In a Relationship</option>
                                                            <option value="married" {{ old('relationship_status_her', $coupleData['relationship_status_her'] ?? '') === 'married' ? 'selected' : '' }}>Married</option>
                                                            <option value="open" {{ old('relationship_status_her', $coupleData['relationship_status_her'] ?? '') === 'open' ? 'selected' : '' }}>Open Relationship</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Smoking</label>
                                                        <select name="smoking_her" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                                            <option value="">Select...</option>
                                                            <option value="never" {{ old('smoking_her', $coupleData['smoking_her'] ?? '') === 'never' ? 'selected' : '' }}>Never</option>
                                                            <option value="occasionally" {{ old('smoking_her', $coupleData['smoking_her'] ?? '') === 'occasionally' ? 'selected' : '' }}>Occasionally</option>
                                                            <option value="regularly" {{ old('smoking_her', $coupleData['smoking_her'] ?? '') === 'regularly' ? 'selected' : '' }}>Regularly</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="grid grid-cols-2 gap-4">
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Experience</label>
                                                        <select name="experience_her" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                                            <option value="">Select...</option>
                                                            <option value="beginner" {{ old('experience_her', $coupleData['experience_her'] ?? '') === 'beginner' ? 'selected' : '' }}>Beginner</option>
                                                            <option value="intermediate" {{ old('experience_her', $coupleData['experience_her'] ?? '') === 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                                            <option value="experienced" {{ old('experience_her', $coupleData['experience_her'] ?? '') === 'experienced' ? 'selected' : '' }}>Experienced</option>
                                                            <option value="expert" {{ old('experience_her', $coupleData['experience_her'] ?? '') === 'expert' ? 'selected' : '' }}>Expert</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Travel Options</label>
                                                        <select name="travel_options_her" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                                            <option value="">Select...</option>
                                                            <option value="can_host" {{ old('travel_options_her', $coupleData['travel_options_her'] ?? '') === 'can_host' ? 'selected' : '' }}>Can Host</option>
                                                            <option value="can_travel" {{ old('travel_options_her', $coupleData['travel_options_her'] ?? '') === 'can_travel' ? 'selected' : '' }}>Can Travel</option>
                                                            <option value="both" {{ old('travel_options_her', $coupleData['travel_options_her'] ?? '') === 'both' ? 'selected' : '' }}>Both</option>
                                                            <option value="neither" {{ old('travel_options_her', $coupleData['travel_options_her'] ?? '') === 'neither' ? 'selected' : '' }}>Neither</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bio - Her</label>
                                                    <textarea name="bio_her" rows="4" placeholder="Tell us about her..." class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">{{ old('bio_her', $coupleData['bio_her'] ?? '') }}</textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Him Section -->
                                        <div class="border-2 border-blue-200 dark:border-blue-800 rounded-2xl p-6">
                                            <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                                <span class="text-blue-500">👨</span> Him Information
                                            </h4>
                                            
                                            <div class="space-y-4">
                                                <div class="grid grid-cols-2 gap-4">
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date of Birth</label>
                                                        <input type="date" name="date_of_birth_him" value="{{ old('date_of_birth_him', $coupleData['date_of_birth_him'] ?? '') }}" max="{{ date('Y-m-d', strtotime('-18 years')) }}" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Sexuality</label>
                                                        <select name="sexuality_him" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                                            <option value="">Select...</option>
                                                            <option value="heterosexual" {{ old('sexuality_him', $coupleData['sexuality_him'] ?? '') === 'heterosexual' ? 'selected' : '' }}>Heterosexual</option>
                                                            <option value="bisexual" {{ old('sexuality_him', $coupleData['sexuality_him'] ?? '') === 'bisexual' ? 'selected' : '' }}>Bisexual</option>
                                                            <option value="homosexual" {{ old('sexuality_him', $coupleData['sexuality_him'] ?? '') === 'homosexual' ? 'selected' : '' }}>Homosexual</option>
                                                            <option value="pansexual" {{ old('sexuality_him', $coupleData['sexuality_him'] ?? '') === 'pansexual' ? 'selected' : '' }}>Pansexual</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="grid grid-cols-2 gap-4">
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Relationship Status</label>
                                                        <select name="relationship_status_him" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                                            <option value="">Select...</option>
                                                            <option value="single" {{ old('relationship_status_him', $coupleData['relationship_status_him'] ?? '') === 'single' ? 'selected' : '' }}>Single</option>
                                                            <option value="relationship" {{ old('relationship_status_him', $coupleData['relationship_status_him'] ?? '') === 'relationship' ? 'selected' : '' }}>In a Relationship</option>
                                                            <option value="married" {{ old('relationship_status_him', $coupleData['relationship_status_him'] ?? '') === 'married' ? 'selected' : '' }}>Married</option>
                                                            <option value="open" {{ old('relationship_status_him', $coupleData['relationship_status_him'] ?? '') === 'open' ? 'selected' : '' }}>Open Relationship</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Smoking</label>
                                                        <select name="smoking_him" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                                            <option value="">Select...</option>
                                                            <option value="never" {{ old('smoking_him', $coupleData['smoking_him'] ?? '') === 'never' ? 'selected' : '' }}>Never</option>
                                                            <option value="occasionally" {{ old('smoking_him', $coupleData['smoking_him'] ?? '') === 'occasionally' ? 'selected' : '' }}>Occasionally</option>
                                                            <option value="regularly" {{ old('smoking_him', $coupleData['smoking_him'] ?? '') === 'regularly' ? 'selected' : '' }}>Regularly</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="grid grid-cols-2 gap-4">
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Experience</label>
                                                        <select name="experience_him" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                                            <option value="">Select...</option>
                                                            <option value="beginner" {{ old('experience_him', $coupleData['experience_him'] ?? '') === 'beginner' ? 'selected' : '' }}>Beginner</option>
                                                            <option value="intermediate" {{ old('experience_him', $coupleData['experience_him'] ?? '') === 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                                            <option value="experienced" {{ old('experience_him', $coupleData['experience_him'] ?? '') === 'experienced' ? 'selected' : '' }}>Experienced</option>
                                                            <option value="expert" {{ old('experience_him', $coupleData['experience_him'] ?? '') === 'expert' ? 'selected' : '' }}>Expert</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Travel Options</label>
                                                        <select name="travel_options_him" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                                            <option value="">Select...</option>
                                                            <option value="can_host" {{ old('travel_options_him', $coupleData['travel_options_him'] ?? '') === 'can_host' ? 'selected' : '' }}>Can Host</option>
                                                            <option value="can_travel" {{ old('travel_options_him', $coupleData['travel_options_him'] ?? '') === 'can_travel' ? 'selected' : '' }}>Can Travel</option>
                                                            <option value="both" {{ old('travel_options_him', $coupleData['travel_options_him'] ?? '') === 'both' ? 'selected' : '' }}>Both</option>
                                                            <option value="neither" {{ old('travel_options_him', $coupleData['travel_options_him'] ?? '') === 'neither' ? 'selected' : '' }}>Neither</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bio - Him</label>
                                                    <textarea name="bio_him" rows="4" placeholder="Tell us about him..." class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">{{ old('bio_him', $coupleData['bio_him'] ?? '') }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <!-- Single Mode: Show regular fields -->
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
                                                <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $profile && $profile->date_of_birth ? $profile->date_of_birth->format('Y-m-d') : '') }}" max="{{ date('Y-m-d', strtotime('-18 years')) }}" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
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
                                    @endif
                                </div>

                                @if($isCouple)
                                    <!-- Personal Details for Couple -->
                                    <div class="mb-8">
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Personal Details</h3>
                                        
                                        <!-- Her Personal Details -->
                                        <div class="border-2 border-pink-200 dark:border-pink-800 rounded-2xl p-6 mb-6">
                                            <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                                <span class="text-pink-500">👩</span> Her Details
                                            </h4>
                                            
                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Weight (kg)</label>
                                                    <input type="number" name="weight_her" value="{{ old('weight_her', $coupleData['weight_her'] ?? '') }}" placeholder="kg" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Height (cm)</label>
                                                    <input type="number" name="height_her" value="{{ old('height_her', $coupleData['height_her'] ?? '') }}" placeholder="cm" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Body Type</label>
                                                    <select name="body_type_her" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                                        <option value="">Select Body Type</option>
                                                        <option value="Slim" {{ old('body_type_her', $coupleData['body_type_her'] ?? '') == 'Slim' ? 'selected' : '' }}>💪 Slim</option>
                                                        <option value="Athletic" {{ old('body_type_her', $coupleData['body_type_her'] ?? '') == 'Athletic' ? 'selected' : '' }}>🏃 Athletic</option>
                                                        <option value="Curvy" {{ old('body_type_her', $coupleData['body_type_her'] ?? '') == 'Curvy' ? 'selected' : '' }}>❤️ Curvy</option>
                                                        <option value="Average" {{ old('body_type_her', $coupleData['body_type_her'] ?? '') == 'Average' ? 'selected' : '' }}>👤 Average</option>
                                                        <option value="Plus Size" {{ old('body_type_her', $coupleData['body_type_her'] ?? '') == 'Plus Size' ? 'selected' : '' }}>👥 Plus Size</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Eye Color</label>
                                                    <select name="eye_color_her" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                                        <option value="">Select Eye Color</option>
                                                        <option value="Brown" {{ old('eye_color_her', $coupleData['eye_color_her'] ?? '') === 'Brown' ? 'selected' : '' }}>Brown</option>
                                                        <option value="Blue" {{ old('eye_color_her', $coupleData['eye_color_her'] ?? '') === 'Blue' ? 'selected' : '' }}>Blue</option>
                                                        <option value="Green" {{ old('eye_color_her', $coupleData['eye_color_her'] ?? '') === 'Green' ? 'selected' : '' }}>Green</option>
                                                        <option value="Gray" {{ old('eye_color_her', $coupleData['eye_color_her'] ?? '') === 'Gray' ? 'selected' : '' }}>Gray</option>
                                                        <option value="Hazel" {{ old('eye_color_her', $coupleData['eye_color_her'] ?? '') === 'Hazel' ? 'selected' : '' }}>Hazel</option>
                                                        <option value="Amber" {{ old('eye_color_her', $coupleData['eye_color_her'] ?? '') === 'Amber' ? 'selected' : '' }}>Amber</option>
                                                        <option value="Black" {{ old('eye_color_her', $coupleData['eye_color_her'] ?? '') === 'Black' ? 'selected' : '' }}>Black</option>
                                                        <option value="Other" {{ old('eye_color_her', $coupleData['eye_color_her'] ?? '') === 'Other' ? 'selected' : '' }}>Other</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Hair Color</label>
                                                    <select name="hair_color_her" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                                        <option value="">Select Hair Color</option>
                                                        <option value="Black" {{ old('hair_color_her', $coupleData['hair_color_her'] ?? '') === 'Black' ? 'selected' : '' }}>Black</option>
                                                        <option value="Brown" {{ old('hair_color_her', $coupleData['hair_color_her'] ?? '') === 'Brown' ? 'selected' : '' }}>Brown</option>
                                                        <option value="Blonde" {{ old('hair_color_her', $coupleData['hair_color_her'] ?? '') === 'Blonde' ? 'selected' : '' }}>Blonde</option>
                                                        <option value="Red" {{ old('hair_color_her', $coupleData['hair_color_her'] ?? '') === 'Red' ? 'selected' : '' }}>Red</option>
                                                        <option value="Gray" {{ old('hair_color_her', $coupleData['hair_color_her'] ?? '') === 'Gray' ? 'selected' : '' }}>Gray</option>
                                                        <option value="White" {{ old('hair_color_her', $coupleData['hair_color_her'] ?? '') === 'White' ? 'selected' : '' }}>White</option>
                                                        <option value="Auburn" {{ old('hair_color_her', $coupleData['hair_color_her'] ?? '') === 'Auburn' ? 'selected' : '' }}>Auburn</option>
                                                        <option value="Chestnut" {{ old('hair_color_her', $coupleData['hair_color_her'] ?? '') === 'Chestnut' ? 'selected' : '' }}>Chestnut</option>
                                                        <option value="Other" {{ old('hair_color_her', $coupleData['hair_color_her'] ?? '') === 'Other' ? 'selected' : '' }}>Other</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tattoos</label>
                                                    <select name="tattoos_her" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                                        <option value="">Select...</option>
                                                        <option value="yes" {{ old('tattoos_her', $coupleData['tattoos_her'] ?? '') === 'yes' ? 'selected' : '' }}>Yes</option>
                                                        <option value="no" {{ old('tattoos_her', $coupleData['tattoos_her'] ?? '') === 'no' ? 'selected' : '' }}>No</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Piercings</label>
                                                    <select name="piercings_her" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                                        <option value="">Select...</option>
                                                        <option value="yes" {{ old('piercings_her', $coupleData['piercings_her'] ?? '') === 'yes' ? 'selected' : '' }}>Yes</option>
                                                        <option value="no" {{ old('piercings_her', $coupleData['piercings_her'] ?? '') === 'no' ? 'selected' : '' }}>No</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Boob Size</label>
                                                    <input type="text" name="boob_size_her" value="{{ old('boob_size_her', $coupleData['boob_size_her'] ?? '') }}" placeholder="e.g., A, B, C, D, DD, etc." class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Him Personal Details -->
                                        <div class="border-2 border-blue-200 dark:border-blue-800 rounded-2xl p-6">
                                            <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                                <span class="text-blue-500">👨</span> Him Details
                                            </h4>
                                            
                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Weight (kg)</label>
                                                    <input type="number" name="weight_him" value="{{ old('weight_him', $coupleData['weight_him'] ?? '') }}" placeholder="kg" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Height (cm)</label>
                                                    <input type="number" name="height_him" value="{{ old('height_him', $coupleData['height_him'] ?? '') }}" placeholder="cm" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Body Type</label>
                                                    <select name="body_type_him" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                                        <option value="">Select Body Type</option>
                                                        <option value="Slim" {{ old('body_type_him', $coupleData['body_type_him'] ?? '') == 'Slim' ? 'selected' : '' }}>💪 Slim</option>
                                                        <option value="Athletic" {{ old('body_type_him', $coupleData['body_type_him'] ?? '') == 'Athletic' ? 'selected' : '' }}>🏃 Athletic</option>
                                                        <option value="Curvy" {{ old('body_type_him', $coupleData['body_type_him'] ?? '') == 'Curvy' ? 'selected' : '' }}>❤️ Curvy</option>
                                                        <option value="Average" {{ old('body_type_him', $coupleData['body_type_him'] ?? '') == 'Average' ? 'selected' : '' }}>👤 Average</option>
                                                        <option value="Plus Size" {{ old('body_type_him', $coupleData['body_type_him'] ?? '') == 'Plus Size' ? 'selected' : '' }}>👥 Plus Size</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Eye Color</label>
                                                    <select name="eye_color_him" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                                        <option value="">Select Eye Color</option>
                                                        <option value="Brown" {{ old('eye_color_him', $coupleData['eye_color_him'] ?? '') === 'Brown' ? 'selected' : '' }}>Brown</option>
                                                        <option value="Blue" {{ old('eye_color_him', $coupleData['eye_color_him'] ?? '') === 'Blue' ? 'selected' : '' }}>Blue</option>
                                                        <option value="Green" {{ old('eye_color_him', $coupleData['eye_color_him'] ?? '') === 'Green' ? 'selected' : '' }}>Green</option>
                                                        <option value="Gray" {{ old('eye_color_him', $coupleData['eye_color_him'] ?? '') === 'Gray' ? 'selected' : '' }}>Gray</option>
                                                        <option value="Hazel" {{ old('eye_color_him', $coupleData['eye_color_him'] ?? '') === 'Hazel' ? 'selected' : '' }}>Hazel</option>
                                                        <option value="Amber" {{ old('eye_color_him', $coupleData['eye_color_him'] ?? '') === 'Amber' ? 'selected' : '' }}>Amber</option>
                                                        <option value="Black" {{ old('eye_color_him', $coupleData['eye_color_him'] ?? '') === 'Black' ? 'selected' : '' }}>Black</option>
                                                        <option value="Other" {{ old('eye_color_him', $coupleData['eye_color_him'] ?? '') === 'Other' ? 'selected' : '' }}>Other</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Hair Color</label>
                                                    <select name="hair_color_him" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                                        <option value="">Select Hair Color</option>
                                                        <option value="Black" {{ old('hair_color_him', $coupleData['hair_color_him'] ?? '') === 'Black' ? 'selected' : '' }}>Black</option>
                                                        <option value="Brown" {{ old('hair_color_him', $coupleData['hair_color_him'] ?? '') === 'Brown' ? 'selected' : '' }}>Brown</option>
                                                        <option value="Blonde" {{ old('hair_color_him', $coupleData['hair_color_him'] ?? '') === 'Blonde' ? 'selected' : '' }}>Blonde</option>
                                                        <option value="Red" {{ old('hair_color_him', $coupleData['hair_color_him'] ?? '') === 'Red' ? 'selected' : '' }}>Red</option>
                                                        <option value="Gray" {{ old('hair_color_him', $coupleData['hair_color_him'] ?? '') === 'Gray' ? 'selected' : '' }}>Gray</option>
                                                        <option value="White" {{ old('hair_color_him', $coupleData['hair_color_him'] ?? '') === 'White' ? 'selected' : '' }}>White</option>
                                                        <option value="Auburn" {{ old('hair_color_him', $coupleData['hair_color_him'] ?? '') === 'Auburn' ? 'selected' : '' }}>Auburn</option>
                                                        <option value="Chestnut" {{ old('hair_color_him', $coupleData['hair_color_him'] ?? '') === 'Chestnut' ? 'selected' : '' }}>Chestnut</option>
                                                        <option value="Other" {{ old('hair_color_him', $coupleData['hair_color_him'] ?? '') === 'Other' ? 'selected' : '' }}>Other</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tattoos</label>
                                                    <select name="tattoos_him" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                                        <option value="">Select...</option>
                                                        <option value="yes" {{ old('tattoos_him', $coupleData['tattoos_him'] ?? '') === 'yes' ? 'selected' : '' }}>Yes</option>
                                                        <option value="no" {{ old('tattoos_him', $coupleData['tattoos_him'] ?? '') === 'no' ? 'selected' : '' }}>No</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Piercings</label>
                                                    <select name="piercings_him" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                                        <option value="">Select...</option>
                                                        <option value="yes" {{ old('piercings_him', $coupleData['piercings_him'] ?? '') === 'yes' ? 'selected' : '' }}>Yes</option>
                                                        <option value="no" {{ old('piercings_him', $coupleData['piercings_him'] ?? '') === 'no' ? 'selected' : '' }}>No</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Dick Size</label>
                                                    <input type="text" name="dick_size_him" value="{{ old('dick_size_him', $coupleData['dick_size_him'] ?? '') }}" placeholder="e.g., inches or cm" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Location -->
                                <div class="mb-8">
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Location</h3>
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Location</label>
                                            <input type="text" id="home_location" name="home_location" value="{{ old('home_location', $profile && $profile->home_location ? $profile->home_location : '') }}" placeholder="Search for your city..." class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                            <input type="hidden" id="home_location_lat" name="home_location_lat">
                                            <input type="hidden" id="home_location_lng" name="home_location_lng">
                                        </div>
                                        
                                        <!-- Country and City Fields -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Country</label>
                                                <input type="text" id="country" name="country" value="{{ old('country', $profile && $profile->country ? $profile->country : '') }}" placeholder="Country will auto-fill from location..." class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">City</label>
                                                <input type="text" id="city" name="city" value="{{ old('city', $profile && $profile->city ? $profile->city : '') }}" placeholder="City will auto-fill from location..." class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                            </div>
                                        </div>
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



                            <!-- Action Buttons -->
                            <div class="flex items-center justify-end gap-3 border-t border-gray-200 dark:border-gray-700 pt-6 mt-6">
                                <a href="{{ route('account.profile') }}" class="rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-6 py-2 text-sm font-semibold text-gray-700 dark:text-gray-300 shadow-sm transition-colors hover:bg-gray-50 dark:hover:bg-gray-600">
                                    Cancel
                                </a>
                                <button type="submit" class="rounded-lg bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-2 text-sm font-semibold text-white shadow-sm transition-all hover:from-purple-700 hover:to-pink-700">
                                    Save Changes
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
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
                const preview = document.getElementById('profile-preview');
                if (preview) {
                    if (preview.tagName === 'IMG') {
                        preview.src = e.target.result;
                    } else {
                        // Replace div with img
                        const newImg = document.createElement('img');
                        newImg.id = 'profile-preview';
                        newImg.src = e.target.result;
                        newImg.className = 'w-32 h-32 rounded-full object-cover border-4 border-white shadow-lg';
                        newImg.alt = '{{ $user->name }}';
                        preview.parentNode.replaceChild(newImg, preview);
                    }
                }
            };
            reader.readAsDataURL(file);
        }
    });


    // Google Maps Autocomplete for Location
    @php
        $googleMapsApiKey = config('services.google_maps.api_key');
    @endphp
    @if($googleMapsApiKey)
    // Load Google Maps API with proper async loading
    (function() {
        const script = document.createElement('script');
        script.src = 'https://maps.googleapis.com/maps/api/js?key={{ $googleMapsApiKey }}&loading=async&libraries=places&callback=initLocationAutocompleteCallback';
        script.async = true;
        script.defer = true;
        document.head.appendChild(script);
    })();

    // Function to extract country and city from address components
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

    // Callback function for when Google Maps loads
    window.initLocationAutocompleteCallback = function() {
        if (typeof google !== 'undefined' && google.maps && google.maps.places) {
            const locationInput = document.getElementById('home_location');
            if (locationInput) {
                try {
                    const autocomplete = new google.maps.places.Autocomplete(locationInput, {
                        types: ['(cities)'],
                        fields: ['formatted_address', 'geometry', 'name', 'address_components']
                    });

                    autocomplete.addListener('place_changed', function() {
                        const place = autocomplete.getPlace();
                        if (!place.geometry) {
                            return;
                        }

                        // Update hidden fields with coordinates
                        const latInput = document.getElementById('home_location_lat');
                        const lngInput = document.getElementById('home_location_lng');
                        if (latInput) latInput.value = place.geometry.location.lat();
                        if (lngInput) lngInput.value = place.geometry.location.lng();

                        // Update input value
                        locationInput.value = place.formatted_address || place.name;

                        // Extract country and city from address components
                        extractCountryAndCity(place.address_components);
                    });
                } catch (error) {
                    console.error('Error initializing location autocomplete:', error);
                    if (error.message && error.message.includes('legacy')) {
                        console.error('⚠️ PLACES API NOT ENABLED:');
                        console.error('Please enable "Places API" (legacy) in Google Cloud Console:');
                        console.error('1. Go to: https://console.cloud.google.com/apis/library');
                        console.error('2. Search for "Places API" (without "New")');
                        console.error('3. Click ENABLE');
                        console.error('4. Wait 1-5 minutes and refresh this page');
                    }
                }
            }
        }
    };
    @else
    console.warn('Google Maps API key is not configured. Please add GOOGLE_MAPS_API_KEY to your .env file.');
    @endif
</script>
@endpush
@endsection
