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
                    <div class="rounded-2xl border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-800">
                        <div class="bg-gradient-to-r from-primary/10 to-secondary/10 px-8 py-6 rounded-t-2xl">
                            <h2 class="text-2xl font-extrabold text-dark dark:text-white flex items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Match Profiles
                            </h2>
                        </div>
                        <div class="p-8">
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                                @foreach($matchedProfiles as $matchedUser)
                                    <div class="group relative overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm transition-all duration-300 hover:shadow-xl hover:border-primary dark:border-gray-700 dark:bg-gray-800">
                                        <!-- Profile Image -->
                                        <a href="{{ route('user.profile', $matchedUser->username ?? $matchedUser->id) }}" class="block">
                                            <div class="relative aspect-square overflow-hidden bg-gray-100 dark:bg-gray-900">
                                                @if($matchedUser->profile_image)
                                                    <img src="{{ asset('storage/' . $matchedUser->profile_image) }}" 
                                                         alt="{{ $matchedUser->name }}" 
                                                         class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-110">
                                                @else
                                                    <div class="flex h-full w-full items-center justify-center bg-primary/10 text-primary">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                        </svg>
                                                    </div>
                                                @endif
                                                
                                                <!-- Category Badge -->
                                                @if($matchedUser->profile && $matchedUser->profile->category)
                                                    <div class="absolute top-2 left-2">
                                                        <span class="inline-flex items-center rounded-full bg-primary/90 px-3 py-1 text-xs font-semibold text-white shadow-md backdrop-blur-sm">
                                                            {{ ucwords(str_replace('_', ' ', $matchedUser->profile->category)) }}
                                                        </span>
                                                    </div>
                                                @endif
                                                
                                                <!-- Location Badge -->
                                                @if($matchedUser->profile && $matchedUser->profile->city)
                                                    <div class="absolute bottom-2 right-2">
                                                        <span class="inline-flex items-center gap-1 rounded-full bg-gray-900/70 px-2 py-1 text-xs font-medium text-white backdrop-blur-sm">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            </svg>
                                                            {{ $matchedUser->profile->city }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        </a>
                                        
                                        <!-- Profile Info -->
                                        <div class="p-4">
                                            <a href="{{ route('user.profile', $matchedUser->username ?? $matchedUser->id) }}" class="block">
                                                <h3 class="text-lg font-bold text-gray-900 dark:text-white truncate group-hover:text-primary transition-colors">
                                                    {{ $matchedUser->name }}
                                                </h3>
                                            </a>
                                            
                                            @if($matchedUser->profile)
                                                <!-- Age Display -->
                                                @php
                                                    $isMatchedCouple = $matchedUser->profile->category === 'couple';
                                                    $matchedAge = null;
                                                    $matchedAgeHer = null;
                                                    $matchedAgeHim = null;
                                                    
                                                    if ($isMatchedCouple && $matchedUser->profile->couple_data) {
                                                        $matchedCoupleData = is_array($matchedUser->profile->couple_data) 
                                                            ? $matchedUser->profile->couple_data 
                                                            : json_decode($matchedUser->profile->couple_data, true) ?? [];
                                                        
                                                        if (!empty($matchedCoupleData['date_of_birth_her'])) {
                                                            $matchedAgeHer = \Carbon\Carbon::parse($matchedCoupleData['date_of_birth_her'])->age;
                                                        }
                                                        if (!empty($matchedCoupleData['date_of_birth_him'])) {
                                                            $matchedAgeHim = \Carbon\Carbon::parse($matchedCoupleData['date_of_birth_him'])->age;
                                                        }
                                                    } elseif ($matchedUser->profile->date_of_birth) {
                                                        $matchedAge = \Carbon\Carbon::parse($matchedUser->profile->date_of_birth)->age;
                                                    }
                                                @endphp
                                                
                                                @if($matchedAge || $matchedAgeHer || $matchedAgeHim)
                                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                                        @if($isMatchedCouple)
                                                            @if($matchedAgeHer && $matchedAgeHim)
                                                                {{ $matchedAgeHer }} / {{ $matchedAgeHim }} years old
                                                            @elseif($matchedAgeHer)
                                                                {{ $matchedAgeHer }} years old
                                                            @elseif($matchedAgeHim)
                                                                {{ $matchedAgeHim }} years old
                                                            @endif
                                                        @else
                                                            {{ $matchedAge }} years old
                                                        @endif
                                                    </p>
                                                @endif
                                                
                                                <!-- Bio Preview -->
                                                @if($matchedUser->profile->bio)
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 line-clamp-2">
                                                        {{ Str::limit($matchedUser->profile->bio, 80) }}
                                                    </p>
                                                @endif
                                            @endif
                                            
                                            <!-- View Profile Button -->
                                            <div class="mt-4">
                                                <a href="{{ route('user.profile', $matchedUser->username ?? $matchedUser->id) }}" 
                                                   class="block w-full rounded-lg bg-primary px-4 py-2 text-center text-sm font-semibold text-white transition-colors hover:bg-secondary">
                                                    View Profile
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

