@extends('layouts.app')

@section('title', $user->name . ' - Profile - ' . config('app.name'))

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Profile Banner Area -->
    <div class="relative">
        <!-- Cover Photo -->
        <div class="h-64 md:h-80 w-full bg-gradient-to-r from-purple-500 via-pink-500 to-orange-400 relative overflow-hidden">
            @if($profile && $profile->cover_photo)
                <img src="{{ asset('storage/' . $profile->cover_photo) }}" alt="Cover Photo" class="w-full h-full object-cover">
            @else
                <!-- Default gradient pattern -->
                <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.4&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
            @endif
            
            <!-- Edit Profile Button -->
            <div class="absolute top-4 right-4">
                <a href="{{ route('account.profile.edit') }}" class="inline-flex items-center gap-2 bg-white/90 hover:bg-white px-4 py-2 rounded-lg text-sm font-semibold text-gray-800 shadow-lg transition-all">
                    <i class="ri-pencil-line"></i>
                    Edit Profile
                </a>
            </div>
        </div>

        <!-- Profile Picture & Info -->
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 -mt-20 relative z-10">
            <div class="flex flex-col md:flex-row items-start md:items-end gap-6 pb-6">
                <!-- Profile Picture -->
                <div class="relative">
                    @if($profile && $profile->profile_photo)
                        <img src="{{ asset('storage/' . $profile->profile_photo) }}" alt="{{ $user->name }}" class="w-32 h-32 md:w-40 md:h-40 rounded-full border-4 border-white shadow-xl object-cover">
                    @elseif($user->profile_image)
                        <img src="{{ asset('storage/' . $user->profile_image) }}" alt="{{ $user->name }}" class="w-32 h-32 md:w-40 md:h-40 rounded-full border-4 border-white shadow-xl object-cover">
                    @else
                        <div class="w-32 h-32 md:w-40 md:h-40 rounded-full border-4 border-white shadow-xl bg-gradient-to-br from-purple-400 to-pink-400 flex items-center justify-center text-white text-4xl font-bold">
                            {{ strtoupper(substr($user->first_name ?? $user->name, 0, 1)) }}{{ strtoupper(substr($user->last_name ?? '', 0, 1)) }}
                        </div>
                    @endif
                    <!-- Online Status Indicator -->
                    <div class="absolute bottom-2 right-2 w-5 h-5 bg-green-500 border-4 border-white rounded-full"></div>
                </div>

                <!-- Profile Name & Info -->
                <div class="flex-1 pb-4">
                    <div class="flex flex-wrap items-center gap-3 mb-3">
                        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white">
                            {{ $user->first_name && $user->last_name ? $user->first_name . ' & ' . $user->last_name : $user->name }}
                        </h1>
                        <!-- Verified Badge -->
                        <span class="inline-flex items-center gap-1 bg-blue-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                            <i class="ri-checkbox-circle-fill"></i>
                            Verified
                        </span>
                    </div>
                    
                    <!-- Key Information -->
                    <div class="flex flex-wrap items-center gap-4 text-gray-600 dark:text-gray-300 text-sm md:text-base">
                        @if($profile && $profile->category)
                            <span class="font-semibold capitalize">{{ str_replace('_', ' ', $profile->category) }}</span>
                        @endif
                        @if($age)
                            <span>{{ $age }} years old</span>
                        @endif
                        @if($profile && $profile->sexuality)
                            <span class="capitalize">{{ $profile->sexuality }}</span>
                        @endif
                        @if($profile && $profile->home_location)
                            <span>{{ $profile->home_location }}</span>
                        @endif
                        <span>Joined {{ $joinDate }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Statistics -->
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 mb-6">
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <!-- Likes -->
            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm border border-gray-200 dark:border-gray-700 text-center">
                <i class="ri-heart-fill text-2xl text-red-500 mb-2"></i>
                <div class="text-2xl font-bold text-gray-900 dark:text-white">156</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Likes</div>
            </div>
            
            <!-- Photos -->
            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm border border-gray-200 dark:border-gray-700 text-center">
                <i class="ri-image-fill text-2xl text-blue-500 mb-2"></i>
                @php
                    $photoCount = 0;
                    if ($profile && $profile->album_photos) {
                        $photos = is_array($profile->album_photos) ? $profile->album_photos : json_decode($profile->album_photos, true) ?? [];
                        $photoCount = is_array($photos) ? count($photos) : 0;
                    }
                @endphp
                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $photoCount }}</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Photos</div>
            </div>
            
            <!-- Videos -->
            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm border border-gray-200 dark:border-gray-700 text-center">
                <i class="ri-video-fill text-2xl text-purple-500 mb-2"></i>
                <div class="text-2xl font-bold text-gray-900 dark:text-white">8</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Videos</div>
            </div>
            
            <!-- Friends -->
            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm border border-gray-200 dark:border-gray-700 text-center">
                <i class="ri-group-fill text-2xl text-green-500 mb-2"></i>
                <div class="text-2xl font-bold text-gray-900 dark:text-white">89</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Friends</div>
            </div>
            
            <!-- Validations -->
            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm border border-gray-200 dark:border-gray-700 text-center">
                <i class="ri-award-fill text-2xl text-yellow-500 mb-2"></i>
                <div class="text-2xl font-bold text-gray-900 dark:text-white">12</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Validations</div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 pb-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- About Section -->
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">About</h2>
                    
                    <!-- Languages -->
                    @php
                        $languages = $profile && $profile->languages 
                            ? (is_array($profile->languages) ? $profile->languages : json_decode($profile->languages, true) ?? [])
                            : [];
                    @endphp
                    @if(!empty($languages))
                        <div class="mb-4">
                            <div class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Languages</div>
                            <div class="flex flex-wrap gap-2">
                                @foreach($languages as $language)
                                    <span class="bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 px-3 py-1 rounded-full text-sm font-medium capitalize">
                                        {{ $language }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    <!-- Looking For -->
                    @php
                        $preferences = $profile && $profile->preferences 
                            ? (is_array($profile->preferences) ? $profile->preferences : json_decode($profile->preferences, true) ?? [])
                            : [];
                    @endphp
                    @if(!empty($preferences))
                        <div class="mb-4">
                            <div class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Looking For</div>
                            <div class="flex flex-wrap gap-2">
                                @foreach($preferences as $pref)
                                    <span class="bg-pink-100 dark:bg-pink-900/30 text-pink-700 dark:text-pink-300 px-3 py-1 rounded-full text-sm font-medium capitalize">
                                        {{ str_replace('_', ' ', $pref) }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Description -->
                @if($profile && $profile->bio)
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-3">Description</h2>
                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $profile->bio }}</p>
                    </div>
                @endif

                <!-- Photos Section -->
                <div class="hidden bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Photos</h2>
                        <a href="#" class="text-sm text-purple-600 dark:text-purple-400 hover:underline font-medium">View All</a>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        @php
                            $albumPhotos = $profile && $profile->album_photos 
                                ? (is_array($profile->album_photos) ? $profile->album_photos : json_decode($profile->album_photos, true) ?? [])
                                : [];
                        @endphp
                        @if(!empty($albumPhotos))
                            @foreach(array_slice($albumPhotos, 0, 6) as $photo)
                                <img src="{{ asset('storage/' . $photo) }}" alt="Photo" class="w-full h-24 object-cover rounded-lg">
                            @endforeach
                        @else
                            @for($i = 0; $i < 6; $i++)
                                <div class="w-full h-24 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                    <i class="ri-image-line text-2xl text-gray-400"></i>
                                </div>
                            @endfor
                        @endif
                    </div>
                </div>
            </div>

            <!-- Main Content (Wall) -->
            <div class="lg:col-span-2">
                <!-- Navigation Tabs -->
                <div class="bg-white dark:bg-gray-800 rounded-t-lg border border-gray-200 dark:border-gray-700 border-b-0">
                    <div class="flex space-x-1 p-2">
                        <button class="px-4 py-2 text-sm font-semibold text-purple-600 dark:text-purple-400 border-b-2 border-purple-600 dark:border-purple-400">
                            Wall
                        </button>
                        <button class="px-4 py-2 text-sm font-semibold text-gray-600 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400">
                            Information
                        </button>
                        <button class="px-4 py-2 text-sm font-semibold text-gray-600 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400">
                            Photos
                        </button>
                        <button class="px-4 py-2 text-sm font-semibold text-gray-600 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400">
                            Events
                        </button>
                        <button class="px-4 py-2 text-sm font-semibold text-gray-600 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400">
                            Friends
                        </button>
                    </div>
                </div>

                <!-- Wall Posts -->
                <div class="bg-white dark:bg-gray-800 rounded-b-lg border border-gray-200 dark:border-gray-700 p-6 space-y-6">
                    <!-- Post 1 -->
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-6 last:border-0 last:pb-0">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-400 to-pink-400 flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr($user->first_name ?? $user->name, 0, 1)) }}
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="font-semibold text-gray-900 dark:text-white">{{ $user->first_name && $user->last_name ? $user->first_name . ' & ' . $user->last_name : $user->name }}</span>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">2 hours ago</span>
                                </div>
                                <p class="text-gray-700 dark:text-gray-300 mb-3">Had an amazing time at the beach party last night! Met so many wonderful people ✨🥂</p>
                                <div class="flex items-center gap-4 text-gray-600 dark:text-gray-400">
                                    <button class="flex items-center gap-2 hover:text-red-500 transition-colors">
                                        <i class="ri-heart-line"></i>
                                        <span>89</span>
                                    </button>
                                    <button class="flex items-center gap-2 hover:text-blue-500 transition-colors">
                                        <i class="ri-chat-3-line"></i>
                                        <span>23</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Post 2 -->
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-6 last:border-0 last:pb-0">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-400 to-pink-400 flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr($user->first_name ?? $user->name, 0, 1)) }}
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="font-semibold text-gray-900 dark:text-white">{{ $user->first_name && $user->last_name ? $user->first_name . ' & ' . $user->last_name : $user->name }}</span>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">1 day ago</span>
                                </div>
                                <p class="text-gray-700 dark:text-gray-300 mb-3">Looking forward to our trip to Miami next month. Anyone else going to be there?</p>
                                <div class="mb-3 rounded-lg overflow-hidden">
                                    <img src="https://images.unsplash.com/photo-1492684223066-81342ee5ff30?w=800&h=400&fit=crop" alt="Concert" class="w-full h-64 object-cover">
                                </div>
                                <div class="flex items-center gap-4 text-gray-600 dark:text-gray-400">
                                    <button class="flex items-center gap-2 hover:text-red-500 transition-colors">
                                        <i class="ri-heart-line"></i>
                                        <span>156</span>
                                    </button>
                                    <button class="flex items-center gap-2 hover:text-blue-500 transition-colors">
                                        <i class="ri-chat-3-line"></i>
                                        <span>45</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Post 3 -->
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-6 last:border-0 last:pb-0">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-400 to-pink-400 flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr($user->first_name ?? $user->name, 0, 1)) }}
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="font-semibold text-gray-900 dark:text-white">{{ $user->first_name && $user->last_name ? $user->first_name . ' & ' . $user->last_name : $user->name }}</span>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">3 days ago</span>
                                </div>
                                <p class="text-gray-700 dark:text-gray-300 mb-3">Thank you everyone for the warm welcome to the community! We're excited to connect with you all 💕</p>
                                <div class="flex items-center gap-4 text-gray-600 dark:text-gray-400">
                                    <button class="flex items-center gap-2 hover:text-red-500 transition-colors">
                                        <i class="ri-heart-line"></i>
                                        <span>234</span>
                                    </button>
                                    <button class="flex items-center gap-2 hover:text-blue-500 transition-colors">
                                        <i class="ri-chat-3-line"></i>
                                        <span>67</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
