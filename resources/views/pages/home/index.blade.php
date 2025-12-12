@extends('layouts.app')
@section('full-width')
    

<!-- SECTION: Hero -->
<section class="relative min-h-screen flex items-center justify-center pb-10 overflow-hidden">

    <!-- Background Video -->
    <video class="absolute inset-0 w-full h-full object-cover z-0" autoplay muted loop playsinline>
        <source src="{{ asset('assets/bg-video.mp4') }}" type="video/mp4">
    </video>

    <!-- Dark Overlay -->
    <div class="absolute inset-0 bg-black/50 z-10"></div>

    <div class="relative z-10 mt-32 flex flex-col text-center px-4">

        <h1 class="text-6xl font-['Leckerli_One'] text-white font-thin mb-4 leading-tight">
            Dating for <br />
            <span class="text-[#FB4F7B]">Naughty</span> Adults
        </h1>

        <p class="md:text-lg text-sm text-white mb-10">
            Meet real people nearby who are ready for genuine connections
        </p>

        <p class="md:text-xl mt-7 text-white font-normal mb-6">Free Sign Up</p>

        <div class="grid md:grid-cols-2 gap-4 mx-auto">
            <a href="{{ route('register') }}?type=couple">
                <button
                    class="flex  w-full items-center justify-center gap-2 border-2 border-white bg-black/50 hover:bg-black/70 text-gray-200 hover:text-white px-20 py-4 rounded-lg shadow-xl backdrop-blur-sm transition duration-300">
                    <i class="ri-group-line"></i>
                    <span>We are a Couple</span>
                </button>
            </a>

            <a href="{{ route('register') }}?type=single_female">
                <button
                    class="flex w-full items-center justify-center gap-2 border-2 border-white bg-black/50 hover:bg-black/70 text-gray-200 hover:text-white px-10 py-4 rounded-lg shadow-xl backdrop-blur-sm transition duration-300">
                    <i class="ri-heart-line"></i>
                    <span>I'm a Single Female</span>
                </button>
            </a>

            <a href="{{ route('register') }}?type=single_male">
                <button
                    class="flex w-full items-center justify-center gap-2 border-2 border-white bg-black/50 hover:bg-black/70 text-gray-200 hover:text-white px-10 py-4 rounded-lg shadow-xl backdrop-blur-sm transition duration-300">
                    <i class="ri-group-line"></i>
                    <span>I'm a Single Male</span>
                </button>
            </a>

            <a href="{{ route('register') }}?type=non_binary">
                <button
                    class="flex w-full items-center justify-center gap-2 border border-white bg-black/50 hover:bg-black/70 text-gray-200 hover:text-white px-10 py-4 rounded-lg shadow-xl backdrop-blur-sm transition duration-300">
                    <i class="ri-flashlight-line"></i>
                    <span>Non-binary</span>
                </button>
            </a>

        </div>

    </div>
</section>

