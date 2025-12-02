@extends('layouts.dashboard')

@section('title', 'Members - ' . config('app.name'))

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Header Section with Search -->
    <form method="GET" action="{{ route('dashboard.members') }}" id="filterForm">
        <div class="mb-6">
            <!-- Enhanced Search Bar with Filter Dropdown -->
            <div class="w-full">
                <div class="flex gap-2">
                    <!-- Filter Type Dropdown -->
                    <div class="relative">
                        <select 
                            name="filter_type" 
                            id="filterType"
                            onchange="updateSearchPlaceholder(); handleFilterTypeChange(this.value);"
                            class="text-sm bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-2xl px-4 py-3 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-purple-500 min-w-[180px]"
                        >
                            <option value="all" {{ request('filter_type') == 'all' || !request('filter_type') ? 'selected' : '' }}>All</option>
                            <option value="gender" {{ request('filter_type') == 'gender' ? 'selected' : '' }}>Gender</option>
                            <option value="company" {{ request('filter_type') == 'company' ? 'selected' : '' }}>Company</option>
                            <option value="location" {{ request('filter_type') == 'location' ? 'selected' : '' }}>Location</option>
                            <option value="country" {{ request('filter_type') == 'country' ? 'selected' : '' }}>Country</option>
                            <option value="city" {{ request('filter_type') == 'city' ? 'selected' : '' }}>City</option>
                            <option value="profile_type" {{ request('filter_type') == 'profile_type' ? 'selected' : '' }}>Profile Type</option>
                            <option value="category" {{ request('filter_type') == 'category' ? 'selected' : '' }}>Category</option>
                            <option value="eye_color" {{ request('filter_type') == 'eye_color' ? 'selected' : '' }}>Eye Color</option>
                            <option value="preferences" {{ request('filter_type') == 'preferences' ? 'selected' : '' }}>Things They Like</option>
                        </select>
                    </div>

                    <!-- Search Input / Select Field -->
                    <div class="relative flex-1">
                        <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400 text-xl"></i>
                        
                        <!-- Text Input for General Search -->
                        <input 
                            type="text" 
                            name="search"
                            id="searchInput"
                            value="{{ request('search') }}"
                            placeholder="Search by name, location, interests..." 
                            class="w-full text-sm bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-2xl pl-10 pr-32 py-3 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            style="display: {{ request('filter_type') && request('filter_type') != 'all' ? 'none' : 'block' }};"
                        />

                        <!-- Gender Dropdown -->
                        <select 
                            name="filter_gender"
                            id="filterGender"
                            class="w-full text-sm bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-2xl pl-10 pr-32 py-3 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-purple-500"
                            style="display: {{ request('filter_type') == 'gender' ? 'block' : 'none' }};"
                            onchange="document.getElementById('filterForm').submit();"
                        >
                            <option value="">Select Gender</option>
                            <option value="male" {{ request('filter_gender') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ request('filter_gender') == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ request('filter_gender') == 'other' ? 'selected' : '' }}>Other</option>
                            <option value="prefer_not_to_say" {{ request('filter_gender') == 'prefer_not_to_say' ? 'selected' : '' }}>Prefer Not to Say</option>
                        </select>

                        <!-- Company Input -->
                        <input 
                            type="text" 
                            name="filter_company"
                            id="filterCompany"
                            value="{{ request('filter_company') }}"
                            placeholder="Enter company name..." 
                            class="w-full text-sm bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-2xl pl-10 pr-32 py-3 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            style="display: {{ request('filter_type') == 'company' ? 'block' : 'none' }};"
                        />

                        <!-- Location Input -->
                        <input 
                            type="text" 
                            name="filter_location"
                            id="filterLocation"
                            value="{{ request('filter_location') }}"
                            placeholder="Enter location..." 
                            class="w-full text-sm bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-2xl pl-10 pr-32 py-3 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            style="display: {{ request('filter_type') == 'location' ? 'block' : 'none' }};"
                        />

                        <!-- Country Input -->
                        <input 
                            type="text" 
                            name="filter_country"
                            id="filterCountry"
                            value="{{ request('filter_country') }}"
                            placeholder="Enter country..." 
                            class="w-full text-sm bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-2xl pl-10 pr-32 py-3 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            style="display: {{ request('filter_type') == 'country' ? 'block' : 'none' }};"
                        />

                        <!-- City Input -->
                        <input 
                            type="text" 
                            name="filter_city"
                            id="filterCity"
                            value="{{ request('filter_city') }}"
                            placeholder="Enter city..." 
                            class="w-full text-sm bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-2xl pl-10 pr-32 py-3 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            style="display: {{ request('filter_type') == 'city' ? 'block' : 'none' }};"
                        />

                        <!-- Profile Type Dropdown -->
                        <select 
                            name="filter_profile_type"
                            id="filterProfileType"
                            class="w-full text-sm bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-2xl pl-10 pr-32 py-3 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-purple-500"
                            style="display: {{ request('filter_type') == 'profile_type' ? 'block' : 'none' }};"
                            onchange="document.getElementById('filterForm').submit();"
                        >
                            <option value="">Select Profile Type</option>
                            <option value="normal" {{ request('filter_profile_type') == 'normal' ? 'selected' : '' }}>Normal</option>
                            <option value="business" {{ request('filter_profile_type') == 'business' ? 'selected' : '' }}>Business</option>
                        </select>

                        <!-- Category Dropdown -->
                        <select 
                            name="filter_category"
                            id="filterCategory"
                            class="w-full text-sm bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-2xl pl-10 pr-32 py-3 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-purple-500"
                            style="display: {{ request('filter_type') == 'category' ? 'block' : 'none' }};"
                            onchange="document.getElementById('filterForm').submit();"
                        >
                            <option value="">Select Category</option>
                            <option value="single_male" {{ request('filter_category') == 'single_male' ? 'selected' : '' }}>Single Male</option>
                            <option value="single_female" {{ request('filter_category') == 'single_female' ? 'selected' : '' }}>Single Female</option>
                            <option value="couple" {{ request('filter_category') == 'couple' ? 'selected' : '' }}>Couple</option>
                            <option value="group" {{ request('filter_category') == 'group' ? 'selected' : '' }}>Group</option>
                        </select>

                        <!-- Eye Color Dropdown -->
                        <select 
                            name="filter_eye_color"
                            id="filterEyeColor"
                            class="w-full text-sm bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-2xl pl-10 pr-32 py-3 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-purple-500"
                            style="display: {{ request('filter_type') == 'eye_color' ? 'block' : 'none' }};"
                            onchange="document.getElementById('filterForm').submit();"
                        >
                            <option value="">Select Eye Color</option>
                            <option value="brown" {{ request('filter_eye_color') == 'brown' ? 'selected' : '' }}>Brown</option>
                            <option value="blue" {{ request('filter_eye_color') == 'blue' ? 'selected' : '' }}>Blue</option>
                            <option value="green" {{ request('filter_eye_color') == 'green' ? 'selected' : '' }}>Green</option>
                            <option value="hazel" {{ request('filter_eye_color') == 'hazel' ? 'selected' : '' }}>Hazel</option>
                            <option value="gray" {{ request('filter_eye_color') == 'gray' ? 'selected' : '' }}>Gray</option>
                            <option value="amber" {{ request('filter_eye_color') == 'amber' ? 'selected' : '' }}>Amber</option>
                        </select>

                        <!-- Preferences (Things They Like) Dropdown -->
                        <select 
                            name="filter_preferences"
                            id="filterPreferences"
                            class="w-full text-sm bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-2xl pl-10 pr-32 py-3 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-purple-500"
                            style="display: {{ request('filter_type') == 'preferences' ? 'block' : 'none' }};"
                            onchange="document.getElementById('filterForm').submit();"
                        >
                            <option value="">Select Preference</option>
                            <option value="full_swap" {{ request('filter_preferences') == 'full_swap' ? 'selected' : '' }}>Full Swap</option>
                            <option value="soft_swap" {{ request('filter_preferences') == 'soft_swap' ? 'selected' : '' }}>Soft Swap</option>
                            <option value="exhibitionist" {{ request('filter_preferences') == 'exhibitionist' ? 'selected' : '' }}>Exhibitionist</option>
                            <option value="voyeur" {{ request('filter_preferences') == 'voyeur' ? 'selected' : '' }}>Voyeur</option>
                            <option value="still_exploring" {{ request('filter_preferences') == 'still_exploring' ? 'selected' : '' }}>Still Exploring</option>
                            <option value="hotwife" {{ request('filter_preferences') == 'hotwife' ? 'selected' : '' }}>Hotwife</option>
                            <option value="others" {{ request('filter_preferences') == 'others' ? 'selected' : '' }}>Others</option>
                        </select>

                        <!-- Search Button -->
                        <button 
                            type="submit" 
                            class="absolute right-2 top-1/2 -translate-y-1/2 bg-[#9810FA] hover:bg-purple-700 text-white text-sm font-semibold px-4 py-2 rounded-xl transition-colors"
                        >
                            Search
                        </button>
                    </div>
                </div>
            </div>
        </div>

    <!-- action buttons -->
    <!-- <div class="flex items-center justify-end gap-3 mb-6">
            <button class="px-2 py-1.5 bg-gray-800 border border-gray-700 rounded-lg text-gray-300 hover:text-white hover:bg-gray-700 transition-colors">
                <i class="ri-apps-2-line text-normal"></i>
            </button>
            <button class="px-2 py-1.5 bg-gray-800 border border-gray-700 rounded-lg text-gray-300 hover:text-white hover:bg-gray-700 transition-colors">
                <i class="ri-map-2-line text-normal"></i>
            </button>
    </div> -->

        <!-- Filter Bar -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 mb-6 shadow-sm dark:shadow-none border border-gray-200 dark:border-gray-700">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
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

                <!-- Category -->
                <div>
                    <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Category</label>
                    <select name="category" onchange="document.getElementById('filterForm').submit();" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl px-3 py-2 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="All Categories" {{ request('category') == 'All Categories' || !request('category') ? 'selected' : '' }}>All Categories</option>
                        <option value="single_male" {{ request('category') == 'single_male' ? 'selected' : '' }}>Single Male</option>
                        <option value="single_female" {{ request('category') == 'single_female' ? 'selected' : '' }}>Single Female</option>
                        <option value="couple" {{ request('category') == 'couple' ? 'selected' : '' }}>Couple</option>
                        <option value="group" {{ request('category') == 'group' ? 'selected' : '' }}>Group</option>
                    </select>
                </div>

                <!-- Distance -->
                <div>
                    <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Distance</label>
                    <select name="distance" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-2xl px-3 py-2 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="Any Distance" {{ request('distance') == 'Any Distance' || !request('distance') ? 'selected' : '' }}>Any Distance</option>
                        <option value="10" {{ request('distance') == '10' ? 'selected' : '' }}>Within 10 miles</option>
                        <option value="25" {{ request('distance') == '25' ? 'selected' : '' }}>Within 25 miles</option>
                        <option value="50" {{ request('distance') == '50' ? 'selected' : '' }}>Within 50 miles</option>
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
                // Get profile photo with proper fallback
                $profilePhoto = null;
                if ($profile && $profile->profile_photo && file_exists(public_path('storage/' . $profile->profile_photo))) {
                    $profilePhoto = asset('storage/' . $profile->profile_photo);
                } elseif ($member->profile_image && file_exists(public_path('storage/' . $member->profile_image))) {
                    $profilePhoto = asset('storage/' . $member->profile_image);
                } else {
                    $profilePhoto = asset('assets/profileUser.png');
                }
                $category = $profile && $profile->category ? $profile->category : 'single_male';
                $displayName = $member->name ?: ($member->first_name . ' ' . $member->last_name) ?: 'User #' . $member->id;
            @endphp
            <a href="{{ route('user.profile', $member->id) }}" class="block bg-white dark:bg-gray-800 rounded-2xl overflow-hidden border border-gray-200 dark:border-gray-700 hover:border-purple-500 transition-all hover:shadow-lg hover:shadow-purple-500/20">
                <!-- Profile Image -->
                <div class="relative">
                    <img 
                        src="{{ $profilePhoto }}" 
                        alt="{{ $displayName }}"
                        class="w-full h-64 object-cover"
                        onerror="this.onerror=null; this.src='https://via.placeholder.com/400x400/6366f1/ffffff?text={{ urlencode(strtoupper(substr($displayName, 0, 1))) }}';"
                    />
                    
                    <div class="absolute top-2 left-2 flex flex-col items-start gap-2">
                        <!-- Verified Badge -->
                        @if($member->email_verified_at)
                            <div class="bg-blue-500 text-white text-xs font-semibold px-2 py-1 rounded-xl flex items-center gap-1">
                                <i class="ri-check-line text-sm"></i>
                                <span class="font-light">Verified</span>
                            </div>
                        @endif

                        <!-- Online Badge (static - not in DB) -->
                        @if(rand(0, 1))
                            <div class="bg-green-500 text-white text-xs font-semibold px-2 py-1 rounded-xl">
                                Online
                            </div>
                        @endif
                    </div>

                    <!-- Distance Badge (static - not in DB) -->
                    <div class="absolute bottom-2 right-2 bg-black/60 dark:bg-gray-900/80 text-white text-xs font-semibold px-2 py-1 rounded-xl">
                        <i class="ri-map-pin-line text-sm mr-1"></i>
                        <span class="font-light">{{ rand(5, 25) }} miles</span>  
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
                            <span class="text-gray-900 dark:text-white text-sm font-medium">{{ rand(200, 2000) }}</span>
                        </div>
                        @if(rand(0, 4) === 0)
                            <button class="bg-[#9810FA] text-white text-xs font-semibold px-4 py-1.5 rounded-lg" onclick="event.stopPropagation();">
                                Connected
                            </button>
                        @else
                            <button onclick="event.stopPropagation(); window.location.href='{{ route('user.profile', $member->id) }}'" class="bg-[#9810FA] hover:bg-purple-700 text-white text-xs font-semibold px-4 py-1.5 rounded-full flex items-center gap-1 transition-colors">
                                <i class="ri-user-add-line"></i>
                                <span>Connect</span>
                            </button>
                        @endif
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
    @if($members->hasPages())
        <div class="mt-6 flex justify-center">
            <div class="text-gray-900 dark:text-gray-100">
                {{ $members->links() }}
            </div>
        </div>
    @endif

    <!-- Footer -->
