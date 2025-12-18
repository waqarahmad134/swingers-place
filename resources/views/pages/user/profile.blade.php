@extends('layouts.app')

@section('title', $user->name . ' - Profile - ' . config('app.name'))

@section('full-width')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="mx-auto max-w-4xl">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Home
                </a>
            </div>

            <!-- Profile Header -->
            <div class="rounded-2xl border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-800">
                <div class="bg-gradient-to-r from-primary/10 to-secondary/10 px-8 py-8 rounded-t-2xl">
                    <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
                        <!-- Profile Image -->
                        <div class="flex-shrink-0">
                            @if($user->profile_image)
                                <img src="{{ asset('storage/' . $user->profile_image) }}" alt="{{ $user->name }}" class="h-32 w-32 rounded-full object-cover border-4 border-white shadow-lg">
                            @else
                                <div class="flex h-32 w-32 items-center justify-center rounded-full bg-primary/20 text-primary border-4 border-white shadow-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Name and Company -->
                        <div class="flex-1 text-center md:text-left">
                            <h1 class="text-3xl font-extrabold text-dark dark:text-white mb-2">{{ $user->name }}</h1>
                            @if($user->company)
                                <p class="text-lg text-gray-600 dark:text-gray-400 mb-4">{{ $user->company }}</p>
                            @endif
                            
                            <!-- Contact/Message Button -->
                            <div class="flex flex-wrap gap-3 justify-center md:justify-start">
                                @if($user->email)
                                    <a href="mailto:{{ $user->email }}" class="inline-flex items-center gap-2 rounded-full bg-primary px-6 py-3 text-sm font-semibold text-white transition-colors hover:bg-secondary shadow-md">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        Contact
                                    </a>
                                @endif
                                
                                @if($user->phone)
                                    <a href="tel:{{ $user->phone }}" class="inline-flex items-center gap-2 rounded-full border border-primary bg-white px-6 py-3 text-sm font-semibold text-primary transition-colors hover:bg-primary/10 dark:border-primary dark:bg-gray-800 dark:text-primary dark:hover:bg-primary/20">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                        Call
                                    </a>
                                @endif

                                @auth
                                    @if(auth()->id() !== $user->id)
                                        <a href="{{ route('messages.show', $user->id, false) }}" class="inline-flex items-center gap-2 rounded-full bg-gray-900 px-6 py-3 text-sm font-semibold text-white transition-colors hover:bg-primary dark:bg-gray-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 1.657-4.03 6-9 6s-9-4.343-9-6 4.03-6 9-6 9 4.343 9 6z" />
                                            </svg>
                                            Message
                                        </a>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-full border border-dashed border-gray-400 px-6 py-3 text-sm font-semibold text-gray-600 transition hover:text-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Log in to message
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profile Details -->
                <div class="p-8 space-y-6">
                    <!-- Contact Information -->
                    <div>
                        <h2 class="text-xl font-bold text-dark dark:text-white mb-4 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Contact Information
                        </h2>
                        <div class="grid gap-4 md:grid-cols-2">
                            @if($user->email)
                                <div class="flex items-center gap-3 rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900/50">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-primary/10 text-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Email</p>
                                        <a href="mailto:{{ $user->email }}" class="text-sm font-medium text-gray-900 dark:text-white hover:text-primary truncate block">{{ $user->email }}</a>
                                    </div>
                                </div>
                            @endif

                            @if($user->phone)
                                <div class="flex items-center gap-3 rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900/50">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-primary/10 text-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Phone</p>
                                        <a href="tel:{{ $user->phone }}" class="text-sm font-medium text-gray-900 dark:text-white hover:text-primary">{{ $user->phone }}</a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Business Information -->
                    @if($user->company || $user->website_url || $user->address || $user->business_address)
                        <div>
                            <h2 class="text-xl font-bold text-dark dark:text-white mb-4 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                Business Information
                            </h2>
                            <div class="space-y-4">
                                @if($user->company)
                                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900/50">
                                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1">Company</p>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->company }}</p>
                                    </div>
                                @endif

                                @if($user->website_url)
                                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900/50">
                                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1">Website</p>
                                        <a href="{{ $user->website_url }}" target="_blank" rel="noopener noreferrer" class="text-sm font-medium text-primary hover:underline inline-flex items-center gap-1">
                                            {{ $user->website_url }}
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                        </a>
                                    </div>
                                @endif

                                @if($user->address)
                                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900/50">
                                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1">Address</p>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->address }}</p>
                                    </div>
                                @endif

                                @if($user->business_address)
                                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900/50">
                                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1">Business Address</p>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->business_address }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Personal Information -->
                    @if($user->gender)
                        <div>
                            <h2 class="text-xl font-bold text-dark dark:text-white mb-4 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Personal Information
                            </h2>
                            <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900/50">
                                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1">Gender</p>
                                <p class="text-sm font-medium text-gray-900 dark:text-white capitalize">{{ str_replace('_', ' ', $user->gender) }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Match Profiles Section -->
            @if($matchedProfiles && $matchedProfiles->count() > 0)
                <div class="mt-8">
                    <!-- Section Header -->
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                            <i class="ri-team-line text-[#9810FA]"></i>
                            Match Profiles
                        </h2>
                        <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Members who match this profile</p>
                    </div>

                    <!-- Profile Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                        @foreach($matchedProfiles as $matchedUser)
                            @php
                                $matchedProfile = $matchedUser->profile;
                                
                                // Get category and location
                                $matchedCategory = $matchedProfile ? $matchedProfile->category : null;
                                $matchedLocation = $matchedProfile && $matchedProfile->city 
                                    ? $matchedProfile->city . ($matchedProfile->country ? ', ' . $matchedProfile->country : '')
                                    : 'Location not set';
                                
                                // Calculate age
                                $isMatchedCouple = $matchedCategory === 'couple';
                                $matchedAge = null;
                                $matchedAgeHer = null;
                                $matchedAgeHim = null;
                                
                                if ($isMatchedCouple && $matchedProfile && $matchedProfile->couple_data) {
                                    $matchedCoupleData = is_array($matchedProfile->couple_data) 
                                        ? $matchedProfile->couple_data 
                                        : json_decode($matchedProfile->couple_data, true) ?? [];
                                    
                                    if (!empty($matchedCoupleData['date_of_birth_her'])) {
                                        $matchedAgeHer = \Carbon\Carbon::parse($matchedCoupleData['date_of_birth_her'])->age;
                                    }
                                    if (!empty($matchedCoupleData['date_of_birth_him'])) {
                                        $matchedAgeHim = \Carbon\Carbon::parse($matchedCoupleData['date_of_birth_him'])->age;
                                    }
                                    
                                    if ($matchedAgeHer && $matchedAgeHim) {
                                        $matchedAge = $matchedAgeHer . ' / ' . $matchedAgeHim;
                                    } elseif ($matchedAgeHer) {
                                        $matchedAge = $matchedAgeHer;
                                    } elseif ($matchedAgeHim) {
                                        $matchedAge = $matchedAgeHim;
                                    }
                                } elseif ($matchedProfile && $matchedProfile->date_of_birth) {
                                    $matchedAge = \Carbon\Carbon::parse($matchedProfile->date_of_birth)->age;
                                }
                                
                                // Profile photo
                                $matchedProfilePhoto = 'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><rect fill="#f3f4f6" width="200" height="200"/><path fill="#9ca3af" d="M100 50a30 30 0 1 1 0 60 30 30 0 0 1 0-60zm0 75c25 0 50 12.5 50 25v15H50v-15c0-12.5 25-25 50-25z"/></svg>');
                                if ($matchedProfile && $matchedProfile->profile_photo) {
                                    $matchedProfilePhoto = asset('storage/' . $matchedProfile->profile_photo);
                                } elseif ($matchedUser->profile_image) {
                                    $matchedProfilePhoto = asset('storage/' . $matchedUser->profile_image);
                                }
                                
                                // Check online status
                                $hideMatchedOnlineStatus = $matchedProfile && $matchedProfile->show_online_status === false;
                                $isMatchedOnline = !$hideMatchedOnlineStatus && $matchedUser->isOnline();
                            @endphp
                            
                            <div class="group bg-white dark:bg-gray-800 rounded-2xl overflow-hidden border border-gray-200 dark:border-gray-700 hover:border-purple-500 transition-all hover:shadow-lg hover:shadow-purple-500/20 relative">
                                <!-- Profile Image -->
                                <a href="{{ route('user.profile', $matchedUser->username ?: $matchedUser->id) }}" class="block relative">
                                    <img 
                                        src="{{ $matchedProfilePhoto }}" 
                                        alt="{{ $matchedUser->name }}"
                                        class="w-full h-64 object-cover bg-gray-200 dark:bg-gray-700 cursor-pointer"
                                    />
                                    
                                    <div class="absolute top-2 left-2 flex flex-col items-start gap-2">
                                        <!-- Online Badge -->
                                        @if($isMatchedOnline)
                                            <div class="bg-green-500 text-white text-xs font-semibold px-2 py-1 rounded-xl flex items-center gap-1">
                                                <div class="w-2 h-2 bg-white rounded-full animate-pulse"></div>
                                                <span>Online</span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Distance Badge -->
                                    @if($matchedProfile && $matchedProfile->city)
                                        <div class="absolute bottom-2 right-2 bg-black/60 dark:bg-gray-900/80 text-white text-xs font-semibold px-2 py-1 rounded-xl">
                                            <i class="ri-map-pin-line text-sm mr-1"></i>
                                            <span class="font-light">{{ $matchedProfile->city }}</span>
                                        </div>
                                    @endif
                                    
                                    <!-- Hover Action Buttons Overlay -->
                                    <div class="absolute inset-0 bg-black/70 dark:bg-black/80 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                        <div class="flex flex-col gap-3 px-4 w-full">
                                            <!-- Message Button -->
                                            <a href="{{ route('messages.show', $matchedUser->id) }}" 
                                               class="flex items-center gap-3 bg-purple-600 hover:bg-purple-700 text-white px-4 py-3 rounded-xl transition-colors">
                                                <i class="ri-message-3-line text-xl"></i>
                                                <span class="font-semibold">Messenger</span>
                                            </a>
                                            
                                            <!-- Like Button -->
                                            @php
                                                $isMatchedLikedHover = isset($userLikes[$matchedUser->id]) && $userLikes[$matchedUser->id]->type === 'like';
                                            @endphp
                                            <button type="button"
                                                    onclick="event.stopPropagation(); toggleLike({{ $matchedUser->id }}, this); event.preventDefault();"
                                                    class="flex items-center gap-3 bg-red-500 hover:bg-red-600 text-white px-4 py-3 rounded-xl transition-colors like-hover-btn-{{ $matchedUser->id }}"
                                                    data-user-id="{{ $matchedUser->id }}"
                                                    data-liked="{{ $isMatchedLikedHover ? 'true' : 'false' }}">
                                                <i class="ri-heart-{{ $isMatchedLikedHover ? 'fill' : 'line' }} text-xl"></i>
                                                <span class="font-semibold">{{ $isMatchedLikedHover ? 'Unlike' : 'Like' }}</span>
                                            </button>
                                            
                                            <!-- Friend Request Button -->
                                            <button type="button"
                                                    onclick="event.stopPropagation(); sendFriendRequest({{ $matchedUser->id }}, this); event.preventDefault();"
                                                    class="flex items-center gap-3 bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-xl transition-colors friend-request-btn-{{ $matchedUser->id }}">
                                                <i class="ri-user-add-line text-xl"></i>
                                                <span class="font-semibold friend-request-text-{{ $matchedUser->id }}">Friend request</span>
                                            </button>
                                            
                                            <!-- Remember Button -->
                                            <button type="button"
                                                    onclick="event.stopPropagation(); rememberUser({{ $matchedUser->id }}, this); event.preventDefault();"
                                                    class="flex items-center gap-3 bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-xl transition-colors remember-btn-{{ $matchedUser->id }}">
                                                <i class="ri-bookmark-line text-xl"></i>
                                                <span class="font-semibold remember-text-{{ $matchedUser->id }}">Remember</span>
                                            </button>
                                        </div>
                                    </div>
                                </a>

                                <!-- Profile Info -->
                                <div class="p-4">
                                    <a href="{{ route('user.profile', $matchedUser->username ?: $matchedUser->id) }}" class="block">
                                        <h3 class="text-gray-900 dark:text-white mb-1 font-semibold hover:text-purple-500 transition-colors cursor-pointer">{{ $matchedUser->name }}</h3>
                                    </a>
                                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-2">
                                        @if($matchedAge)
                                            {{ $matchedAge }} •
                                        @endif
                                        @if($matchedCategory === 'couple')
                                            Couple
                                        @elseif($matchedCategory === 'group')
                                            Group
                                        @elseif($matchedCategory === 'single_female')
                                            Single Female
                                        @else
                                            Single {{ ucfirst($matchedUser->gender ?: 'Male') }}
                                        @endif
                                    </p>
                                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-3">
                                        <i class="ri-map-pin-line text-sm mr-0.5"></i>
                                        <span>{{ $matchedLocation }}</span>
                                    </p>

                                    <!-- Engagement Stats -->
                                    @php
                                        $isMatchedLiked = isset($userLikes[$matchedUser->id]) && $userLikes[$matchedUser->id]->type === 'like';
                                        $matchedLikesCount = $matchedUser->likesReceived()->where('type', 'like')->count();
                                    @endphp
                                    
                                    <div class="flex items-center justify-start">
                                        <button 
                                            type="button"
                                            onclick="event.stopPropagation(); toggleLike({{ $matchedUser->id }}, this)"
                                            class="flex items-center gap-1 transition-colors {{ $isMatchedLiked ? 'text-red-500' : 'text-gray-400 hover:text-red-500' }}"
                                            data-user-id="{{ $matchedUser->id }}"
                                            data-liked="{{ $isMatchedLiked ? 'true' : 'false' }}"
                                        >
                                            <i class="ri-heart-{{ $isMatchedLiked ? 'fill' : 'line' }} text-lg"></i>
                                            <span class="text-gray-900 dark:text-white text-sm font-medium likes-count-{{ $matchedUser->id }}">{{ $matchedLikesCount }}</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