<!-- Second Find the Best Option for You -->
<section class="w-full max-w-[1180px] mx-auto flex flex-col items-center py-20 px-6">
    <div class="text-center mb-10">
        <h2 class="text-4xl font-['Grand_Hotel'] text-[#FB4F7B] mb-3 font-medium">
            Find the Best Option for You
        </h2>
        <p class="text-lg dark">
            Search through categories to discover exactly what you need
        </p>
    </div>

    <form method="GET" action="{{ auth()->check() ? route('dashboard.members') : route('login') }}" id="homeSearchForm" class="w-full">
        @if(!auth()->check())
            <input type="hidden" name="redirect_to" value="{{ route('dashboard.members') }}">
        @endif
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm dark:shadow-none border border-gray-200 dark:border-gray-700">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Search / Name -->
                <div>
                    <label for="home_search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="ri-search-line mr-1"></i> Search
                    </label>
                    <input 
                        type="text" 
                        name="search"
                        id="home_search"
                        value="{{ request('search') }}"
                        placeholder="Name, location, interests..." 
                        class="w-full text-sm bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl px-4 py-3 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#FB4F7B] focus:border-transparent"
                    />
                </div>

                <!-- Category -->
                <div>
                    <label for="home_filter_category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="ri-group-line mr-1"></i> Category
                    </label>
                    <select 
                        name="filter_category"
                        id="home_filter_category"
                        class="w-full text-sm bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl px-4 py-3 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#FB4F7B]"
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
                    <label for="home_filter_location" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="ri-map-pin-line mr-1"></i> Location
                    </label>
                    <input 
                        type="text" 
                        name="filter_location"
                        id="home_filter_location"
                        value="{{ request('filter_location') }}"
                        placeholder="Enter location..." 
                        class="w-full text-sm bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl px-4 py-3 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#FB4F7B] focus:border-transparent"
                    />
                </div>
            </div>

            <!-- Search Button and Online Toggle -->
            <div class="mt-6 flex items-center justify-between">
                <button 
                    type="submit" 
                    class="bg-[#FB4F7B] hover:bg-fuchsia-700 text-white text-sm font-semibold px-8 py-3 rounded-xl transition-colors flex items-center gap-2 shadow-xl"
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
                               id="homeOnlineOnlyToggle"
                               {{ request('online_only') ? 'checked' : '' }}
                               class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#FB4F7B]/30 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-[#FB4F7B]"></div>
                    </label>
                    <span class="text-sm font-medium text-gray-900 dark:text-white">
                        <i class="ri-wifi-line text-lg mr-1"></i>
                        Show only online users
                    </span>
                </div>
            </div>
        </div>
    </form>
</section>

<!-- SECTION: Second Want to Become a Member? -->
<section class="w-full max-w-6xl mx-auto px-6 py-16">
    <div class="text-center mb-12">
        <h2 class="text-4xl font-['Grand_Hotel'] text-[#FB4F7B] mb-6 font-medium">
            Want to Become a Member?
        </h2>
        <p class="text-lg dark max-w-3xl mx-auto leading-relaxed">
            Why pay to use a dating app if you could use our platform? Lorem ipsum
            dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
            incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-20 mt-16">
        <div class="text-center">
            <div class="w-48 h-48 mx-auto rounded-full overflow-hidden mb-6 shadow-2xl">
                <img src="./assets/want1.jpg" alt="Couple being affectionate in the woods"
                    class="w-full h-full object-cover" />
            </div>

            <h3 class="dark mb-3">100% for FREE</h3>
            <p class="text-base dark mb-6">
                Create your profile and start connecting with singles in your area
                without paying a dime.
            </p>
            <button
                class="px-8 py-3 text-sm font-semibold bg-[#FB4F7B] text-white rounded-lg transition duration-300 shadow-xl">
                Learn More
            </button>
        </div>

        <div class="text-center">
            <div class="w-48 h-48 mx-auto rounded-full overflow-hidden mb-6 shadow-2xl">
                <img src="./assets/want2.jpg" alt="Couple silhouette under a sunset sky"
                    class="w-full h-full object-cover" />
            </div>

            <h3 class="dark mb-3">Matching compatible partner</h3>
            <p class="text-base dark mb-6">
                Our advanced algorithm helps you find the perfect match based on
                your interests and preferences.
            </p>
            <button
                class="px-8 py-3 text-sm font-semibold bg-[#FB4F7B] text-white rounded-lg transition duration-300 shadow-xl">
                Learn More
            </button>
        </div>

        <div class="text-center">
            <div class="w-48 h-48 mx-auto rounded-full overflow-hidden mb-6 shadow-2xl">
                <img src="./assets/want3.jpg" alt="A group of people talking and laughing"
                    class="w-full h-full object-cover" />
            </div>

            <h3 class="dark mb-3">Share experiences</h3>
            <p class="text-base dark mb-6">
                Connect with people who share your passions and create meaningful
                memories together.
            </p>
            <button
                class="px-8 py-3 text-sm font-semibold bg-[#FB4F7B] text-white rounded-lg transition duration-300 shadow-xl">
                Learn More
            </button>
        </div>
    </div>
