@extends('layouts.dashboard')

@section('title', 'Members - ' . config('app.name'))

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Header Section with Search -->
    <form method="GET" action="{{ route('dashboard.members') }}" id="filterForm">
        <div class="mb-6">
            <!-- Filter Grid with 6 Columns -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm dark:shadow-none border border-gray-200 dark:border-gray-700">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Search / Name -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="ri-search-line mr-1"></i> Search
                        </label>
                        <input 
                            type="text" 
                            name="search"
                            id="search"
                            value="{{ request('search') }}"
                            placeholder="Name, location, interests..." 
                            class="w-full text-sm bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl px-4 py-3 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                        />
                    </div>


                    <!-- Category -->
                    <div>
                        <label for="filter_category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="ri-group-line mr-1"></i> Category
                        </label>
                        <select 
                            name="filter_category"
                            id="filter_category"
                            class="w-full text-sm bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl px-4 py-3 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-purple-500"
                        >
                            <option value="">All Categories</option>
                            <option value="single_male" {{ request('filter_category') == 'single_male' ? 'selected' : '' }}>Single Male</option>
                            <option value="single_female" {{ request('filter_category') == 'single_female' ? 'selected' : '' }}>Single Female</option>
                            <option value="couple" {{ request('filter_category') == 'couple' ? 'selected' : '' }}>Couple</option>
                            <option value="group" {{ request('filter_category') == 'group' ? 'selected' : '' }}>Group</option>
                        </select>
                    </div>

                    <!-- Location -->
                    <div>
                        <label for="filter_location" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="ri-map-pin-line mr-1"></i> Location
                        </label>
                        <input 
                            type="text" 
                            name="filter_location"
                            id="filter_location"
                            value="{{ request('filter_location') }}"
                            placeholder="Enter location..." 
                            class="w-full text-sm bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl px-4 py-3 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                        />
                    </div>

                  
                </div>

                <!-- Search Button and Online Toggle -->
                <div class="mt-6 flex items-center justify-between">
                    <button 
                        type="submit" 
                        class="bg-[#9810FA] hover:bg-purple-700 text-white text-sm font-semibold px-8 py-3 rounded-xl transition-colors flex items-center gap-2"
                    >
                        <i class="ri-search-line"></i>
                        Search
                    </button>

                    <!-- Online Users Toggle Filter -->
                    <div class="flex items-center gap-3">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" 
                                   name="online_only" 
                                   value="1"
                                   id="onlineOnlyToggle"
                                   {{ request('online_only') ? 'checked' : '' }}
                                   onchange="document.getElementById('filterForm').submit();"
                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-[#9810FA]"></div>
                        </label>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">
                            <i class="ri-wifi-line text-lg mr-1"></i>
                            Show only online users
                        </span>
                    </div>
                </div>
            </div>
        </div>


        <!-- Filter Bar -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 mb-6 shadow-sm dark:shadow-none border border-gray-200 dark:border-gray-700">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Sort By -->
                <div>
                    <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Sort By</label>
                    <select name="sort_by" onchange="document.getElementById('filterForm').submit();" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl px-3 py-2 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="All" {{ !request('sort_by') || request('sort_by') == 'All' ? 'selected' : '' }}>All</option>
                        <option value="Best Match" {{ request('sort_by') == 'Best Match' ? 'selected' : '' }}>Best Match</option>
                        <option value="Newest" {{ request('sort_by') == 'Newest' ? 'selected' : '' }}>Newest</option>
                        <option value="Distance" {{ request('sort_by') == 'Distance' ? 'selected' : '' }}>Distance</option>
                        <option value="Popularity" {{ request('sort_by') == 'Popularity' ? 'selected' : '' }}>Popularity</option>
                    </select>
                </div>

                <!-- Distance -->
                <div>
                    <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Distance</label>
                    <select name="distance" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl px-3 py-2 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="Any Distance" {{ request('distance') == 'Any Distance' || !request('distance') ? 'selected' : '' }}>Any Distance</option>
                        <option value="10" {{ request('distance') == '10' ? 'selected' : '' }}>Within 10 km</option>
                        <option value="25" {{ request('distance') == '25' ? 'selected' : '' }}>Within 25 km</option>
                        <option value="50" {{ request('distance') == '50' ? 'selected' : '' }}>Within 50 km</option>
                        <option value="100" {{ request('distance') == '100' ? 'selected' : '' }}>Within 100 km</option>
                    </select>
                </div>

                <!-- Age Range -->
                <div>
                    <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Age Range</label>
                    <select name="age_range" onchange="document.getElementById('filterForm').submit();" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl px-3 py-2 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="Any Age" {{ request('age_range') == 'Any Age' || !request('age_range') ? 'selected' : '' }}>Any Age</option>
                        <option value="18-25" {{ request('age_range') == '18-25' ? 'selected' : '' }}>18-25</option>
                        <option value="26-35" {{ request('age_range') == '26-35' ? 'selected' : '' }}>26-35</option>
                        <option value="36-45" {{ request('age_range') == '36-45' ? 'selected' : '' }}>36-45</option>
                        <option value="45+" {{ request('age_range') == '45+' ? 'selected' : '' }}>45+</option>
                    </select>
                </div>
            </div>
        </div>
    </form>

    <!-- Profile Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
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
                // Check online status - respect privacy setting
                // Only hide online status if profile exists AND show_online_status is explicitly false
                $hideOnlineStatus = $profile && $profile->show_online_status === false;
                $isOnline = !$hideOnlineStatus && $member->isOnline();
            @endphp
            <a href="{{ route('user.profile', $member->username ?: $member->id) }}" class="block bg-white dark:bg-gray-800 rounded-2xl overflow-hidden border border-gray-200 dark:border-gray-700 hover:border-purple-500 transition-all hover:shadow-lg hover:shadow-purple-500/20">
                <!-- Profile Image -->
                <div class="relative">
                    <img 
                        src="{{ $profilePhoto }}" 
                        alt="{{ $displayName }}"
                        class="w-full h-64 object-cover bg-gray-200 dark:bg-gray-700"
                        onerror="this.onerror=null; this.src='{{ $placeholderSvg }}';"
                    />
                    
                    <div class="absolute top-2 left-2 flex flex-col items-start gap-2">
                        <!-- Verified Badge -->
                        <!-- @if($member->email_verified_at)
                            <div class="bg-blue-500 text-white text-xs font-semibold px-2 py-1 rounded-xl flex items-center gap-1">
                                <i class="ri-check-line text-sm"></i>
                                <span class="font-light">Verified</span>
                            </div>
                        @endif -->

                        <!-- Online Badge -->
                        
                        @if($isOnline)
                            <div class="bg-green-500 text-white text-xs font-semibold px-2 py-1 rounded-xl flex items-center gap-1">
                                <div class="w-2 h-2 bg-white rounded-full animate-pulse"></div>
                                <span>Online</span>
                            </div>
                        @endif
                    </div>

                    <!-- Distance Badge (static - not in DB) -->
                    <div class="absolute bottom-2 right-2 bg-black/60 dark:bg-gray-900/80 text-white text-xs font-semibold px-2 py-1 rounded-xl">
                        <i class="ri-map-pin-line text-sm mr-1"></i>
                        <span class="font-light">{{ rand(5, 25) }} km</span>  
                    </div>
                </div>

                <!-- Profile Info -->
                <div class="p-4">
                    <h3 class="text-gray-900 dark:text-white mb-1">{{ $displayName }}</h3>
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
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-1 text-red-500">
                            <i class="ri-heart-fill text-lg"></i>
                            <span class="text-gray-900 dark:text-white text-sm font-medium">{{ 0 }}</span>
                        </div>
                        <!-- @if(rand(0, 4) === 0)
                            <button class="bg-[#9810FA] text-white text-xs font-semibold px-4 py-1.5 rounded-lg" onclick="event.stopPropagation();">
                                Connected
                            </button>
                        @else
                            <button onclick="event.stopPropagation(); window.location.href='{{ route('user.profile', $member->username ?: $member->id) }}'" class="hidden bg-[#9810FA] hover:bg-purple-700 text-white text-xs font-semibold px-4 py-1.5 rounded-full flex items-center gap-1 transition-colors">
                                <i class="ri-user-add-line"></i>
                                <span>Connect</span>
                            </button>
                        @endif -->
                    </div>
                </div>
            </a>
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

        <!-- Next Button - Always enabled, shows random on last page -->
        @if($members->hasMorePages())
            <a href="{{ $members->nextPageUrl() }}" class="px-3 py-2 rounded-lg bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                <i class="ri-arrow-right-s-line text-xl"></i>
            </a>
        @else
            {{-- On last page, show random members --}}
            @php
                $queryParams = request()->except(['page', 'random']);
                $queryParams['random'] = '1';
            @endphp
            <a href="{{ route('dashboard.members', $queryParams) }}" class="px-3 py-2 rounded-lg bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                <i class="ri-arrow-right-s-line text-xl"></i>
            </a>
        @endif
    </div>

    <!-- Footer -->
</div>

@push('scripts')
@php
    $googleMapsApiKey = config('services.google_maps.api_key');
@endphp
@if($googleMapsApiKey)
<script>
// Initialize Google Maps Places Autocomplete for location input
function initLocationAutocomplete() {
    const locationInput = document.getElementById('filter_location');
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

