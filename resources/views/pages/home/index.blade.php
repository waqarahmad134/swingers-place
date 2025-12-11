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

        <p class="md:mt-8 mt-5 text-gray-200 hover:text-white transition duration-300 cursor-pointer">
            More...
        </p>
    </div>
</section>

<!-- Second Find the Best Option for You -->
<!-- <section class="w-full max-w-[1180px] mx-auto flex flex-col items-center py-20 px-6">
    <div class="text-center mb-10">
        <h2 class="text-4xl font-['Grand_Hotel'] text-[#FB4F7B] mb-3 font-medium">
            Find the Best Option for You
        </h2>
        <p class="text-lg dark">
            Search through categories to discover exactly what you need
        </p>
    </div>

    <form class="space-y-8 w-full">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label for="search_input" class="flex items-center space-x-2 dark font-semibold mb-2">
                    <i class="ri-search-line"></i>
                    <span>Search</span>
                </label>
                <input type="text" id="search_input" placeholder="What are you looking for?"
                    class="px-4 py-3 w-full rounded-lg dark border border-[#FB4F7B]" />
            </div>

            <div>
                <label for="category_main" class="flex dark items-center space-x-2 font-semibold mb-2">
                    <i class="ri-filter-line"></i>
                    <span>Category</span>
                </label>
                <div class="relative">
                    <select id="category_main"
                        class="w-full px-4 py-3 rounded-lg bg-form-bg dark border border-[#FB4F7B] appearance-none">
                        <option>All Categories</option>
                        <option>Singles</option>
                        <option>Couples</option>
                        <option>Non-binary</option>
                    </select>
                    <i
                        class="ri-arrow-down-s-line absolute right-3 top-[40px] transform -translate-y-1/2 dark pointer-events-none text-xl"></i>
                </div>
            </div>

            <div>
                <label for="location_input" class="flex items-center space-x-2 dark font-semibold mb-2">
                    <i class="ri-map-pin-line"></i>
                    <span>Location</span>
                </label>
                <input type="text" id="location_input" placeholder="Enter location"
                    class="w-full px-4 py-3 rounded-lg bg-form-bg dark border border-[#FB4F7B]" />
            </div>
        </div>

        <div class="pt-4">
            <h3 class="text-xl font-bold dark">Advanced Filters</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div>
                <label for="sort_by" class="block dark font-semibold mb-2">
                    Sort By
                </label>
                <div class="relative">
                    <select id="sort_by"
                        class="w-full px-4 py-3 rounded-lg bg-form-bg dark border border-[#FB4F7B] appearance-none">
                        <option>Best Match</option>
                        <option>Newest</option>
                        <option>Nearest</option>
                    </select>
                    <i
                        class="ri-arrow-down-s-line absolute right-3 top-[40px] transform -translate-y-1/2 dark pointer-events-none text-xl"></i>
                </div>
            </div>

            <div>
                <label for="category_adv" class="block dark font-semibold mb-2">
                    Category
                </label>
                <div class="relative">
                    <select id="category_adv"
                        class="w-full px-4 py-3 rounded-lg bg-form-bg dark border border-[#FB4F7B] appearance-none">
                        <option>All Categories</option>
                        <option>Fetishes</option>
                        <option>Lifestyle</option>
                    </select>
                    <i
                        class="ri-arrow-down-s-line absolute right-3 top-[40px] transform -translate-y-1/2 dark pointer-events-none text-xl"></i>
                </div>
            </div>

            <div>
                <label for="distance" class="block dark font-semibold mb-2">
                    Distance
                </label>
                <div class="relative">
                    <select id="distance"
                        class="w-full px-4 py-3 rounded-lg bg-form-bg dark border border-[#FB4F7B] appearance-none">
                        <option>Any Distance</option>
                        <option>Within 10 miles</option>
                        <option>Within 50 miles</option>
                    </select>
                    <i
                        class="ri-arrow-down-s-line absolute right-3 top-[40px] transform -translate-y-1/2 dark pointer-events-none text-xl"></i>
                </div>
            </div>

            <div>
                <label for="age" class="block dark font-semibold mb-2">
                    Age
                </label>
                <div class="relative">
                    <select id="age"
                        class="w-full px-4 py-3 rounded-lg bg-form-bg dark border border-[#FB4F7B] appearance-none">
                        <option>Any Age</option>
                        <option>18-25</option>
                        <option>26-35</option>
                        <option>36+</option>
                    </select>
                    <i
                        class="ri-arrow-down-s-line absolute right-3 top-[40px] transform -translate-y-1/2 dark pointer-events-none text-xl"></i>
                </div>
            </div>
        </div>

        <div class="flex items-center space-x-6 pt-6">
            <button type="submit"
                class="flex items-center space-x-2 px-8 py-3 bg-[#FB4F7B] hover:bg-fuchsia-700 text-white rounded-lg font-semibold transition duration-300 shadow-xl">
                <i class="ri-search-line"></i>
                <span>Search</span>
            </button>

            <div class="flex items-center space-x-3">
                <label for="online_toggle" class="flex items-center cursor-pointer">
                    <div class="relative">
                        <input type="checkbox" id="online_toggle" class="sr-only peer" />
                        <div
                            class="block bg-[#FB4F7B] w-10 h-6 rounded-full transition duration-300 peer-checked:bg-gray-600">
                        </div>
                        <div
                            class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition transform duration-300 peer-checked:translate-x-4">
                        </div>
                    </div>
                </label>
                <label for="online_toggle" class="dark select-none">
                    Show only online users
                </label>
            </div>
        </div>
    </form>
</section> -->

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
        <button
            class="px-8 py-3 text-lg font-semibold text-blue-400 bg-white border border-blue-400 rounded-lg transition duration-300">
            Try for free
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


@endsection