</section>

<!-- SEECTION:  Your Journey Starts Here -->
<section class="w-full max-w-[890px] mx-auto flex flex-col items-center py-16 mx-auto sm:px-0 px-4">
    <div class="text-center mb-12">
        <h2 class="text-4xl font-['Grand_Hotel'] text-[#FB4F7B] mb-3 font-medium">
            Your Journey Starts Here
        </h2>
    </div>

    <div class="space-y-6 w-full">
        <div
            class="bg-white text-gray-800 px-6 py-5 rounded-xl shadow-xl flex items-start space-x-6 border border-gray-200">
            <div
                class="w-16 h-16 bg-[linear-gradient(180deg,#EC003F_0%,#C70036_100%)] text-white flex-shrink-0 flex items-center justify-center rounded-full">
                <i class="ri-user-add-line text-2xl"></i>
            </div>
            <div>
                <h3 class="font-bold mb-1">Sign Up For Free</h3>
                <p class="text-gray-600">
                    Create your account in seconds and start your journey to finding
                    love. It's completely free to join.
                </p>
            </div>
        </div>

        <div class="bg-white text-gray-800 p-6 rounded-xl shadow-xl flex items-start space-x-6 border border-gray-200">
            <div
                class="w-16 h-16 bg-[linear-gradient(180deg,#EC003F_0%,#C70036_100%)] text-white flex-shrink-0 flex items-center justify-center rounded-full">
                <i class="ri-sparkling-line text-2xl"></i>
            </div>
            <div>
                <h3 class="font-bold mb-1">Get Matches</h3>
                <p class="text-gray-600">
                    Our smart matching algorithm will connect you with compatible
                    singles who share your interests and values.
                </p>
            </div>
        </div>

        <div class="bg-white text-gray-800 p-6 rounded-xl shadow-xl flex items-start space-x-6 border border-gray-200">
            <div
                class="w-16 h-16 bg-[linear-gradient(180deg,#EC003F_0%,#C70036_100%)] text-white flex-shrink-0 flex items-center justify-center rounded-full bg-custom-pink/10 text-custom-pink">
                <i class="ri-heart-line text-2xl"></i>
            </div>
            <div>
                <h3 class="font-bold mb-1">Start Dating</h3>
                <p class="text-gray-600">
                    Connect with your matches, start conversations, and begin your
                    journey to finding meaningful relationships.
                </p>
            </div>
        </div>

        <div class="bg-white text-gray-800 p-6 rounded-xl shadow-xl flex items-start space-x-6 border border-gray-200">
            <div
                class="w-16 h-16 bg-[linear-gradient(180deg,#EC003F_0%,#C70036_100%)] text-white flex-shrink-0 flex items-center justify-center rounded-full bg-custom-pink/10 text-custom-pink">
                <i class="ri-checkbox-circle-line text-2xl"></i>
            </div>
            <div>
                <h3 class="font-bold mb-1">Find Love</h3>
                <p class="text-gray-600">
                    Build lasting connections and discover the relationship you've
                    been searching for with someone special.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Fifth section -->