@push('scripts')
<script>
// Toggle like functionality
function toggleLike(userId, button) {
    const icon = button.querySelector('i');
    const countSpan = button.querySelector('.likes-count-' + userId);
    const isLiked = button.dataset.liked === 'true';
    
    // Also get hover button if it exists
    const hoverButton = document.querySelector('.like-hover-btn-' + userId);
    const hoverIcon = hoverButton ? hoverButton.querySelector('i') : null;
    const hoverText = hoverButton ? hoverButton.querySelector('span:last-child') : null;
    
    // Optimistic UI update for main button
    if (isLiked) {
        icon.classList.remove('ri-heart-fill');
        icon.classList.add('ri-heart-line');
        button.classList.remove('text-red-500');
        button.classList.add('text-gray-400');
        button.dataset.liked = 'false';
        
        // Update hover button
        if (hoverButton) {
            if (hoverIcon) {
                hoverIcon.classList.remove('ri-heart-fill');
                hoverIcon.classList.add('ri-heart-line');
            }
            if (hoverText) hoverText.textContent = 'Like';
            hoverButton.dataset.liked = 'false';
        }
    } else {
        icon.classList.remove('ri-heart-line');
        icon.classList.add('ri-heart-fill');
        button.classList.remove('text-gray-400');
        button.classList.add('text-red-500');
        button.dataset.liked = 'true';
        
        // Update hover button
        if (hoverButton) {
            if (hoverIcon) {
                hoverIcon.classList.remove('ri-heart-line');
                hoverIcon.classList.add('ri-heart-fill');
            }
            if (hoverText) hoverText.textContent = 'Unlike';
            hoverButton.dataset.liked = 'true';
        }
    }
    
    // Make API call
    fetch(`/users/${userId}/like`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify({})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update count
            if (countSpan) {
                countSpan.textContent = data.likes_count;
            }
            // Update button state based on response
            if (data.is_liked) {
                icon.classList.remove('ri-heart-line');
                icon.classList.add('ri-heart-fill');
                button.classList.remove('text-gray-400');
                button.classList.add('text-red-500');
                button.dataset.liked = 'true';
                
                // Update hover button
                if (hoverButton) {
                    if (hoverIcon) {
                        hoverIcon.classList.remove('ri-heart-line');
                        hoverIcon.classList.add('ri-heart-fill');
                    }
                    if (hoverText) hoverText.textContent = 'Unlike';
                    hoverButton.dataset.liked = 'true';
                }
            } else {
                icon.classList.remove('ri-heart-fill');
                icon.classList.add('ri-heart-line');
                button.classList.remove('text-red-500');
                button.classList.add('text-gray-400');
                button.dataset.liked = 'false';
                
                // Update hover button
                if (hoverButton) {
                    if (hoverIcon) {
                        hoverIcon.classList.remove('ri-heart-fill');
                        hoverIcon.classList.add('ri-heart-line');
                    }
                    if (hoverText) hoverText.textContent = 'Like';
                    hoverButton.dataset.liked = 'false';
                }
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Revert optimistic update on error
        if (isLiked) {
            icon.classList.remove('ri-heart-line');
            icon.classList.add('ri-heart-fill');
            button.classList.remove('text-gray-400');
            button.classList.add('text-red-500');
            button.dataset.liked = 'true';
            
            if (hoverButton) {
                if (hoverIcon) {
                    hoverIcon.classList.remove('ri-heart-line');
                    hoverIcon.classList.add('ri-heart-fill');
                }
                if (hoverText) hoverText.textContent = 'Unlike';
                hoverButton.dataset.liked = 'true';
            }
        } else {
            icon.classList.remove('ri-heart-fill');
            icon.classList.add('ri-heart-line');
            button.classList.remove('text-red-500');
            button.classList.add('text-gray-400');
            button.dataset.liked = 'false';
            
            if (hoverButton) {
                if (hoverIcon) {
                    hoverIcon.classList.remove('ri-heart-fill');
                    hoverIcon.classList.add('ri-heart-line');
                }
                if (hoverText) hoverText.textContent = 'Like';
                hoverButton.dataset.liked = 'false';
            }
        }
    });
}

