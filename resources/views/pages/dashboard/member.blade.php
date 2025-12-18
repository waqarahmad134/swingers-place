@extends('layouts.dashboard')

@section('title', 'Members - ' . config('app.name'))

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Map and Filter Buttons -->
    <div class="mb-4 flex gap-3 relative">
        <button 
            id="filterBtn"
            class="px-6 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition-colors flex items-center gap-2 relative z-10"
            onclick="toggleFilterSidebar()"
        >
            <i class="ri-filter-line"></i>
            Filter
        </button>
        
        <!-- Filter Dropdown (Absolute positioned, no space consumption) -->
        <aside 
            id="filterSidebar"
            class="hidden absolute top-full left-0 mt-2 w-80 z-50 bg-gray-50 dark:bg-gray-900 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden"
        >
            <div class="p-4 max-h-[80vh] overflow-y-auto">
                @include('components.member-filter-sidebar')
            </div>
        </aside>
    </div>

    <!-- Main Content Area -->
    <div class="w-full" id="mainContent">
            <!-- Search Bar (Simple) -->
            <form method="GET" action="{{ route('dashboard.members') }}" id="searchForm" class="mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="flex gap-3">
                        <input 
                            type="text" 
                            name="search"
                            id="search"
                            value="{{ request('search') }}"
                            placeholder="Search members..." 
                            class="flex-1 text-sm bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl px-4 py-2 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                        />
                        <button 
                            type="submit" 
                            class="bg-[#9810FA] hover:bg-purple-700 text-white text-sm font-semibold px-6 py-2 rounded-xl transition-colors flex items-center gap-2"
                        >
                            <i class="ri-search-line"></i>
                            Search
                        </button>
                    </div>
                </div>
                
                <!-- Hidden inputs to preserve filter state -->
                <input type="hidden" name="filter_couples" value="{{ request('filter_couples') }}">
                <input type="hidden" name="filter_female" value="{{ request('filter_female') }}">
                <input type="hidden" name="filter_male" value="{{ request('filter_male') }}">
                <input type="hidden" name="filter_business" value="{{ request('filter_business') }}">
                <input type="hidden" name="filter_transgender" value="{{ request('filter_transgender') }}">
                <input type="hidden" name="filter_looking_for_me" value="{{ request('filter_looking_for_me') }}">
                <input type="hidden" name="with_photos_only" value="{{ request('with_photos_only') }}">
                <input type="hidden" name="with_videos_only" value="{{ request('with_videos_only') }}">
                <input type="hidden" name="filter_location" value="{{ request('filter_location') }}">
                <input type="hidden" name="sort_by" value="{{ request('sort_by', 'Random') }}">
                <input type="hidden" name="distance" value="{{ request('distance', 'Any Distance') }}">
                <input type="hidden" name="age_range" value="{{ request('age_range', 'Any Age') }}">
                <input type="hidden" name="online_only" value="{{ request('online_only') }}">
            </form>

            <!-- Profile Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @forelse($members as $member)
                    @php
                        $profile = $member->profile;
                        $age = null;
                        if ($profile && $profile->date_of_birth) {
                            $age = \Carbon\Carbon::parse($profile->date_of_birth)->age;
                        }
                        $location = $profile && $profile->home_location ? $profile->home_location : 'Location not set';
                        
                        // Get display name and initials first
                        $category = $profile && $profile->category ? $profile->category : 'single_male';
                        $displayName = $member->name ?: ($member->first_name . ' ' . $member->last_name) ?: 'User #' . $member->id;
                        
                        // Get initials for placeholder
                        $initials = strtoupper(substr($displayName, 0, 1));
                        if ($member->first_name && $member->last_name) {
                            $initials = strtoupper(substr($member->first_name, 0, 1) . substr($member->last_name, 0, 1));
                        } elseif ($member->first_name) {
                            $initials = strtoupper(substr($member->first_name, 0, 1));
                        }
                        
                        // Create SVG data URI for placeholder (properly encoded)
                        $svgContent = '<svg xmlns="http://www.w3.org/2000/svg" width="400" height="400"><rect fill="#6366f1" width="400" height="400"/><text fill="#ffffff" font-family="Arial, sans-serif" font-size="120" font-weight="bold" x="50%" y="50%" text-anchor="middle" dominant-baseline="middle">' . htmlspecialchars($initials, ENT_XML1, 'UTF-8') . '</text></svg>';
                        $placeholderSvg = 'data:image/svg+xml;charset=utf-8,' . rawurlencode($svgContent);
                        
                        // Get profile photo with proper fallback to SVG placeholder
                        $profilePhoto = $placeholderSvg; // Default to SVG placeholder
                        if ($profile && $profile->profile_photo && file_exists(public_path('storage/' . $profile->profile_photo))) {
                            $profilePhoto = asset('storage/' . $profile->profile_photo);
                        } elseif ($member->profile_image && file_exists(public_path('storage/' . $member->profile_image))) {
                            $profilePhoto = asset('storage/' . $member->profile_image);
                        }
                        
                        // Check if user has photos
                        $hasPhotos = false;
                        if ($profile && $profile->album_photos) {
                            $albumPhotos = is_string($profile->album_photos) ? json_decode($profile->album_photos, true) : $profile->album_photos;
                            if (is_array($albumPhotos)) {
                                $totalPhotos = 0;
                                if (isset($albumPhotos['non_adult']) && is_array($albumPhotos['non_adult'])) {
                                    $totalPhotos += count($albumPhotos['non_adult']);
                                }
                                if (isset($albumPhotos['adult']) && is_array($albumPhotos['adult'])) {
                                    $totalPhotos += count($albumPhotos['adult']);
                                }
                                if (isset($albumPhotos['album']) && is_array($albumPhotos['album'])) {
                                    $totalPhotos += count($albumPhotos['album']);
                                }
                                $hasPhotos = $totalPhotos > 0 || $profile->profile_photo || $member->profile_image;
                            }
                        } else {
                            $hasPhotos = $profile && ($profile->profile_photo || $member->profile_image);
                        }
                        
                        // Check online status - respect privacy setting
                        $hideOnlineStatus = $profile && $profile->show_online_status === false;
                        $isOnline = !$hideOnlineStatus && $member->isOnline();
                    @endphp
                    <div class="bg-white dark:bg-gray-800 rounded-2xl overflow-hidden border border-gray-200 dark:border-gray-700 hover:border-purple-500 transition-all hover:shadow-lg hover:shadow-purple-500/20">
                        <!-- Profile Image -->
                        <a href="{{ route('user.profile', $member->username ?: $member->id) }}" class="block relative">
                            <img 
                                src="{{ $profilePhoto }}" 
                                alt="{{ $displayName }}"
                                class="w-full h-64 object-cover bg-gray-200 dark:bg-gray-700 cursor-pointer"
                                onerror="this.onerror=null; this.src='{{ $placeholderSvg }}';"
                            />
                            
                            <div class="absolute top-2 left-2 flex flex-col items-start gap-2">
                                <!-- Online Badge -->
                                @if($isOnline)
                                    <div class="bg-green-500 text-white text-xs font-semibold px-2 py-1 rounded-xl flex items-center gap-1">
                                        <div class="w-2 h-2 bg-white rounded-full animate-pulse"></div>
                                        <span>Online</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Distance Badge -->
                            <div class="absolute bottom-2 right-2 bg-black/60 dark:bg-gray-900/80 text-white text-xs font-semibold px-2 py-1 rounded-xl">
                                <i class="ri-map-pin-line text-sm mr-1"></i>
                                <span class="font-light">{{ rand(5, 25) }} km</span>  
                            </div>
                        </a>

                        <!-- Profile Info -->
                        <div class="p-4">
                            <a href="{{ route('user.profile', $member->username ?: $member->id) }}" class="block">
                                <h3 class="text-gray-900 dark:text-white mb-1 hover:text-purple-500 transition-colors cursor-pointer">{{ $displayName }}</h3>
                            </a>
                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-2">
                                @if($age)
                                    {{ $age }} •
                                @endif
                                @if($category === 'couple')
                                    Couple
                                @elseif($category === 'group')
                                    Group
                                @elseif($category === 'single_female')
                                    Single Female
                                @else
                                    Single {{ ucfirst($member->gender ?: 'Male') }}
                                @endif
                            </p>
                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-5">
                                <i class="ri-map-pin-line text-sm mr-0.5"></i>
                                <span>{{ $location }}</span>
                            </p>

                            <!-- Engagement Stats -->
                            <div class="flex items-center justify-start">
                                @php
                                    $isLiked = isset($userLikes[$member->id]) && $userLikes[$member->id]->type === 'like';
                                    $likesCount = $member->likesReceived()->where('type', 'like')->count();
                                @endphp
                                
                                <button 
                                    type="button"
                                    onclick="toggleLike({{ $member->id }}, this)"
                                    class="flex items-center gap-1 transition-colors {{ $isLiked ? 'text-red-500' : 'text-gray-400 hover:text-red-500' }}"
                                    data-user-id="{{ $member->id }}"
                                    data-liked="{{ $isLiked ? 'true' : 'false' }}"
                                >
                                    <i class="ri-heart-{{ $isLiked ? 'fill' : 'line' }} text-lg"></i>
                                    <span class="text-gray-900 dark:text-white text-sm font-medium likes-count-{{ $member->id }}">{{ $likesCount }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-600 dark:text-gray-400 text-lg">No members found matching your criteria.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-6 flex justify-center items-center gap-2">
                <!-- Previous Button -->
                @if($members->onFirstPage())
                    <button disabled class="px-3 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-400 dark:text-gray-600 cursor-not-allowed">
                        <i class="ri-arrow-left-s-line text-xl"></i>
                    </button>
                @else
                    <a href="{{ $members->previousPageUrl() }}" class="px-3 py-2 rounded-lg bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <i class="ri-arrow-left-s-line text-xl"></i>
                    </a>
                @endif

                <!-- Next Button -->
                @if($members->hasMorePages())
                    <a href="{{ $members->nextPageUrl() }}" class="px-3 py-2 rounded-lg bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <i class="ri-arrow-right-s-line text-xl"></i>
                    </a>
                @else
                    @php
                        $queryParams = request()->except(['page', 'random']);
                        $queryParams['random'] = '1';
                    @endphp
                    <a href="{{ route('dashboard.members', $queryParams) }}" class="px-3 py-2 rounded-lg bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <i class="ri-arrow-right-s-line text-xl"></i>
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Mobile Overlay (shown when filter is open on mobile) -->
    <div 
        id="mobileFilterOverlay"
        class="fixed inset-0 bg-black/50 z-40 hidden transition-opacity duration-300"
        onclick="toggleFilterSidebar()"
    ></div>
</div>

@push('scripts')
<script>
// Toggle like functionality
function toggleLike(userId, button) {
    const icon = button.querySelector('i');
    const countSpan = button.querySelector('.likes-count-' + userId);
    const isLiked = button.dataset.liked === 'true';
    
    // Optimistic UI update
    if (isLiked) {
        icon.classList.remove('ri-heart-fill');
        icon.classList.add('ri-heart-line');
        button.classList.remove('text-red-500');
        button.classList.add('text-gray-400');
        button.dataset.liked = 'false';
    } else {
        icon.classList.remove('ri-heart-line');
        icon.classList.add('ri-heart-fill');
        button.classList.remove('text-gray-400');
        button.classList.add('text-red-500');
        button.dataset.liked = 'true';
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
            } else {
                icon.classList.remove('ri-heart-fill');
                icon.classList.add('ri-heart-line');
                button.classList.remove('text-red-500');
                button.classList.add('text-gray-400');
                button.dataset.liked = 'false';
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
        } else {
            icon.classList.remove('ri-heart-fill');
            icon.classList.add('ri-heart-line');
            button.classList.remove('text-red-500');
            button.classList.add('text-gray-400');
            button.dataset.liked = 'false';
        }
    });
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

