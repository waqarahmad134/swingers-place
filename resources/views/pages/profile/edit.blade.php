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
                                <button type="button" onclick="switchTab('photos')" id="tab-photos" class="tab-button px-6 py-3 text-sm font-semibold rounded-lg transition-colors text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <i class="ri-image-line mr-2"></i>
                                    Photos
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
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Sexuality</label>
                                                <select name="sexuality" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                                    <option value="">Select...</option>
                                                    <option value="straight" {{ old('sexuality', $profile && $profile->sexuality ? $profile->sexuality : '') === 'straight' ? 'selected' : '' }}>Straight</option>
                                                    <option value="gay" {{ old('sexuality', $profile && $profile->sexuality ? $profile->sexuality : '') === 'gay' ? 'selected' : '' }}>Gay</option>
                                                    <option value="bisexual" {{ old('sexuality', $profile && $profile->sexuality ? $profile->sexuality : '') === 'bisexual' ? 'selected' : '' }}>Bisexual</option>
                                                    <option value="lesbian" {{ old('sexuality', $profile && $profile->sexuality ? $profile->sexuality : '') === 'lesbian' ? 'selected' : '' }}>Lesbian</option>
                                                    <option value="prefer_not_to_say" {{ old('sexuality', $profile && $profile->sexuality ? $profile->sexuality : '') === 'prefer_not_to_say' ? 'selected' : '' }}>Prefer not to say</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Relationship Status</label>
                                                <select name="relationship_status" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                                    <option value="">Select...</option>
                                                    <option value="single" {{ old('relationship_status', $profile && $profile->relationship_status ? $profile->relationship_status : '') === 'single' ? 'selected' : '' }}>Single</option>
                                                    <option value="in_relationship" {{ old('relationship_status', $profile && $profile->relationship_status ? $profile->relationship_status : '') === 'in_relationship' ? 'selected' : '' }}>In a Relationship</option>
                                                    <option value="married" {{ old('relationship_status', $profile && $profile->relationship_status ? $profile->relationship_status : '') === 'married' ? 'selected' : '' }}>Married</option>
                                                    <option value="open" {{ old('relationship_status', $profile && $profile->relationship_status ? $profile->relationship_status : '') === 'open' ? 'selected' : '' }}>Open</option>
                                                    <option value="prefer_not_to_say" {{ old('relationship_status', $profile && $profile->relationship_status ? $profile->relationship_status : '') === 'prefer_not_to_say' ? 'selected' : '' }}>Prefer not to say</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Relationship Orientation</label>
                                                <select name="relationship_orientation" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                                    <option value="">Select...</option>
                                                    <option value="monogamous" {{ old('relationship_orientation', $profile && $profile->relationship_orientation ? $profile->relationship_orientation : '') === 'monogamous' ? 'selected' : '' }}>Monogamous</option>
                                                    <option value="polyamorous" {{ old('relationship_orientation', $profile && $profile->relationship_orientation ? $profile->relationship_orientation : '') === 'polyamorous' ? 'selected' : '' }}>Polyamorous</option>
                                                    <option value="swinger" {{ old('relationship_orientation', $profile && $profile->relationship_orientation ? $profile->relationship_orientation : '') === 'swinger' ? 'selected' : '' }}>Swinger</option>
                                                    <option value="open" {{ old('relationship_orientation', $profile && $profile->relationship_orientation ? $profile->relationship_orientation : '') === 'open' ? 'selected' : '' }}>Open</option>
                                                    <option value="prefer_not_to_say" {{ old('relationship_orientation', $profile && $profile->relationship_orientation ? $profile->relationship_orientation : '') === 'prefer_not_to_say' ? 'selected' : '' }}>Prefer not to say</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Smoking</label>
                                                <select name="smoking" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                                    <option value="">Select...</option>
                                                    <option value="no" {{ old('smoking', $profile && $profile->smoking ? $profile->smoking : '') === 'no' ? 'selected' : '' }}>No</option>
                                                    <option value="yes" {{ old('smoking', $profile && $profile->smoking ? $profile->smoking : '') === 'yes' ? 'selected' : '' }}>Yes</option>
                                                    <option value="prefer_not_to_say" {{ old('smoking', $profile && $profile->smoking ? $profile->smoking : '') === 'prefer_not_to_say' ? 'selected' : '' }}>Prefer not to say</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Piercings</label>
                                                <select name="piercings" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                                    <option value="">Select...</option>
                                                    <option value="no" {{ old('piercings', $profile && $profile->piercings ? $profile->piercings : '') === 'no' ? 'selected' : '' }}>No</option>
                                                    <option value="yes" {{ old('piercings', $profile && $profile->piercings ? $profile->piercings : '') === 'yes' ? 'selected' : '' }}>Yes</option>
                                                    <option value="prefer_not_to_say" {{ old('piercings', $profile && $profile->piercings ? $profile->piercings : '') === 'prefer_not_to_say' ? 'selected' : '' }}>Prefer not to say</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tattoos</label>
                                                <select name="tattoos" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                                    <option value="">Select...</option>
                                                    <option value="no" {{ old('tattoos', $profile && $profile->tattoos ? $profile->tattoos : '') === 'no' ? 'selected' : '' }}>No</option>
                                                    <option value="yes" {{ old('tattoos', $profile && $profile->tattoos ? $profile->tattoos : '') === 'yes' ? 'selected' : '' }}>Yes</option>
                                                    <option value="prefer_not_to_say" {{ old('tattoos', $profile && $profile->tattoos ? $profile->tattoos : '') === 'prefer_not_to_say' ? 'selected' : '' }}>Prefer not to say</option>
                                                </select>
                                            </div>
                                            @php
                                                $languages = $profile && $profile->languages 
                                                    ? (is_array($profile->languages) ? $profile->languages : json_decode($profile->languages, true) ?? [])
                                                    : [];
                                            @endphp
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Languages Spoken</label>
                                                @php
                                                    $languageOptions = [
                                                        'Afrikaans', 'Akan', 'Albanian', 'Amharic', 'Arabic', 'Aragonese', 'Armenian', 'Assamese', 'Aymara', 'Azerbaijani',
                                                        'Balinese', 'Bambara', 'Basque', 'Belarusian', 'Bengali', 'Berber (Tamazight)', 'Bislama', 'Bosnian', 'Bulgarian', 'Burmese',
                                                        'Catalan', 'Cebuano', 'Chichewa', 'Chinese (Mandarin)', 'Corsican', 'Croatian', 'Czech',
                                                        'Danish', 'Dari', 'Divehi (Dhivehi)', 'Dutch', 'Dzongkha',
                                                        'English', 'Esperanto', 'Estonian', 'Ewe',
                                                        'Faroese', 'Fijian', 'Filipino (Tagalog)', 'Finnish', 'French', 'Frisian', 'Fulah',
                                                        'Galician', 'Georgian', 'German', 'Greek', 'Greenlandic (Kalaallisut)', 'Guarani', 'Gujarati',
                                                        'Haitian Creole', 'Hausa', 'Hebrew', 'Herero', 'Hindi', 'Hiri Motu', 'Hungarian',
                                                        'Icelandic', 'Igbo', 'Indonesian', 'Interlingua', 'Irish', 'Italian',
                                                        'Japanese', 'Javanese',
                                                        'Kannada', 'Kazakh', 'Khmer', 'Kikongo', 'Kinyarwanda', 'Kirundi', 'Korean', 'Kurdish', 'Kyrgyz',
                                                        'Lao', 'Latin', 'Latvian', 'Lingala', 'Lithuanian', 'Luba-Katanga', 'Luxembourgish',
                                                        'Macedonian', 'Maithili', 'Malagasy', 'Malay', 'Malayalam', 'Maltese', 'Manipuri', 'Maori', 'Marathi', 'Mongolian', 'Montenegrin',
                                                        'Nauruan', 'Nepali', 'Northern Sotho', 'Norwegian',
                                                        'Occitan', 'Odia (Oriya)', 'Oromo', 'Ossetian',
                                                        'Pashto', 'Persian (Farsi)', 'Polish', 'Portuguese', 'Punjabi',
                                                        'Quechua',
                                                        'Romanian', 'Romansh', 'Rundi', 'Russian',
                                                        'Samoan', 'Sango', 'Sanskrit', 'Scottish Gaelic', 'Serbian', 'Sesotho', 'Setswana', 'Shona', 'Sindhi', 'Sinhala', 'Slovak', 'Slovenian', 'Somali', 'Spanish', 'Swahili', 'Swati', 'Swedish',
                                                        'Tajik', 'Tamil', 'Tatar', 'Telugu', 'Thai', 'Tigrinya', 'Tongan', 'Tsonga', 'Tshiluba', 'Tunisian Arabic', 'Turkish', 'Turkmen',
                                                        'Ukrainian', 'Urdu', 'Uyghur', 'Uzbek',
                                                        'Venda', 'Vietnamese',
                                                        'Walloon', 'Welsh', 'Wolof',
                                                        'Xhosa',
                                                        'Xitsonga',
                                                        'Yiddish', 'Yoruba',
                                                        'Zhuang', 'Zulu'
                                                    ];
                                                    $selectedLanguages = array_map('strtolower', $languages);
                                                @endphp
                                                <div class="relative" id="language-select-container">
                                                    <!-- Selected Languages Display -->
                                                    <div class="min-h-[42px] w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-2 flex flex-wrap gap-2 items-center cursor-pointer" id="language-select-trigger">
                                                        <span id="language-placeholder" class="text-gray-500 dark:text-gray-400 text-sm">Select languages...</span>
                                                        <div id="language-selected-tags" class="flex flex-wrap gap-2 hidden"></div>
                                                        <i class="ri-arrow-down-s-line ml-auto text-gray-400" id="language-arrow"></i>
                                                    </div>
                                                    
                                                    <!-- Dropdown -->
                                                    <div id="language-dropdown" class="hidden absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg shadow-lg max-h-60 overflow-hidden">
                                                        <!-- Search Input -->
                                                        <div class="p-2 border-b border-gray-200 dark:border-gray-700">
                                                            <input type="text" id="language-search" placeholder="Search languages..." class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500">
                                                        </div>
                                                        
                                                        <!-- Options List -->
                                                        <div class="overflow-y-auto max-h-48" id="language-options-list">
                                                            @foreach($languageOptions as $lang)
                                                                @php
                                                                    $langLower = strtolower($lang);
                                                                    $isSelected = in_array($langLower, $selectedLanguages);
                                                                @endphp
                                                                <label class="language-option flex items-center px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer" data-lang="{{ $langLower }}" data-lang-name="{{ $lang }}">
                                                                    <input type="checkbox" 
                                                                           name="languages[]" 
                                                                           value="{{ $langLower }}" 
                                                                           {{ $isSelected ? 'checked' : '' }}
                                                                           class="language-checkbox rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                                                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $lang }}</span>
                                                                </label>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Looks are important?</label>
                                                <select name="looks_important" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                                    <option value="">Select...</option>
                                                    <option value="no" {{ old('looks_important', $profile && $profile->looks_important ? $profile->looks_important : '') === 'no' ? 'selected' : '' }}>No</option>
                                                    <option value="yes" {{ old('looks_important', $profile && $profile->looks_important ? $profile->looks_important : '') === 'yes' ? 'selected' : '' }}>Yes</option>
                                                    <option value="prefer_not_to_say" {{ old('looks_important', $profile && $profile->looks_important ? $profile->looks_important : '') === 'prefer_not_to_say' ? 'selected' : '' }}>Prefer not to say</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Intelligence is important?</label>
                                                <select name="intelligence_important" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 focus:border-purple-500 focus:ring-purple-500">
                                                    <option value="">Select...</option>
                                                    <option value="no" {{ old('intelligence_important', $profile && $profile->intelligence_important ? $profile->intelligence_important : '') === 'no' ? 'selected' : '' }}>No</option>
                                                    <option value="yes" {{ old('intelligence_important', $profile && $profile->intelligence_important ? $profile->intelligence_important : '') === 'yes' ? 'selected' : '' }}>Yes</option>
                                                    <option value="prefer_not_to_say" {{ old('intelligence_important', $profile && $profile->intelligence_important ? $profile->intelligence_important : '') === 'prefer_not_to_say' ? 'selected' : '' }}>Prefer not to say</option>
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

                            <!-- Photos Tab -->
                            <div id="content-photos" class="tab-content hidden">
                                @php
                                    $albumPhotos = $profile && $profile->album_photos 
                                        ? (is_array($profile->album_photos) ? $profile->album_photos : json_decode($profile->album_photos, true) ?? [])
                                        : [];
                                    
                                    // Separate photos by category if structured, otherwise treat all as album
                                    $adultPhotos = isset($albumPhotos['adult']) ? $albumPhotos['adult'] : [];
                                    $nonAdultPhotos = isset($albumPhotos['non_adult']) ? $albumPhotos['non_adult'] : [];
                                    $albumPhotosList = isset($albumPhotos['album']) ? $albumPhotos['album'] : (isset($albumPhotos['adult']) || isset($albumPhotos['non_adult']) ? [] : $albumPhotos);
                                @endphp

                                <!-- Non-Adult Photos Section -->
                                <div class="mb-8">
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                        <i class="ri-image-line text-purple-600"></i>
                                        Non-Adult Photos
                                    </h3>
                                    <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-6">
                                        <input type="file" id="non_adult_photos" name="non_adult_photos[]" accept="image/*" multiple class="hidden" onchange="handlePhotoUpload(this, 'non-adult-preview')">
                                        <label for="non_adult_photos" class="cursor-pointer flex flex-col items-center justify-center py-8 text-center hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-lg transition-colors">
                                            <i class="ri-upload-cloud-2-line text-4xl text-gray-400 mb-2"></i>
                                            <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Upload Non-Adult Photos</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Click to select or drag and drop multiple images</p>
                                        </label>
                                        
                                        <!-- Preview Grid -->
                                        <div id="non-adult-preview" class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                                            @if(!empty($nonAdultPhotos))
                                                @foreach($nonAdultPhotos as $index => $photo)
                                                    <div class="relative group" data-photo-path="{{ $photo }}">
                                                        <img src="{{ asset('storage/' . $photo) }}" alt="Non-adult photo" class="w-full h-32 object-cover rounded-lg border-2 border-gray-200 dark:border-gray-700">
                                                        <button type="button" onclick="removePhoto('non_adult', '{{ $photo }}', this)" class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                                                            <i class="ri-delete-bin-line text-sm"></i>
                                                        </button>
                                                        <input type="hidden" name="existing_non_adult_photos[]" value="{{ $photo }}">
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Adult Photos Section -->
                                <div class="mb-8">
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                        <i class="ri-image-line text-red-600"></i>
                                        Adult Photos
                                        <span class="text-xs bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 px-2 py-0.5 rounded-full font-semibold">XXX</span>
                                    </h3>
                                    <div class="border-2 border-dashed border-red-300 dark:border-red-700 rounded-xl p-6 bg-red-50/50 dark:bg-red-900/10">
                                        <input type="file" id="adult_photos" name="adult_photos[]" accept="image/*" multiple class="hidden" onchange="handlePhotoUpload(this, 'adult-preview')">
                                        <label for="adult_photos" class="cursor-pointer flex flex-col items-center justify-center py-8 text-center hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                                            <i class="ri-upload-cloud-2-line text-4xl text-red-400 mb-2"></i>
                                            <p class="text-sm font-semibold text-red-700 dark:text-red-300 mb-1">Upload Adult Photos</p>
                                            <p class="text-xs text-red-500 dark:text-red-400">Click to select or drag and drop multiple images</p>
                                        </label>
                                        
                                        <!-- Preview Grid -->
                                        <div id="adult-preview" class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                                            @if(!empty($adultPhotos))
                                                @foreach($adultPhotos as $index => $photo)
                                                    <div class="relative group" data-photo-path="{{ $photo }}">
                                                        <img src="{{ asset('storage/' . $photo) }}" alt="Adult photo" class="w-full h-32 object-cover rounded-lg border-2 border-red-200 dark:border-red-700">
                                                        <button type="button" onclick="removePhoto('adult', '{{ $photo }}', this)" class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                                                            <i class="ri-delete-bin-line text-sm"></i>
                                                        </button>
                                                        <input type="hidden" name="existing_adult_photos[]" value="{{ $photo }}">
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Album Photos Section -->
                                <div class="mb-8">
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                        <i class="ri-folder-image-line text-blue-600"></i>
                                        Album Photos
                                    </h3>
                                    <div class="border-2 border-dashed border-blue-300 dark:border-blue-700 rounded-xl p-6 bg-blue-50/50 dark:bg-blue-900/10">
                                        <input type="file" id="album_photos" name="album_photos[]" accept="image/*" multiple class="hidden" onchange="handlePhotoUpload(this, 'album-preview')">
                                        <label for="album_photos" class="cursor-pointer flex flex-col items-center justify-center py-8 text-center hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors">
                                            <i class="ri-upload-cloud-2-line text-4xl text-blue-400 mb-2"></i>
                                            <p class="text-sm font-semibold text-blue-700 dark:text-blue-300 mb-1">Upload Album Photos</p>
                                            <p class="text-xs text-blue-500 dark:text-blue-400">Click to select or drag and drop multiple images</p>
                                        </label>
                                        
                                        <!-- Preview Grid -->
                                        <div id="album-preview" class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                                            @if(!empty($albumPhotosList))
                                                @foreach($albumPhotosList as $index => $photo)
                                                    <div class="relative group" data-photo-path="{{ $photo }}">
                                                        <img src="{{ asset('storage/' . $photo) }}" alt="Album photo" class="w-full h-32 object-cover rounded-lg border-2 border-blue-200 dark:border-blue-700">
                                                        <button type="button" onclick="removePhoto('album', '{{ $photo }}', this)" class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                                                            <i class="ri-delete-bin-line text-sm"></i>
                                                        </button>
                                                        <input type="hidden" name="existing_album_photos[]" value="{{ $photo }}">
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
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
// Language Select Dropdown
(function() {
    const container = document.getElementById('language-select-container');
    if (!container) return;
    
    const trigger = document.getElementById('language-select-trigger');
    const dropdown = document.getElementById('language-dropdown');
    const searchInput = document.getElementById('language-search');
    const placeholder = document.getElementById('language-placeholder');
    const selectedTags = document.getElementById('language-selected-tags');
    const checkboxes = container.querySelectorAll('.language-checkbox');
    const options = container.querySelectorAll('.language-option');
    
    let isOpen = false;
    let selectedLanguages = [];
    
    // Initialize selected languages from checked checkboxes
    checkboxes.forEach(cb => {
        if (cb.checked) {
            const lang = cb.value;
            selectedLanguages.push(lang);
            updateSelectedTags();
        }
    });
    
    // Toggle dropdown
    trigger.addEventListener('click', function(e) {
        e.stopPropagation();
        isOpen = !isOpen;
        if (isOpen) {
            dropdown.classList.remove('hidden');
            searchInput.focus();
        } else {
            dropdown.classList.add('hidden');
        }
    });
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!container.contains(e.target)) {
            isOpen = false;
            dropdown.classList.add('hidden');
        }
    });
    
    // Search functionality
    searchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        options.forEach(option => {
            const langName = option.getAttribute('data-lang-name').toLowerCase();
            const langValue = option.getAttribute('data-lang').toLowerCase();
            if (langName.includes(searchTerm) || langValue.includes(searchTerm)) {
                option.style.display = 'flex';
            } else {
                option.style.display = 'none';
            }
        });
    });
    
    // Handle checkbox changes
    checkboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            const lang = this.value;
            if (this.checked) {
                if (!selectedLanguages.includes(lang)) {
                    selectedLanguages.push(lang);
                }
            } else {
                selectedLanguages = selectedLanguages.filter(l => l !== lang);
            }
            updateSelectedTags();
        });
    });
    
    // Update selected tags display
    function updateSelectedTags() {
        if (selectedLanguages.length === 0) {
            placeholder.classList.remove('hidden');
            selectedTags.classList.add('hidden');
        } else {
            placeholder.classList.add('hidden');
            selectedTags.classList.remove('hidden');
            selectedTags.innerHTML = '';
            
            selectedLanguages.forEach(lang => {
                const langName = Array.from(options).find(opt => opt.getAttribute('data-lang') === lang)?.getAttribute('data-lang-name') || lang;
                const tag = document.createElement('span');
                tag.className = 'inline-flex items-center gap-1 px-2 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 rounded-md text-xs';
                tag.innerHTML = `
                    <span>${langName}</span>
                    <button type="button" class="hover:text-purple-900 dark:hover:text-purple-100 remove-language" data-lang="${lang}">
                        <i class="ri-close-line text-sm"></i>
                    </button>
                `;
                selectedTags.appendChild(tag);
            });
            
            // Add remove functionality
            selectedTags.querySelectorAll('.remove-language').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const lang = this.getAttribute('data-lang');
                    const checkbox = container.querySelector(`input[value="${lang}"]`);
                    if (checkbox) {
                        checkbox.checked = false;
                        checkbox.dispatchEvent(new Event('change'));
                    }
                });
            });
        }
    }
    
    // Initialize
    updateSelectedTags();
})();
</script>
<script>
    function switchTab(tab) {
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
            content.style.display = 'none';
        });
        
        // Remove active class from all tabs
        document.querySelectorAll('.tab-button').forEach(button => {
            button.classList.remove('active', 'bg-gray-800', 'dark:bg-gray-700', 'text-white');
            button.classList.add('text-gray-600', 'dark:text-gray-400', 'hover:bg-gray-100', 'dark:hover:bg-gray-700');
        });
        
        // Show selected tab content
        const targetContent = document.getElementById('content-' + tab);
        if (targetContent) {
            targetContent.classList.remove('hidden');
            targetContent.style.display = 'block';
        }
        
        // Add active class to selected tab
        const activeTab = document.getElementById('tab-' + tab);
        if (activeTab) {
            activeTab.classList.add('active', 'bg-gray-800', 'dark:bg-gray-700', 'text-white');
            activeTab.classList.remove('text-gray-600', 'dark:text-gray-400', 'hover:bg-gray-100', 'dark:hover:bg-gray-700');
        }
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

    // Handle photo uploads for different categories
    function handlePhotoUpload(input, previewId) {
        const files = input.files;
        const previewContainer = document.getElementById(previewId);
        
        if (!previewContainer) return;
        
        Array.from(files).forEach(file => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const photoDiv = document.createElement('div');
                    photoDiv.className = 'relative group';
                    
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'w-full h-32 object-cover rounded-lg border-2 ' + 
                        (previewId.includes('adult') ? 'border-red-200 dark:border-red-700' : 
                         previewId.includes('non') ? 'border-gray-200 dark:border-gray-700' : 
                         'border-blue-200 dark:border-blue-700');
                    img.alt = 'Photo preview';
                    
                    const deleteBtn = document.createElement('button');
                    deleteBtn.type = 'button';
                    deleteBtn.className = 'absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-1.5 opacity-0 group-hover:opacity-100 transition-opacity';
                    deleteBtn.innerHTML = '<i class="ri-delete-bin-line text-sm"></i>';
                    deleteBtn.onclick = function() {
                        photoDiv.remove();
                    };
                    
                    photoDiv.appendChild(img);
                    photoDiv.appendChild(deleteBtn);
                    previewContainer.appendChild(photoDiv);
                };
                reader.readAsDataURL(file);
            }
        });
        
        // Reset input to allow uploading same file again
        input.value = '';
    }

    // Remove existing photo
    function removePhoto(category, photoPath, buttonElement) {
        if (confirm('Are you sure you want to delete this photo?')) {
            // Find the photo container
            const photoDiv = buttonElement.closest('.relative.group');
            if (photoDiv) {
                // Create hidden input to mark photo for deletion
                const deleteInput = document.createElement('input');
                deleteInput.type = 'hidden';
                deleteInput.name = 'deleted_' + category + '_photos[]';
                deleteInput.value = photoPath;
                document.getElementById('profile-form').appendChild(deleteInput);
                
                // Remove the hidden input for existing photo
                const existingInput = photoDiv.querySelector('input[type="hidden"][name*="existing"]');
                if (existingInput) {
                    existingInput.remove();
                }
                
                // Remove the photo preview
                photoDiv.remove();
            }
        }
    }


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
