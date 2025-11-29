@extends('layouts.dashboard')

@section('title', 'Members - ' . config('app.name'))

@section('content')
<div class="min-h-screen bg-gray-900">
    <!-- Header Section with Search -->
    <div class="mb-6">
        <!-- Search Bar with Filter Icon -->
        <div class="w-full">
            <div class="relative">
                <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xl"></i>
                <input 
                    type="text" 
                    placeholder="Search by name, location, interests..." 
                    class="w-full text-sm bg-gray-800 border border-gray-700 rounded-2xl pl-10 pr-10 py-3 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                />
                <button class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white transition-colors">
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
                <select class="w-full bg-gray-700 border border-gray-600 rounded-2xl px-3 py-2 text-white text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option>Best Match</option>
                    <option>Newest</option>
                    <option>Distance</option>
                    <option>Popularity</option>
                </select>
            </div>

            <!-- Category -->
            <div>
                <label class="block text-xs text-gray-400 mb-1">Category</label>
                <select class="w-full bg-gray-700 border border-gray-600 rounded-2xl px-3 py-2 text-white text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option>All Categories</option>
                    <option>Single</option>
                    <option>Couple</option>
                    <option>Group</option>
                </select>
            </div>

            <!-- Distance -->
            <div>
                <label class="block text-xs text-gray-400 mb-1">Distance</label>
                <select class="w-full bg-gray-700 border border-gray-600 rounded-2xl px-3 py-2 text-white text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option>Within 25 miles</option>
                    <option>Within 10 miles</option>
                    <option>Within 50 miles</option>
                    <option>Any Distance</option>
                </select>
            </div>

            <!-- Age Range -->
            <div>
                <label class="block text-xs text-gray-400 mb-1">Age Range</label>
                <select class="w-full bg-gray-700 border border-gray-600 rounded-2xl px-3 py-2 text-white text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option>Any Age</option>
                    <option>18-25</option>
                    <option>26-35</option>
                    <option>36-45</option>
                    <option>45+</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Profile Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @forelse($members as $member)
            <div class="bg-gray-800 rounded-2xl overflow-hidden border border-gray-700 hover:border-purple-500 transition-all hover:shadow-lg hover:shadow-purple-500/20">
                <!-- Profile Image -->
                <div class="relative">
                    <img 
                        src="{{ $member->profile_photo ?? asset('assets/profileUser.png') }}" 
                        alt="{{ $member->name }}"
                        class="w-full h-64 object-cover"
                        onerror="this.src='https://via.placeholder.com/400x400/6366f1/ffffff?text={{ urlencode($member->name) }}'"
                    />
                    
                    <div class="absolute top-2 left-2 flex flex-col items-start gap-2">
                        <!-- Verified Badge -->
                        <div class="bg-blue-500 text-white text-xs font-semibold px-2 py-1 rounded-xl flex items-center gap-1">
                            <i class="ri-check-line text-sm"></i>
                            <span class="font-light">Verified</span>
                        </div>

                        <!-- Online Badge (if online) -->
                        @if(rand(0, 1))
                            <div class="font-light bg-green-500 text-white text-xs font-semibold px-2 py-1 rounded-xl">
                                Online
                            </div>
                        @endif
                    </div>

                    <!-- Distance Badge -->
                    <div class="absolute bottom-2 right-2 bg-gray-900/80 text-white text-xs font-semibold px-2 py-1 rounded-xl">
                        <i class="ri-map-pin-line text-sm mr-1"></i>
                        <span class="font-light">{{ rand(5, 25) }} miles</span>  
                    </div>
                </div>

                <!-- Profile Info -->
                <div class="p-4">
                    <h3 class="text-white mb-1">{{ $member->name }}</h3>
                    <p class="text-gray-400 text-sm mb-2">
                        {{ $member->age ?? rand(25, 35) }} • 
                        @if($member->profile_type === 'couple')
                            Couple
                        @elseif($member->profile_type === 'group')
                            Group
                        @else
                            Single {{ ucfirst($member->gender ?? 'Male') }}
                        @endif
                    </p>
                    <p class="text-gray-400 text-sm mb-5    ">
                        <i class="ri-map-pin-line text-sm mr-0.5"></i>
                        <span>
                         {{ $member->location ?? 'California, USA' }}
                        </span>
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
                            <button class="bg-[#9810FA] hover:bg-purple-700 text-white text-xs font-semibold px-4 py-1.5 rounded-full flex items-center gap-1 transition-colors">
                                <i class="ri-user-add-line"></i>
                                <span>Connect</span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
        @endforelse
    </div>
</div>
@endsection