// Send Friend Request
function sendFriendRequest(userId, button) {
    const textSpan = button.querySelector('.friend-request-text-' + userId);
    const originalText = textSpan ? textSpan.textContent : 'Friend request';
    
    // Update button to show success state
    if (textSpan) {
        textSpan.textContent = 'Friend request sent';
    }
    button.classList.remove('bg-blue-600', 'hover:bg-blue-700');
    button.classList.add('bg-blue-500', 'cursor-not-allowed');
    button.disabled = true;
    
    // Show success notification
    showNotification('Friend request sent successfully!', 'success');
    
    // Revert after 3 seconds
    setTimeout(() => {
        if (textSpan) {
            textSpan.textContent = originalText;
        }
        button.classList.remove('bg-blue-500', 'cursor-not-allowed');
        button.classList.add('bg-blue-600', 'hover:bg-blue-700');
        button.disabled = false;
    }, 3000);
}

// Remember User
function rememberUser(userId, button) {
    const textSpan = button.querySelector('.remember-text-' + userId);
    const originalText = textSpan ? textSpan.textContent : 'Remember';
    
    // Update button to show success state
    if (textSpan) {
        textSpan.textContent = 'Remembered successfully';
    }
    button.classList.remove('bg-green-600', 'hover:bg-green-700');
    button.classList.add('bg-green-500', 'cursor-not-allowed');
    button.disabled = true;
    
    // Show success notification
    showNotification('User remembered successfully!', 'success');
    
    // Revert after 3 seconds
    setTimeout(() => {
        if (textSpan) {
            textSpan.textContent = originalText;
        }
        button.classList.remove('bg-green-500', 'cursor-not-allowed');
        button.classList.add('bg-green-600', 'hover:bg-green-700');
        button.disabled = false;
    }, 3000);
}