function toggleFilterSidebar() {
    const sidebar = document.getElementById('filterSidebar');
    const overlay = document.getElementById('mobileFilterOverlay');
    
    // Toggle sidebar visibility
    const isHidden = sidebar.classList.contains('hidden');
    
    if (window.innerWidth < 1024) {
        // Mobile: show overlay and position sidebar
        if (isHidden) {
            sidebar.classList.remove('hidden');
            sidebar.classList.add('fixed', 'top-0', 'left-0', 'h-screen', 'w-80', 'mt-0', 'rounded-none');
            sidebar.classList.remove('absolute', 'mt-2');
            overlay.classList.remove('hidden');
        } else {
            sidebar.classList.add('hidden');
            sidebar.classList.remove('fixed', 'top-0', 'left-0', 'h-screen', 'w-80', 'mt-0', 'rounded-none');
            sidebar.classList.add('absolute', 'mt-2');
            overlay.classList.add('hidden');
        }
    } else {
        // Desktop: simple toggle
        if (isHidden) {
            sidebar.classList.remove('hidden');
        } else {
            sidebar.classList.add('hidden');
        }
    }
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const sidebar = document.getElementById('filterSidebar');
    const filterBtn = document.getElementById('filterBtn');
    const overlay = document.getElementById('mobileFilterOverlay');
    
    if (window.innerWidth >= 1024) {
        // Desktop: close if clicking outside
        if (!sidebar.contains(event.target) && !filterBtn.contains(event.target)) {
            sidebar.classList.add('hidden');
        }
    } else {
        // Mobile: close on overlay click (handled by overlay onclick)
    }
});
</script>