<section class="w-full max-w-6xl mx-auto px-6 py-12 text-center">
    <h2 class="text-4xl font-['Grand_Hotel'] text-[#FB4F7B] mb-6 font-medium">
        It all starts with a Date
    </h2>

    <p class="text-lg dark max-w-4xl mx-auto mb-10">
        You find us, finally, and you are already in love. More than 5,000,000
        around the world already shared the same experience and uses our system.
        Joining us today just got easier!
    </p>

    <div class="flex flex-col md:flex-row md:gap-5 gap-4 justify-center items-center mb-20">
        <button
            class="px-8 py-3 text-lg font-semibold bg-[linear-gradient(180deg,#EC003F_0%,#C70036_100%)] hover:bg-[#D94269] text-white rounded-lg transition duration-300 shadow-xl">
            Join Us FREE
        </button>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
        <div class="flex flex-col items-center">
            <div class="w-16 h-16 flex items-center justify-center rounded-full mb-4">
                <img src="./assets/Container (1).png" width="50" alt="" />
            </div>
            <p class="md:text-3xl text-xl font-bold mb-1 text-[#FB4F7B]">5 MILLION</p>
            <p class="text-sm font-medium text-gray-500">Users in total</p>
        </div>

        <div class="flex flex-col items-center">
            <div class="w-16 h-16 flex items-center justify-center mb-4">
                <i class="ri-group-line text-5xl text-[#FB4F7B]"></i>
            </div>
            <p class="text-3xl font-bold mb-1 text-[#FB4F7B]">947</p>
            <p class="text-sm font-medium text-gray-500">Verified online</p>
        </div>

        <div class="flex flex-col items-center">
            <div
                class="w-16 h-16 flex items-center justify-center rounded-full bg-[linear-gradient(180deg,#EC003F_0%,#C70036_100%)] mb-4">
                <i class="ri-women-line text-3xl text-black"></i>
            </div>
            <p class="text-3xl font-bold mb-1 text-[#FB4F7B]">530</p>
            <p class="text-sm font-medium text-gray-500">Female users</p>
        </div>

        <div class="flex flex-col items-center">
            <div class="w-16 h-16 flex items-center justify-center rounded-full bg-blue-500 mb-4">
                <i class="ri-men-line text-3xl text-white"></i>
            </div>
            <p class="text-3xl font-bold mb-1 text-blue-500">417</p>
            <p class="text-sm font-medium text-gray-500">Male users</p>
        </div>
    </div>
</section>