// Show notification (simple toast-like notification)
function showNotification(message, type = 'success') {
    // Remove existing notification if any
    const existingNotification = document.getElementById('action-notification');
    if (existingNotification) {
        existingNotification.remove();
    }
    
    // Create notification element
    const notification = document.createElement('div');
    notification.id = 'action-notification';
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 transition-all duration-300 transform translate-x-full ${
        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    }`;
    notification.textContent = message;
    
    // Add to body
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 10);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Toggle dislike functionality
function toggleDislike(userId, button) {
    const icon = button.querySelector('i');
    const isDisliked = button.dataset.disliked === 'true';
    
    // Optimistic UI update
    if (isDisliked) {
        icon.classList.remove('ri-close-circle-fill');
        icon.classList.add('ri-close-circle-line');
        button.classList.remove('text-gray-600', 'dark:text-gray-400');
        button.classList.add('text-gray-400');
        button.dataset.disliked = 'false';
    } else {
        icon.classList.remove('ri-close-circle-line');
        icon.classList.add('ri-close-circle-fill');
        button.classList.remove('text-gray-400');
        button.classList.add('text-gray-600', 'dark:text-gray-400');
        button.dataset.disliked = 'true';
    }
    
    // Make API call
    fetch(`/users/${userId}/dislike`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify({})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update button state based on response
            if (data.is_disliked) {
                icon.classList.remove('ri-close-circle-line');
                icon.classList.add('ri-close-circle-fill');
                button.classList.remove('text-gray-400');
                button.classList.add('text-gray-600', 'dark:text-gray-400');
                button.dataset.disliked = 'true';
            } else {
                icon.classList.remove('ri-close-circle-fill');
                icon.classList.add('ri-close-circle-line');
                button.classList.remove('text-gray-600', 'dark:text-gray-400');
                button.classList.add('text-gray-400');
                button.dataset.disliked = 'false';
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Revert optimistic update on error
        if (isDisliked) {
            icon.classList.remove('ri-close-circle-line');
            icon.classList.add('ri-close-circle-fill');
            button.classList.remove('text-gray-400');
            button.classList.add('text-gray-600', 'dark:text-gray-400');
            button.dataset.disliked = 'true';
        } else {
            icon.classList.remove('ri-close-circle-fill');
            icon.classList.add('ri-close-circle-line');
            button.classList.remove('text-gray-600', 'dark:text-gray-400');
            button.classList.add('text-gray-400');
            button.dataset.disliked = 'false';
        }
    });
}
</script>
@endpush
@endsection