@php
    $googleMapsApiKey = config('services.google_maps.api_key');
@endphp
@if($googleMapsApiKey)
<script>
// Initialize Google Maps Places Autocomplete for location input
function initLocationAutocomplete() {
    const locationInput = document.getElementById('filter_location_sidebar');
    if (!locationInput) return;

    // Initialize Google Places Autocomplete for location field
    const autocomplete = new google.maps.places.Autocomplete(locationInput, {
        types: ['geocode'],
        fields: ['formatted_address', 'geometry', 'address_components']
    });

    // When a place is selected, update the input with the formatted address
    autocomplete.addListener('place_changed', function() {
        const place = autocomplete.getPlace();
        if (place.formatted_address) {
            locationInput.value = place.formatted_address;
        }
    });
}

// Load Google Maps API with Places library
(function() {
    // Check if script already exists
    if (document.querySelector('script[src*="maps.googleapis.com"]')) {
        // Script already loaded, initialize directly
        if (typeof google !== 'undefined' && google.maps && google.maps.places) {
            initLocationAutocomplete();
        }
    } else {
        const script = document.createElement('script');
        script.src = 'https://maps.googleapis.com/maps/api/js?key={{ $googleMapsApiKey }}&loading=async&libraries=places&callback=initLocationAutocompleteCallback';
        script.async = true;
        script.defer = true;
        document.head.appendChild(script);
    }
})();

// Callback function for when Google Maps loads
window.initLocationAutocompleteCallback = function() {
    if (typeof google !== 'undefined' && google.maps && google.maps.places) {
        initLocationAutocomplete();
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