<!-- SECTION: Grid Layout -->
<section class="w-full max-w-6xl mx-auto px-6 py-12">
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <div
            class="relative col-span-2 row-span-2 rounded-lg overflow-hidden bg-gray-700 min-h-[300px] sm:min-h-[400px] md:min-h-[400px] lg:min-h-[580px]">
            <img src="./assets/grid1.jpg" alt="Swingers Partner Program"
                class="w-full h-full object-cover absolute inset-0" />

            <div
                class="absolute inset-x-0 bottom-0 p-4 sm:p-6 bg-gradient-to-t from-black/80 to-transparent h-full flex flex-col justify-end text-left">
                <h3 class="text-xl text-white sm:text-3xl font-bold mb-2">
                    Swingers Partner Program
                </h3>
                <p class="text-xs sm:text-sm dark">
                    It's now easier than ever for you to make money... and grow your
                    business with Swingers - all in one.
                </p>
            </div>
        </div>

        <div class="relative col-span-1 rounded-lg overflow-hidden bg-gray-700 min-h-[150px]">
            <img src="./assets/grid2.jpg" alt="Dealing With Love" class="w-full h-full object-cover absolute inset-0" />
            <div class="absolute inset-x-0 bottom-0 p-4 bg-gradient-to-t from-black/80 to-transparent">
                <h3 class="text-sm sm:text-lg font-bold text-white">
                    Dealing With Love
                </h3>
                <p class="text-[10px] sm:text-xs text-gray-300">
                    It's now easier than ever for you to make money...
                </p>
            </div>
        </div>

        <div class="relative col-span-1 rounded-lg overflow-hidden bg-gray-700 min-h-[150px]">
            <img src="./assets/grid3.jpg" alt="Dealing With Love" class="w-full h-full object-cover absolute inset-0" />
            <div class="absolute inset-x-0 bottom-0 p-4 bg-gradient-to-t from-black/80 to-transparent">
                <h3 class="text-sm sm:text-lg font-bold text-white">
                    Dealing With Love
                </h3>
                <p class="text-[10px] sm:text-xs text-gray-300">
                    It's now easier than ever for you to make money...
                </p>
            </div>
        </div>

        <div class="relative col-span-1 rounded-lg overflow-hidden bg-gray-700 min-h-[150px]">
            <img src="./assets/grid4.jpg" alt="Dealing with loneliness"
                class="w-full h-full object-cover absolute inset-0" />
            <div class="absolute inset-x-0 bottom-0 p-4 bg-gradient-to-t from-black/80 to-transparent">
                <h3 class="text-sm sm:text-lg font-bold text-white">
                    Dealing With loneliness
                </h3>
                <p class="text-[10px] sm:text-xs text-gray-300">
                    It's now easier than ever for you to make money...
                </p>
            </div>
        </div>

        <div class="relative col-span-1 rounded-lg overflow-hidden bg-gray-700 min-h-[150px]">
            <img src="./assets/grid5.jpg" alt="Dealing with loneliness"
                class="w-full h-full object-cover absolute inset-0" />
            <div class="absolute inset-x-0 bottom-0 p-4 bg-gradient-to-t from-black/80 to-transparent">
                <h3 class="text-sm sm:text-lg font-bold text-white">
                    Dealing With loneliness
                </h3>
                <p class="text-[10px] sm:text-xs text-gray-300">
                    It's now easier than ever for you to make money...
                </p>
            </div>
        </div>
    </div>

    <div class="mt-5 grid grid-cols-1 gap-5 
                md:grid-cols-4 md:grid-rows-2">

        <!-- Big Left Image -->
        <div class=" [background:linear-gradient(180deg,rgba(0,0,0,0)_0%,rgba(0,0,0,0.356901)_46.35%,rgba(0,0,0,0.77)_100%)] min-h-[250px] md:min-h-[580px] rounded-lg relative
                    md:col-start-1 md:col-end-2 md:row-start-1 md:row-end-3">
            <img src="./assets/grid6.jpg" alt="Dealing with loneliness"
                class="absolute rounded-lg inset-0 w-full h-full object-cover" />

            <div class="absolute inset-x-0 bottom-0 p-4 bg-gradient-to-t from-black/80 to-transparent">
                <h3 class="text-sm sm:text-lg font-bold text-white">
                    Dealing With loneliness
                </h3>
                <p class="text-[10px] sm:text-xs text-gray-300">
                    It's now easier than ever for you to make money...
                </p>
            </div>
        </div>

        <!-- Top Middle -->
        <div class="min-h-[200px] rounded-lg relative md:col-start-2 md:col-end-4 md:row-start-1 md:row-end-2">
            <img src="./assets/card-image.jpg" alt="Dealing with loneliness"
                class="absolute rounded-lg inset-0 w-full h-full object-cover" />

            <div class="absolute inset-x-0 bottom-0 p-4 bg-gradient-to-t from-black/80 to-transparent">
                <h3 class="text-sm sm:text-lg font-bold text-white">
                    Swingers Partner Program
                </h3>
                <p class="text-[10px] sm:text-xs text-gray-300">
                    It's now easier than ever for you to make money... and grow your
                    business with Swingers - all in one.
                </p>
            </div>
        </div>

        <!-- Top Right -->
        <div class="relative rounded-lg min-h-[200px] md:col-start-4 md:col-end-5 md:row-start-1 md:row-end-2">
            <img src="./assets/grid8.jpg" alt="Dealing with loneliness"
                class="absolute rounded-lg inset-0 w-full h-full object-cover" />

            <div class="absolute inset-x-0 bottom-0 p-4 bg-gradient-to-t from-black/80 to-transparent">
                <h3 class="text-sm sm:text-lg font-bold text-white">
                    Super Sexperience..
                </h3>
                <p class="text-[10px] sm:text-xs text-gray-300">
                    It’s now easier than ever for you to make money…
                </p>
            </div>
        </div>

        <!-- Bottom Middle Left -->
        <div class="relative rounded-lg min-h-[200px] md:col-start-2 md:col-end-3  md:row-start-2 md:row-end-3">
            <img src="./assets/grid9.jpg" alt="Dealing with loneliness"
                class="absolute rounded-lg inset-0 w-full h-full object-cover" />

            <div class="absolute inset-x-0 bottom-0 p-4 bg-gradient-to-t from-black/80 to-transparent">
                <h3 class="text-sm sm:text-lg font-bold text-white">
                    Travel Journey
                </h3>
                <p class="text-[10px] sm:text-xs text-gray-300">
                    It's now easier than ever for you to make money...
                </p>
            </div>
        </div>

        <!-- Bottom Middle Right -->
        <div class="relative rounded-lg min-h-[200px] md:col-start-3 md:col-end-4 md:row-start-2 md:row-end-3">
            <img src="./assets/grid11.jpg" alt="Dealing with loneliness"
                class="absolute rounded-lg inset-0 w-full h-full object-cover" />

            <div class="absolute inset-x-0 bottom-0 p-4 bg-gradient-to-t from-black/80 to-transparent">
                <h3 class="text-sm sm:text-lg font-bold text-white">
                    Travel Journey
                </h3>
                <p class="text-[10px] sm:text-xs text-gray-300">
                    It's now easier than ever for you to make money...
                </p>
            </div>
        </div>

        <!-- Bottom Right -->
        <div class="relative rounded-lg min-h-[200px] md:col-start-4 md:col-end-5 md:row-start-2 md:row-end-3">
            <img src="./assets/grid10.jpg" alt="Dealing with loneliness"
                class="absolute rounded-lg inset-0 w-full h-full object-cover" />

            <div class="absolute inset-x-0 bottom-0 p-4 bg-gradient-to-t from-black/80 to-transparent">
                <h3 class="text-sm sm:text-lg font-bold text-white">
                    Super Fitness
                </h3>
                <p class="text-[10px] sm:text-xs text-gray-300">
                    It's now easier than ever for you to make money...
                </p>
            </div>
        </div>

    </div>


    <div class="text-center mt-12">
        <button
            class="px-8 py-3 text-lg font-semibold bg-[#FB4F7B] hover:bg-[#D94269] text-white rounded-lg transition duration-300 shadow-xl">
            Start Your Love Story Today
        </button>
        <p class="text-sm dark mt-4">
            Over 2,000 success stories this month
        </p>
    </div>
