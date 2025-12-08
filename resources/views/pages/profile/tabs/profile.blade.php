<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Main Profile Header Section -->
    <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <!-- Top Bar with Username, Age, Location & Edit Button -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                <!-- Left: User Info -->
                <div class="flex flex-col md:flex-row md:items-center gap-4">
                    <div class="flex items-center gap-3">
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                            {{ strtoupper($user->username ?? $user->name) }}
                        </h1>
                        @if($age)
                            <span class="text-xl md:text-2xl font-semibold text-gray-700 dark:text-gray-300">|</span>
                            <span class="text-xl md:text-2xl font-bold text-[#9810FA] dark:text-purple-400">{{ $age }}</span>
                        @endif
                        @if($profile && $profile->home_location)
                            <span class="text-xl md:text-2xl font-semibold text-gray-700 dark:text-gray-300">|</span>
                            <span class="text-lg md:text-xl font-semibold text-gray-600 dark:text-gray-400">
                                {{ $profile->city ?? explode(',', $profile->home_location)[0] ?? '' }}, {{ $profile->country ?? 'IND' }} | 0 mi
                            </span>
            @endif
                    </div>
                </div>

                <!-- Right: Edit Profile Button (only on own profile) -->
                @if(isset($isOwnProfile) && $isOwnProfile)
                    <a href="{{ route('account.profile.edit') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-[#9810FA] to-[#E60076] hover:from-[#8810EA] hover:to-[#D60066] text-white px-6 py-3 rounded-full text-sm font-semibold shadow-lg transition-all hover:shadow-xl hover:scale-105">
                        <i class="ri-pencil-line text-lg"></i>
                        <span>Edit Profile</span>
                    </a>
                @endif
        </div>

            <!-- Looking For Section -->
            @php
                $preferences = $profile && $profile->preferences 
                    ? (is_array($profile->preferences) ? $profile->preferences : json_decode($profile->preferences, true) ?? [])
                    : [];
                // Couple data logic
                $isCouple = ($profile && $profile->category === 'couple');
                $coupleData = $coupleData ?? ($profile && $profile->couple_data ? (is_array($profile->couple_data) ? $profile->couple_data : json_decode($profile->couple_data, true) ?? []) : []);
            @endphp
            @if(!empty($preferences))
                <div class="flex flex-col md:flex-row md:items-center gap-4 mb-6">
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wide">Looking For:</span>
                        <span class="text-base font-semibold text-[#9810FA] dark:text-purple-400 capitalize">
                            {{ str_replace('_', ' ', $preferences[0] ?? '') }}
                        </span>
                    </div>
                    <!-- Gender Preference Icons -->
                    <div class="flex items-center gap-2">
                        @if($profile && $profile->category === 'couple')
                            <div class="flex items-center gap-1">
                                <div class="w-6 h-6 rounded-full bg-pink-400 border-2 border-white shadow-md"></div>
                                <div class="w-6 h-6 rounded-full bg-pink-400 border-2 border-white shadow-md"></div>
                            </div>
                            <div class="flex items-center gap-1">
                                <div class="w-6 h-6 rounded-full bg-blue-400 border-2 border-white shadow-md"></div>
                                <div class="w-6 h-6 rounded-full bg-blue-400 border-2 border-white shadow-md"></div>
                            </div>
                        @elseif($profile && $profile->category === 'single_female')
                            <div class="flex items-center gap-1">
                                <div class="w-6 h-6 rounded-full bg-pink-400 border-2 border-white shadow-md"></div>
                                <div class="w-6 h-6 rounded-full bg-pink-400 border-2 border-white shadow-md"></div>
                    </div>
                        @elseif($profile && $profile->category === 'single_male')
                            <div class="flex items-center gap-1">
                                <div class="w-6 h-6 rounded-full bg-blue-400 border-2 border-white shadow-md"></div>
                                <div class="w-6 h-6 rounded-full bg-blue-400 border-2 border-white shadow-md"></div>
                            </div>
                        @else
                            <div class="flex items-center gap-1">
                                <div class="w-6 h-6 rounded-full bg-pink-400 border-2 border-white shadow-md"></div>
                                <div class="w-6 h-6 rounded-full bg-blue-400 border-2 border-white shadow-md"></div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
        </div>

    <!-- Main Content Area -->
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Profile Picture & Bio -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Profile Picture Card -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="relative">
                        @if($profile && $profile->profile_photo)
                            <img src="{{ asset('storage/' . $profile->profile_photo) }}" alt="{{ $user->name }}" class="w-full h-96 object-cover">
                        @elseif($user->profile_image)
                            <img src="{{ asset('storage/' . $user->profile_image) }}" alt="{{ $user->name }}" class="w-full h-96 object-cover">
                        @else
                            <div class="w-full h-96 bg-gradient-to-br from-purple-400 via-pink-400 to-orange-400 flex items-center justify-center">
                                <div class="text-white text-6xl font-bold">
                                    {{ strtoupper(substr($user->first_name ?? $user->name, 0, 1)) }}{{ strtoupper(substr($user->last_name ?? '', 0, 1)) }}
                                </div>
                </div>
                        @endif
                
                        <!-- Photo Count Badge -->
                    @php
                        $photoCount = 0;
                        if ($profile && $profile->album_photos) {
                            $photos = is_array($profile->album_photos) ? $profile->album_photos : json_decode($profile->album_photos, true) ?? [];
                            $photoCount = is_array($photos) ? count($photos) : 0;
                        }
                            $totalPhotos = $photoCount + (($profile && $profile->profile_photo) || $user->profile_image ? 1 : 0);
                    @endphp
                        <div class="absolute top-4 right-4 bg-black/70 backdrop-blur-sm text-white px-4 py-2 rounded-full flex items-center gap-2 text-sm font-semibold">
                            <i class="ri-camera-line"></i>
                            <span>{{ $totalPhotos }}</span>
                </div>
                
                        <!-- Online Status Indicator -->
                        <div class="absolute bottom-4 left-4 flex items-center gap-2 bg-black/70 backdrop-blur-sm text-white px-3 py-1.5 rounded-full text-xs font-medium">
                            <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                            <span>Online</span>
                </div>
                
                        <!-- Verified Badge -->
                        @if($user->email_verified_at)
                            <div class="absolute top-4 left-4 bg-blue-500 text-white px-3 py-1.5 rounded-full flex items-center gap-1.5 text-xs font-semibold shadow-lg">
                                <i class="ri-checkbox-circle-fill"></i>
                                <span>Verified</span>
                            </div>
                        @endif
                </div>
                
                    <!-- Action Buttons -->
                    <div class="p-4 bg-gray-50 dark:bg-gray-700/50">
                        <div class="grid grid-cols-2 gap-2">
                            @if(!isset($isOwnProfile) || !$isOwnProfile)
                                <button class="flex items-center justify-center gap-2 bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 text-white px-4 py-3 rounded-xl text-sm font-semibold transition-all hover:shadow-lg">
                                    <i class="ri-heart-fill"></i>
                                    <span>Like</span>
                                </button>
                                <a href="{{ auth()->check() ? route('messages.show', $user->id) : route('login') }}" class="flex items-center justify-center gap-2 bg-gradient-to-r from-[#9810FA] to-[#E60076] hover:from-[#8810EA] hover:to-[#D60066] text-white px-4 py-3 rounded-xl text-sm font-semibold transition-all hover:shadow-lg">
                                    <i class="ri-chat-1-line"></i>
                                    <span>Message</span>
                                </a>
                            @endif
                </div>
            </div>
        </div>

                <!-- Bio Section -->
                @if($profile && $profile->bio)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                            <i class="ri-file-text-line text-[#9810FA]"></i>
                            <span>BIO</span>
                        </h2>
                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed text-base">{{ $profile->bio }}</p>
                    </div>
                @endif

                <!-- Quick Stats -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <i class="ri-bar-chart-line text-[#9810FA]"></i>
                        <span>Quick Stats</span>
                    </h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center p-4 bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 rounded-xl">
                            <div class="text-2xl font-bold text-[#9810FA] dark:text-purple-400">156</div>
                            <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">Likes</div>
                        </div>
                        <div class="text-center p-4 bg-gradient-to-br from-blue-50 to-cyan-50 dark:from-blue-900/20 dark:to-cyan-900/20 rounded-xl">
                            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $totalPhotos }}</div>
                            <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">Photos</div>
                        </div>
                        <div class="text-center p-4 bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl">
                            <div class="text-2xl font-bold text-green-600 dark:text-green-400">89</div>
                            <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">Friends</div>
                        </div>
                        <div class="text-center p-4 bg-gradient-to-br from-orange-50 to-amber-50 dark:from-orange-900/20 dark:to-amber-900/20 rounded-xl">
                            <div class="text-2xl font-bold text-orange-600 dark:text-orange-400">12</div>
                            <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">Validations</div>
                        </div>
                    </div>
        </div>
    </div>

            <!-- Right Column: Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- About Section -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                        <i class="ri-information-line text-[#9810FA]"></i>
                        <span>About</span>
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Languages -->
                    @php
                        $languages = $profile && $profile->languages 
                            ? (is_array($profile->languages) ? $profile->languages : json_decode($profile->languages, true) ?? [])
                            : [];
                    @endphp
                    @if(!empty($languages))
                            <div>
                                <div class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 flex items-center gap-2">
                                    <i class="ri-global-line text-[#9810FA]"></i>
                                    <span>Languages</span>
                                </div>
                            <div class="flex flex-wrap gap-2">
                                @foreach($languages as $language)
                                        <span class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-purple-100 to-pink-100 dark:from-purple-900/30 dark:to-pink-900/30 text-purple-700 dark:text-purple-300 rounded-full text-sm font-semibold capitalize border border-purple-200 dark:border-purple-700">
                                        {{ $language }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                        <!-- Category -->
                        @if($profile && $profile->category)
                            <div>
                                <div class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 flex items-center gap-2">
                                    <i class="ri-user-line text-[#9810FA]"></i>
                                    <span>Category</span>
                                </div>
                                <span class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-[#9810FA] to-[#E60076] text-white rounded-full text-sm font-semibold capitalize">
                                    {{ str_replace('_', ' ', $profile->category) }}
                                </span>
                            </div>
                        @endif

                        <!-- Sexuality -->
                        @if($profile && $profile->sexuality)
                            <div>
                                <div class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 flex items-center gap-2">
                                    <i class="ri-heart-line text-[#9810FA]"></i>
                                    <span>Sexuality</span>
                                </div>
                                <span class="inline-flex items-center px-4 py-2 bg-pink-100 dark:bg-pink-900/30 text-pink-700 dark:text-pink-300 rounded-full text-sm font-semibold capitalize">
                                    {{ $profile->sexuality }}
                                </span>
                            </div>
                        @endif

                        <!-- Relationship Status -->
                        @if($profile && $profile->relationship_status)
                            <div>
                                <div class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 flex items-center gap-2">
                                    <i class="ri-user-heart-line text-[#9810FA]"></i>
                                    <span>Relationship Status</span>
                                </div>
                                <span class="inline-flex items-center px-4 py-2 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-full text-sm font-semibold capitalize">
                                    {{ str_replace('_', ' ', $profile->relationship_status) }}
                                    </span>
                            </div>
                        @endif

                        <!-- Location -->
                        @if($profile && $profile->home_location)
                            <div>
                                <div class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 flex items-center gap-2">
                                    <i class="ri-map-pin-2-line text-[#9810FA]"></i>
                                    <span>Location</span>
                                </div>
                                <p class="text-gray-700 dark:text-gray-300">{{ $profile->home_location }}</p>
                            </div>
                        @endif

                        <!-- Joined Date -->
                        <div>
                            <div class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 flex items-center gap-2">
                                <i class="ri-calendar-line text-[#9810FA]"></i>
                                <span>Member Since</span>
                            </div>
                            <p class="text-gray-700 dark:text-gray-300">{{ $user->created_at->format('F Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Looking For Details -->
                @if(!empty($preferences))
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <i class="ri-search-line text-[#9810FA]"></i>
                            <span>Looking For</span>
                        </h2>
                        <div class="flex flex-wrap gap-2">
                            @foreach($preferences as $pref)
                                <span class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-pink-100 to-rose-100 dark:from-pink-900/30 dark:to-rose-900/30 text-pink-700 dark:text-pink-300 rounded-full text-sm font-semibold capitalize border border-pink-200 dark:border-pink-700">
                                    <i class="ri-heart-fill mr-1.5"></i>
                                    {{ str_replace('_', ' ', $pref) }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Detailed Information Section -->
                @if($isCouple)
                    <!-- Couple Mode: Show Combined Her and Him Information Table -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                            <span class="text-pink-500">👩</span>
                            <span class="text-blue-500">👨</span>
                            <span>Couple Information</span>
                        </h2>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b-2 border-gray-300 dark:border-gray-600">
                                        <th class="py-3 px-4 text-left text-sm font-bold text-gray-700 dark:text-gray-300 w-1/3">Field</th>
                                        <th class="py-3 px-4 text-center text-sm font-bold text-pink-600 dark:text-pink-400">👩 Her</th>
                                        <th class="py-3 px-4 text-center text-sm font-bold text-blue-600 dark:text-blue-400">👨 Him</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    <!-- Basic Information -->
                                    <tr>
                                        <td class="py-3 px-4 text-sm font-semibold text-gray-700 dark:text-gray-300">Date of Birth</td>
                                        <td class="py-3 px-4 text-sm text-gray-900 dark:text-white text-center">
                                            @if(!empty($coupleData['date_of_birth_her']))
                                                {{ \Carbon\Carbon::parse($coupleData['date_of_birth_her'])->format('F j, Y') }}
                                            @else
                                                <span class="text-gray-400 dark:text-gray-500">Not provided</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 text-sm text-gray-900 dark:text-white text-center">
                                            @if(!empty($coupleData['date_of_birth_him']))
                                                {{ \Carbon\Carbon::parse($coupleData['date_of_birth_him'])->format('F j, Y') }}
                                            @else
                                                <span class="text-gray-400 dark:text-gray-500">Not provided</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 px-4 text-sm font-semibold text-gray-700 dark:text-gray-300">Sexuality</td>
                                        <td class="py-3 px-4 text-sm text-gray-900 dark:text-white text-center capitalize">
                                            {{ $coupleData['sexuality_her'] ?? 'Not provided' }}
                                        </td>
                                        <td class="py-3 px-4 text-sm text-gray-900 dark:text-white text-center capitalize">
                                            {{ $coupleData['sexuality_him'] ?? 'Not provided' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 px-4 text-sm font-semibold text-gray-700 dark:text-gray-300">Relationship Status</td>
                                        <td class="py-3 px-4 text-sm text-gray-900 dark:text-white text-center capitalize">
                                            {{ str_replace('_', ' ', $coupleData['relationship_status_her'] ?? 'Not provided') }}
                                        </td>
                                        <td class="py-3 px-4 text-sm text-gray-900 dark:text-white text-center capitalize">
                                            {{ str_replace('_', ' ', $coupleData['relationship_status_him'] ?? 'Not provided') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 px-4 text-sm font-semibold text-gray-700 dark:text-gray-300">Smoking</td>
                                        <td class="py-3 px-4 text-sm text-gray-900 dark:text-white text-center capitalize">
                                            {{ $coupleData['smoking_her'] ?? 'Not provided' }}
                                        </td>
                                        <td class="py-3 px-4 text-sm text-gray-900 dark:text-white text-center capitalize">
                                            {{ $coupleData['smoking_him'] ?? 'Not provided' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 px-4 text-sm font-semibold text-gray-700 dark:text-gray-300">Experience</td>
                                        <td class="py-3 px-4 text-sm text-gray-900 dark:text-white text-center capitalize">
                                            {{ $coupleData['experience_her'] ?? 'Not provided' }}
                                        </td>
                                        <td class="py-3 px-4 text-sm text-gray-900 dark:text-white text-center capitalize">
                                            {{ $coupleData['experience_him'] ?? 'Not provided' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 px-4 text-sm font-semibold text-gray-700 dark:text-gray-300">Travel Options</td>
                                        <td class="py-3 px-4 text-sm text-gray-900 dark:text-white text-center capitalize">
                                            {{ str_replace('_', ' ', $coupleData['travel_options_her'] ?? 'Not provided') }}
                                        </td>
                                        <td class="py-3 px-4 text-sm text-gray-900 dark:text-white text-center capitalize">
                                            {{ str_replace('_', ' ', $coupleData['travel_options_him'] ?? 'Not provided') }}
                                        </td>
                                    </tr>
                                    @if(!empty($coupleData['bio_her']) || !empty($coupleData['bio_him']))
                                    <tr>
                                        <td class="py-3 px-4 text-sm font-semibold text-gray-700 dark:text-gray-300 align-top">Bio</td>
                                        <td class="py-3 px-4 text-sm text-gray-900 dark:text-white text-center align-top">
                                            {{ $coupleData['bio_her'] ?? 'Not provided' }}
                                        </td>
                                        <td class="py-3 px-4 text-sm text-gray-900 dark:text-white text-center align-top">
                                            {{ $coupleData['bio_him'] ?? 'Not provided' }}
                                        </td>
                                    </tr>
                                    @endif
                                    <!-- Personal Details Section Header -->
                                    <tr>
                                        <td colspan="3" class="py-4 px-4">
                                            <div class="border-t-2 border-gray-300 dark:border-gray-600 pt-4 mt-4">
                                                <h3 class="text-base font-bold text-gray-900 dark:text-white text-center">Personal Details</h3>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 px-4 text-sm font-semibold text-gray-700 dark:text-gray-300">Weight</td>
                                        <td class="py-3 px-4 text-sm text-gray-900 dark:text-white text-center">
                                            {{ !empty($coupleData['weight_her']) ? $coupleData['weight_her'] . ' kg' : 'Not provided' }}
                                        </td>
                                        <td class="py-3 px-4 text-sm text-gray-900 dark:text-white text-center">
                                            {{ !empty($coupleData['weight_him']) ? $coupleData['weight_him'] . ' kg' : 'Not provided' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 px-4 text-sm font-semibold text-gray-700 dark:text-gray-300">Height</td>
                                        <td class="py-3 px-4 text-sm text-gray-900 dark:text-white text-center">
                                            {{ !empty($coupleData['height_her']) ? $coupleData['height_her'] . ' cm' : 'Not provided' }}
                                        </td>
                                        <td class="py-3 px-4 text-sm text-gray-900 dark:text-white text-center">
                                            {{ !empty($coupleData['height_him']) ? $coupleData['height_him'] . ' cm' : 'Not provided' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 px-4 text-sm font-semibold text-gray-700 dark:text-gray-300">Body Type</td>
                                        <td class="py-3 px-4 text-sm text-gray-900 dark:text-white text-center">
                                            {{ $coupleData['body_type_her'] ?? 'Not provided' }}
                                        </td>
                                        <td class="py-3 px-4 text-sm text-gray-900 dark:text-white text-center">
                                            {{ $coupleData['body_type_him'] ?? 'Not provided' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 px-4 text-sm font-semibold text-gray-700 dark:text-gray-300">Eye Color</td>
                                        <td class="py-3 px-4 text-sm text-gray-900 dark:text-white text-center capitalize">
                                            {{ $coupleData['eye_color_her'] ?? 'Not provided' }}
                                        </td>
                                        <td class="py-3 px-4 text-sm text-gray-900 dark:text-white text-center capitalize">
                                            {{ $coupleData['eye_color_him'] ?? 'Not provided' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 px-4 text-sm font-semibold text-gray-700 dark:text-gray-300">Hair Color</td>
                                        <td class="py-3 px-4 text-sm text-gray-900 dark:text-white text-center capitalize">
                                            {{ $coupleData['hair_color_her'] ?? 'Not provided' }}
                                        </td>
                                        <td class="py-3 px-4 text-sm text-gray-900 dark:text-white text-center capitalize">
                                            {{ $coupleData['hair_color_him'] ?? 'Not provided' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 px-4 text-sm font-semibold text-gray-700 dark:text-gray-300">Tattoos</td>
                                        <td class="py-3 px-4 text-sm text-gray-900 dark:text-white text-center capitalize">
                                            {{ $coupleData['tattoos_her'] ?? 'Not provided' }}
                                        </td>
                                        <td class="py-3 px-4 text-sm text-gray-900 dark:text-white text-center capitalize">
                                            {{ $coupleData['tattoos_him'] ?? 'Not provided' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 px-4 text-sm font-semibold text-gray-700 dark:text-gray-300">Piercings</td>
                                        <td class="py-3 px-4 text-sm text-gray-900 dark:text-white text-center">
                                            {{ $coupleData['piercings_her'] ?? 'Not provided' }}
                                        </td>
                                        <td class="py-3 px-4 text-sm text-gray-900 dark:text-white text-center">
                                            {{ $coupleData['piercings_him'] ?? 'Not provided' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 px-4 text-sm font-semibold text-gray-700 dark:text-gray-300">Boob Size / Dick Size</td>
                                        <td class="py-3 px-4 text-sm text-gray-900 dark:text-white text-center">
                                            {{ $coupleData['boob_size_her'] ?? 'Not provided' }}
                                        </td>
                                        <td class="py-3 px-4 text-sm text-gray-900 dark:text-white text-center">
                                            {{ $coupleData['dick_size_him'] ?? 'Not provided' }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <!-- Single Mode: Show single person information -->
                    @if($profile)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                            <i class="ri-user-line text-[#9810FA]"></i>
                            <span>Personal Information</span>
                        </h2>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @if($profile->date_of_birth)
                                    <tr>
                                        <td class="py-3 px-4 text-sm font-semibold text-gray-700 dark:text-gray-300 w-1/3">Date of Birth</td>
                                        <td class="py-3 px-4 text-sm text-gray-900 dark:text-white">
                                            {{ \Carbon\Carbon::parse($profile->date_of_birth)->format('F j, Y') }}
                                        </td>
                                    </tr>
                                    @endif
                                    @if($profile->sexuality)
                                    <tr>
                                        <td class="py-3 px-4 text-sm font-semibold text-gray-700 dark:text-gray-300">Sexuality</td>
                                        <td class="py-3 px-4 text-sm text-gray-900 dark:text-white capitalize">
                                            {{ $profile->sexuality }}
                                        </td>
                                    </tr>
                                    @endif
                                    @if($profile->relationship_status)
                                    <tr>
                                        <td class="py-3 px-4 text-sm font-semibold text-gray-700 dark:text-gray-300">Relationship Status</td>
                                        <td class="py-3 px-4 text-sm text-gray-900 dark:text-white capitalize">
                                            {{ str_replace('_', ' ', $profile->relationship_status) }}
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
