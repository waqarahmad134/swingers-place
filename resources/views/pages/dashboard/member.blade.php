@extends('layouts.dashboard')

@section('title', 'Members - ' . config('app.name'))

@section('content')
<div class="min-h-screen bg-gray-900">
    <!-- Header Section with Search -->
    <form method="GET" action="{{ route('dashboard.members') }}" id="filterForm">
        <div class="mb-6">
            <!-- Search Bar with Filter Icon -->
            <div class="w-full">
                <div class="relative">
                    <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xl"></i>
                    <input 
                        type="text" 
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search by name, location, interests..." 
                        class="w-full text-sm bg-gray-800 border border-gray-700 rounded-2xl pl-10 pr-10 py-3 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    />
                    <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white transition-colors">
                        <i class="ri-filter-3-line text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

    <!-- action buttons -->
    <div class="flex items-center justify-end gap-3 mb-6">
            <button class="px-2 py-1.5 bg-gray-800 border border-gray-700 rounded-lg text-gray-300 hover:text-white hover:bg-gray-700 transition-colors">
                <i class="ri-apps-2-line text-normal"></i>
            </button>
            <button class="px-2 py-1.5 bg-gray-800 border border-gray-700 rounded-lg text-gray-300 hover:text-white hover:bg-gray-700 transition-colors">
                <i class="ri-map-2-line text-normal"></i>
            </button>
    </div>

        <!-- Filter Bar -->
        <div class="bg-gray-800 rounded-2xl p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Sort By -->
                <div>
                    <label class="block text-xs text-gray-400 mb-1">Sort By</label>
                    <select name="sort_by" onchange="document.getElementById('filterForm').submit();" class="w-full bg-gray-700 border border-gray-600 rounded-2xl px-3 py-2 text-white text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="All" {{ !request('sort_by') || request('sort_by') == 'All' ? 'selected' : '' }}>All</option>
                        <option value="Best Match" {{ request('sort_by') == 'Best Match' ? 'selected' : '' }}>Best Match</option>
                        <option value="Newest" {{ request('sort_by') == 'Newest' ? 'selected' : '' }}>Newest</option>
                        <option value="Distance" {{ request('sort_by') == 'Distance' ? 'selected' : '' }}>Distance</option>
                        <option value="Popularity" {{ request('sort_by') == 'Popularity' ? 'selected' : '' }}>Popularity</option>
                    </select>
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-xs text-gray-400 mb-1">Category</label>
                    <select name="category" onchange="document.getElementById('filterForm').submit();" class="w-full bg-gray-700 border border-gray-600 rounded-2xl px-3 py-2 text-white text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="All Categories" {{ request('category') == 'All Categories' || !request('category') ? 'selected' : '' }}>All Categories</option>
                        <option value="single_male" {{ request('category') == 'single_male' ? 'selected' : '' }}>Single Male</option>
                        <option value="single_female" {{ request('category') == 'single_female' ? 'selected' : '' }}>Single Female</option>
                        <option value="couple" {{ request('category') == 'couple' ? 'selected' : '' }}>Couple</option>
                        <option value="group" {{ request('category') == 'group' ? 'selected' : '' }}>Group</option>
                    </select>
                </div>

                <!-- Distance -->
                <div>
                    <label class="block text-xs text-gray-400 mb-1">Distance</label>
                    <select name="distance" class="w-full bg-gray-700 border border-gray-600 rounded-2xl px-3 py-2 text-white text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="Any Distance" {{ request('distance') == 'Any Distance' || !request('distance') ? 'selected' : '' }}>Any Distance</option>
                        <option value="10" {{ request('distance') == '10' ? 'selected' : '' }}>Within 10 miles</option>
                        <option value="25" {{ request('distance') == '25' ? 'selected' : '' }}>Within 25 miles</option>
                        <option value="50" {{ request('distance') == '50' ? 'selected' : '' }}>Within 50 miles</option>
                    </select>
                </div>

                <!-- Age Range -->
                <div>
                    <label class="block text-xs text-gray-400 mb-1">Age Range</label>
                    <select name="age_range" onchange="document.getElementById('filterForm').submit();" class="w-full bg-gray-700 border border-gray-600 rounded-2xl px-3 py-2 text-white text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
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
            <div class="bg-gray-800 rounded-2xl overflow-hidden border border-gray-700 hover:border-purple-500 transition-all hover:shadow-lg hover:shadow-purple-500/20">
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
                    <div class="absolute bottom-2 right-2 bg-gray-900/80 text-white text-xs font-semibold px-2 py-1 rounded-xl">
                        <i class="ri-map-pin-line text-sm mr-1"></i>
                        <span class="font-light">{{ rand(5, 25) }} miles</span>  
                    </div>
                </div>

                <!-- Profile Info -->
                <div class="p-4">
                    <h3 class="text-white mb-1">{{ $displayName }}</h3>
                    <p class="text-gray-400 text-sm mb-2">
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
                    <p class="text-gray-400 text-sm mb-5">
                        <i class="ri-map-pin-line text-sm mr-0.5"></i>
                        <span>{{ $location }}</span>
                    </p>

                    <!-- Engagement Stats -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-1 text-red-500">
                            <i class="ri-heart-fill text-lg"></i>
                            <span class="text-white text-sm font-medium">{{ rand(200, 2000) }}</span>
                        </div>
                        @if(rand(0, 4) === 0)
                            <button class="bg-[#9810FA] text-white text-xs font-semibold px-4 py-1.5 rounded-lg">
                                Connected
                            </button>
                        @else
                            <a href="{{ route('user.profile', $member->id) }}" class="bg-[#9810FA] hover:bg-purple-700 text-white text-xs font-semibold px-4 py-1.5 rounded-full flex items-center gap-1 transition-colors">
                                <i class="ri-user-add-line"></i>
                                <span>Connect</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <p class="text-gray-400 text-lg">No members found matching your criteria.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($members->hasPages())
        <div class="mt-6">
            {{ $members->links() }}
        </div>
    @endif
</div>
@endsection