</section>


@push('scripts')
@php
    $googleMapsApiKey = config('services.google_maps.api_key');
@endphp
@if($googleMapsApiKey)
<script>
// Initialize Google Maps Places Autocomplete for location input on home page
function initHomeLocationAutocomplete() {
    const locationInput = document.getElementById('home_filter_location');
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
            initHomeLocationAutocomplete();
        }
    } else {
        const script = document.createElement('script');
        script.src = 'https://maps.googleapis.com/maps/api/js?key={{ $googleMapsApiKey }}&loading=async&libraries=places&callback=initHomeLocationAutocompleteCallback';
        script.async = true;
        script.defer = true;
        document.head.appendChild(script);
    }
})();

// Callback function for when Google Maps loads
window.initHomeLocationAutocompleteCallback = function() {
    if (typeof google !== 'undefined' && google.maps && google.maps.places) {
        initHomeLocationAutocomplete();
    }
};

// Handle form submission - if not logged in, store search params and redirect to login
@if(!auth()->check())
document.getElementById('homeSearchForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const params = new URLSearchParams();
    
    // Store all form values
    for (const [key, value] of formData.entries()) {
        if (value) {
            params.append(key, value);
        }
    }
    
    // Redirect to login with search params
    window.location.href = '{{ route("login") }}?redirect=' + encodeURIComponent('{{ route("dashboard.members") }}?' + params.toString());
});
@endif
</script>
@else
<script>
console.warn('Google Maps API key is not configured. Please add GOOGLE_MAPS_API_KEY to your .env file.');
</script>
@endif
@endpush

@endsection