</div>

@push('scripts')
<script>
    function updateSearchPlaceholder() {
        const filterType = document.getElementById('filterType').value;
        const searchInput = document.getElementById('searchInput');
        const filterGender = document.getElementById('filterGender');
        const filterCompany = document.getElementById('filterCompany');
        const filterLocation = document.getElementById('filterLocation');
        const filterCountry = document.getElementById('filterCountry');
        const filterCity = document.getElementById('filterCity');
        const filterProfileType = document.getElementById('filterProfileType');
        const filterCategory = document.getElementById('filterCategory');
        const filterEyeColor = document.getElementById('filterEyeColor');
        const filterPreferences = document.getElementById('filterPreferences');

        // Hide all inputs first
        searchInput.style.display = 'none';
        filterGender.style.display = 'none';
        filterCompany.style.display = 'none';
        filterLocation.style.display = 'none';
        filterCountry.style.display = 'none';
        filterCity.style.display = 'none';
        filterProfileType.style.display = 'none';
        filterCategory.style.display = 'none';
        filterEyeColor.style.display = 'none';
        filterPreferences.style.display = 'none';

        // Show the appropriate input based on filter type
        if (filterType === 'all') {
            searchInput.style.display = 'block';
            searchInput.placeholder = 'Search by name, location, interests...';
        } else if (filterType === 'gender') {
            filterGender.style.display = 'block';
        } else if (filterType === 'company') {
            filterCompany.style.display = 'block';
        } else if (filterType === 'location') {
            filterLocation.style.display = 'block';
        } else if (filterType === 'country') {
            filterCountry.style.display = 'block';
        } else if (filterType === 'city') {
            filterCity.style.display = 'block';
        } else if (filterType === 'profile_type') {
            filterProfileType.style.display = 'block';
        } else if (filterType === 'category') {
            filterCategory.style.display = 'block';
        } else if (filterType === 'eye_color') {
            filterEyeColor.style.display = 'block';
        } else if (filterType === 'preferences') {
            filterPreferences.style.display = 'block';
        }
    }

    function handleFilterTypeChange(filterType) {
        // Clear all filter values when changing filter type
        if (filterType !== '{{ request('filter_type', 'all') }}') {
            // Reset form values when switching filter types
            document.getElementById('filterGender').value = '';
            document.getElementById('filterCompany').value = '';
            document.getElementById('filterLocation').value = '';
            document.getElementById('filterCountry').value = '';
            document.getElementById('filterCity').value = '';
            document.getElementById('filterProfileType').value = '';
            document.getElementById('filterCategory').value = '';
            document.getElementById('filterEyeColor').value = '';
            document.getElementById('filterPreferences').value = '';
            document.getElementById('searchInput').value = '';
        }
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateSearchPlaceholder();
    });
</script>
@endpush
@endsection

