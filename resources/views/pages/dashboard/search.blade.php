@extends('layouts.dashboard')

@section('title', 'Search - ' . config('app.name'))

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Search Page Content -->
    <div class="max-w-4xl mx-auto py-8 px-4">
        <!-- Regular Search Section (shown by default) -->
        <div id="regularSearchSection">
        <!-- Title -->
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">Search</h1>

        <!-- Search Form -->
        <form method="GET" action="{{ route('dashboard.search') }}" id="searchForm">
            <!-- Search Input -->
            <div class="mb-8">
                <input 
                    type="text" 
                    name="query"
                    id="searchQuery"
                    value="{{ request('query') }}"
                    placeholder="Profile Name / Keyword" 
                    class="w-full text-base bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-2xl px-6 py-4 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                />
            </div>

            <!-- Search Categories -->
            <div class="mb-8">
                <p class="text-blue-600 dark:text-blue-400 text-lg font-medium mb-4 text-center">in :</p>
                
                <div class="flex flex-col gap-3">
                    <!-- All -->
                    <button 
                        type="button"
                        data-category="all"
                        class="category-btn w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-2xl transition-colors text-left"
                    >
                        All
                    </button>

                    <!-- Login name -->
                    <button 
                        type="button"
                        data-category="login_name"
                        class="category-btn w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-2xl transition-colors text-left"
                    >
                        Login name
                    </button>

                    <!-- Profile Text -->
                    <button 
                        type="button"
                        data-category="profile_text"
                        class="category-btn w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-2xl transition-colors text-left"
                    >
                        Profile Text
                    </button>

                    <!-- Parties & Events -->
                    <button 
                        type="button"
                        data-category="parties_events"
                        class="category-btn w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-2xl transition-colors text-left"
                    >
                        Parties & Events
                    </button>

                    <!-- Business -->
                    <button 
                        type="button"
                        data-category="business"
                        class="category-btn w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-2xl transition-colors text-left"
                    >
                        Business
                    </button>

                    <!-- Groups/Communities -->
                    <button 
                        type="button"
                        data-category="groups_communities"
                        class="category-btn w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-2xl transition-colors text-left"
                    >
                        Groups/Communities
                    </button>

                    <!-- Member Service -->
                    <button 
                        type="button"
                        data-category="member_service"
                        class="category-btn w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-2xl transition-colors text-left"
                    >
                        Member Service
                    </button>

                    <!-- Forum -->
                    <button 
                        type="button"
                        data-category="forum"
                        class="category-btn w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-2xl transition-colors text-left"
                    >
                        Forum
                    </button>
                </div>
            </div>

            <!-- Hidden input for category -->
            <input type="hidden" name="category" id="selectedCategory" value="{{ request('category', 'all') }}">

            <!-- Advanced Member Search Button -->
            <div class="mt-8">
                <button 
                        type="button"
                        id="toggleAdvancedSearchBtn"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-2xl transition-colors"
                >
                    Advanced Member Search
                </button>
            </div>
        </form>
        </div>

        <!-- Advanced Member Search Section (hidden by default) -->
        <div id="advancedSearchSection" class="hidden">
            <!-- Title -->
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-6">Member Search</h1>
            
            <!-- Description -->
            <p class="text-gray-600 dark:text-gray-400 mb-8 leading-relaxed">
                Select who you are looking for and refine your search parameters in the sections below. Next to "Search Mode", you can either select "and" to narrow down your search results to show profiles that have all the features you select below; or you can select "or" to show profiles that have minimum one of the features you select below.
            </p>

            <!-- Advanced Search Form -->
            <form method="GET" action="{{ route('dashboard.members') }}" id="advancedSearchForm" class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                <!-- Login Name Input -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Login name</label>
                    <input 
                        type="text" 
                        name="login_name"
                        id="login_name"
                        value="{{ request('login_name') }}"
                        placeholder="Login name" 
                        class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl px-4 py-3 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    />
                </div>

                <!-- Accordion Sections -->
                <div class="space-y-3 mb-8">
                    <!-- STATUS Accordion -->
                    <div class="accordion-item bg-gray-50 dark:bg-gray-700 rounded-xl border border-gray-200 dark:border-gray-600 overflow-hidden">
                        <button 
                            type="button"
                            class="accordion-header w-full flex items-center justify-between px-4 py-3 text-left hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors"
                            onclick="toggleAccordion(this)"
                        >
                            <h2 class="text-sm font-semibold uppercase text-gray-500 dark:text-gray-400">STATUS</h2>
                            <i class="ri-arrow-up-s-line accordion-icon text-blue-500 text-lg transition-transform duration-300"></i>
                        </button>
                        <div class="accordion-content hidden px-4 pb-4">
                            <div class="space-y-3 pt-2">
                                <!-- Online -->
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input 
                                        type="radio" 
                                        name="status" 
                                        value="online"
                                        {{ request('status') == 'online' ? 'checked' : '' }}
                                        class="w-5 h-5 text-purple-600 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-purple-500 focus:ring-2"
                                    />
                                    <div class="flex items-center gap-3 flex-1">
                                        <div class="w-6 h-6 flex items-center justify-center flex-shrink-0">
                                            <i class="ri-wifi-line text-gray-500 dark:text-gray-400 text-lg"></i>
                                        </div>
                                        <span class="text-gray-900 dark:text-white font-medium group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">Online</span>
                                    </div>
                                </label>

                                <!-- New Members -->
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input 
                                        type="radio" 
                                        name="status" 
                                        value="new_members"
                                        {{ request('status') == 'new_members' ? 'checked' : '' }}
                                        class="w-5 h-5 text-purple-600 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-purple-500 focus:ring-2"
                                    />
                                    <div class="flex items-center gap-3 flex-1">
                                        <div class="w-6 h-6 flex items-center justify-center flex-shrink-0">
                                            <i class="ri-add-circle-line text-gray-500 dark:text-gray-400 text-lg"></i>
                                        </div>
                                        <span class="text-gray-900 dark:text-white font-medium group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">New Members</span>
                                    </div>
                                </label>

                                <!-- Has a Birthday -->
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input 
                                        type="radio" 
                                        name="status" 
                                        value="birthday"
                                        {{ request('status') == 'birthday' ? 'checked' : '' }}
                                        class="w-5 h-5 text-purple-600 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-purple-500 focus:ring-2"
                                    />
                                    <div class="flex items-center gap-3 flex-1">
                                        <div class="w-6 h-6 flex items-center justify-center flex-shrink-0">
                                            <i class="ri-cake-line text-gray-500 dark:text-gray-400 text-lg"></i>
                                        </div>
                                        <span class="text-gray-900 dark:text-white font-medium group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">Has a Birthday</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- WHAT YOU ARE LOOKING FOR Accordion -->
                    <div class="accordion-item bg-gray-50 dark:bg-gray-700 rounded-xl border border-gray-200 dark:border-gray-600 overflow-hidden">
                        <button 
                            type="button"
                            class="accordion-header w-full flex items-center justify-between px-4 py-3 text-left hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors"
                            onclick="toggleAccordion(this)"
                        >
                            <h2 class="text-sm font-semibold uppercase text-gray-500 dark:text-gray-400">WHAT YOU ARE LOOKING FOR</h2>
                            <i class="ri-arrow-up-s-line accordion-icon text-blue-500 text-lg transition-transform duration-300"></i>
                        </button>
                        <div class="accordion-content hidden px-4 pb-4">
                            <div class="space-y-3 pt-2">
                        <!-- Couple Female/Male -->
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input 
                                type="radio" 
                                name="looking_for" 
                                value="couple"
                                {{ request('looking_for') == 'couple' ? 'checked' : '' }}
                                class="w-5 h-5 text-purple-600 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-purple-500 focus:ring-2"
                            />
                            <div class="flex items-center gap-3 flex-1">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-pink-400 to-blue-400 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                    </svg>
                                </div>
                                <span class="text-gray-900 dark:text-white font-medium group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">Couple Female/Male</span>
                            </div>
                        </label>

                        <!-- Female -->
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input 
                                type="radio" 
                                name="looking_for" 
                                value="female"
                                {{ request('looking_for') == 'female' ? 'checked' : '' }}
                                class="w-5 h-5 text-purple-600 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-purple-500 focus:ring-2"
                            />
                            <div class="flex items-center gap-3 flex-1">
                                <div class="w-8 h-8 rounded-full bg-pink-500 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <span class="text-gray-900 dark:text-white font-medium group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">Female</span>
                            </div>
                        </label>

                        <!-- Male -->
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input 
                                type="radio" 
                                name="looking_for" 
                                value="male"
                                {{ request('looking_for') == 'male' ? 'checked' : '' }}
                                class="w-5 h-5 text-purple-600 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-purple-500 focus:ring-2"
                            />
                            <div class="flex items-center gap-3 flex-1">
                                <div class="w-8 h-8 rounded-full bg-blue-400 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <span class="text-gray-900 dark:text-white font-medium group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">Male</span>
                            </div>
                        </label>

                        <!-- Transgender -->
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input 
                                type="radio" 
                                name="looking_for" 
                                value="transgender"
                                {{ request('looking_for') == 'transgender' ? 'checked' : '' }}
                                class="w-5 h-5 text-purple-600 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-purple-500 focus:ring-2"
                            />
                            <div class="flex items-center gap-3 flex-1">
                                <div class="w-8 h-8 rounded-full bg-purple-500 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <span class="text-gray-900 dark:text-white font-medium group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">Transgender</span>
                            </div>
                        </label>

                        <!-- Businesses -->
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input 
                                type="radio" 
                                name="looking_for" 
                                value="business"
                                {{ request('looking_for') == 'business' ? 'checked' : '' }}
                                class="w-5 h-5 text-purple-600 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-purple-500 focus:ring-2"
                            />
                            <div class="flex items-center gap-3 flex-1">
                                <div class="w-8 h-8 rounded-full bg-yellow-500 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <span class="text-gray-900 dark:text-white font-medium group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">Businesses</span>
                            </div>
                        </label>

                        <!-- Looking for me / us -->
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input 
                                type="radio" 
                                name="looking_for" 
                                value="looking_for_me"
                                {{ request('looking_for') == 'looking_for_me' ? 'checked' : '' }}
                                class="w-5 h-5 text-purple-600 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-purple-500 focus:ring-2"
                            />
                            <div class="flex items-center gap-3 flex-1">
                                <span class="text-gray-900 dark:text-white font-medium group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">Looking for me / us</span>
                            </div>
                        </label>
                            </div>
                        </div>
                    </div>

                    <!-- VISUALS Accordion -->
                    <div class="accordion-item bg-gray-50 dark:bg-gray-700 rounded-xl border border-gray-200 dark:border-gray-600 overflow-hidden">
                        <button 
                            type="button"
                            class="accordion-header w-full flex items-center justify-between px-4 py-3 text-left hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors"
                            onclick="toggleAccordion(this)"
                        >
                            <h2 class="text-sm font-semibold uppercase text-gray-500 dark:text-gray-400">VISUALS</h2>
                            <i class="ri-arrow-up-s-line accordion-icon text-blue-500 text-lg transition-transform duration-300"></i>
                        </button>
                        <div class="accordion-content hidden px-4 pb-4">
                            <div class="space-y-3 pt-2">
                                <!-- Profile Picture -->
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input 
                                        type="radio" 
                                        name="visuals" 
                                        value="profile_picture"
                                        {{ request('visuals') == 'profile_picture' ? 'checked' : '' }}
                                        class="w-5 h-5 text-purple-600 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-purple-500 focus:ring-2"
                                    />
                                    <div class="flex items-center gap-3 flex-1">
                                        <div class="w-6 h-6 flex items-center justify-center flex-shrink-0">
                                            <i class="ri-camera-line text-gray-500 dark:text-gray-400 text-lg"></i>
                                        </div>
                                        <span class="text-gray-900 dark:text-white font-medium group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">Profile Picture</span>
                                    </div>
                                </label>

                                <!-- Video -->
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input 
                                        type="radio" 
                                        name="visuals" 
                                        value="video"
                                        {{ request('visuals') == 'video' ? 'checked' : '' }}
                                        class="w-5 h-5 text-purple-600 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-purple-500 focus:ring-2"
                                    />
                                    <div class="flex items-center gap-3 flex-1">
                                        <div class="w-6 h-6 flex items-center justify-center flex-shrink-0">
                                            <i class="ri-video-line text-gray-500 dark:text-gray-400 text-lg"></i>
                                        </div>
                                        <span class="text-gray-900 dark:text-white font-medium group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">Video</span>
                                    </div>
                                </label>

                                <!-- Album -->
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input 
                                        type="radio" 
                                        name="visuals" 
                                        value="album"
                                        {{ request('visuals') == 'album' ? 'checked' : '' }}
                                        class="w-5 h-5 text-purple-600 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-purple-500 focus:ring-2"
                                    />
                                    <div class="flex items-center gap-3 flex-1">
                                        <div class="w-6 h-6 flex items-center justify-center flex-shrink-0">
                                            <i class="ri-image-line text-gray-500 dark:text-gray-400 text-lg"></i>
                                        </div>
                                        <span class="text-gray-900 dark:text-white font-medium group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">Album</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- LANGUAGES Accordion -->
                    <div class="accordion-item bg-gray-50 dark:bg-gray-700 rounded-xl border border-gray-200 dark:border-gray-600 overflow-hidden">
                        <button 
                            type="button"
                            class="accordion-header w-full flex items-center justify-between px-4 py-3 text-left hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors"
                            onclick="toggleAccordion(this)"
                        >
                            <h2 class="text-sm font-semibold uppercase text-gray-500 dark:text-gray-400">LANGUAGES</h2>
                            <i class="ri-arrow-up-s-line accordion-icon text-blue-500 text-lg transition-transform duration-300"></i>
                        </button>
                        <div class="accordion-content hidden px-4 pb-4">
                            <!-- Search Mode -->
                            <div class="mb-4 pt-2">
                                <div class="flex items-center gap-4">
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Search Mode</label>
                                    <div class="flex items-center gap-4">
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input 
                                                type="radio" 
                                                name="search_mode" 
                                                value="and"
                                                {{ request('search_mode', 'and') == 'and' ? 'checked' : '' }}
                                                class="w-4 h-4 text-green-600 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-green-500 focus:ring-2"
                                            />
                                            <span class="text-gray-900 dark:text-white text-sm">and</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input 
                                                type="radio" 
                                                name="search_mode" 
                                                value="or"
                                                {{ request('search_mode') == 'or' ? 'checked' : '' }}
                                                class="w-4 h-4 text-green-600 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-green-500 focus:ring-2"
                                            />
                                            <span class="text-gray-900 dark:text-white text-sm">or</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Language Options -->
                            <div class="space-y-3">
                                <!-- Any -->
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input 
                                        type="radio" 
                                        name="language" 
                                        value="any"
                                        {{ !request('language') || request('language') == 'any' ? 'checked' : '' }}
                                        class="w-5 h-5 text-green-600 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-green-500 focus:ring-2"
                                    />
                                    <span class="text-gray-900 dark:text-white font-medium group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">Any</span>
                                </label>

                                <!-- Español -->
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input 
                                        type="radio" 
                                        name="language" 
                                        value="es"
                                        {{ request('language') == 'es' ? 'checked' : '' }}
                                        class="w-5 h-5 text-green-600 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-green-500 focus:ring-2"
                                    />
                                    <span class="text-gray-900 dark:text-white font-medium group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">Español</span>
                                </label>

                                <!-- Nederlands -->
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input 
                                        type="radio" 
                                        name="language" 
                                        value="nl"
                                        {{ request('language') == 'nl' ? 'checked' : '' }}
                                        class="w-5 h-5 text-green-600 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-green-500 focus:ring-2"
                                    />
                                    <span class="text-gray-900 dark:text-white font-medium group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">Nederlands</span>
                                </label>

                                <!-- Italiano -->
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input 
                                        type="radio" 
                                        name="language" 
                                        value="it"
                                        {{ request('language') == 'it' ? 'checked' : '' }}
                                        class="w-5 h-5 text-green-600 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-green-500 focus:ring-2"
                                    />
                                    <span class="text-gray-900 dark:text-white font-medium group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">Italiano</span>
                                </label>

                                <!-- Deutsch -->
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input 
                                        type="radio" 
                                        name="language" 
                                        value="de"
                                        {{ request('language') == 'de' ? 'checked' : '' }}
                                        class="w-5 h-5 text-green-600 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-green-500 focus:ring-2"
                                    />
                                    <span class="text-gray-900 dark:text-white font-medium group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">Deutsch</span>
                                </label>

                                <!-- English -->
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input 
                                        type="radio" 
                                        name="language" 
                                        value="en"
                                        {{ request('language') == 'en' ? 'checked' : '' }}
                                        class="w-5 h-5 text-green-600 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-green-500 focus:ring-2"
                                    />
                                    <span class="text-gray-900 dark:text-white font-medium group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">English</span>
                                </label>

                                <!-- Français -->
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input 
                                        type="radio" 
                                        name="language" 
                                        value="fr"
                                        {{ request('language') == 'fr' ? 'checked' : '' }}
                                        class="w-5 h-5 text-green-600 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-green-500 focus:ring-2"
                                    />
                                    <span class="text-gray-900 dark:text-white font-medium group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">Français</span>
                                </label>

                                <!-- Português -->
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input 
                                        type="radio" 
                                        name="language" 
                                        value="pt"
                                        {{ request('language') == 'pt' ? 'checked' : '' }}
                                        class="w-5 h-5 text-green-600 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-green-500 focus:ring-2"
                                    />
                                    <span class="text-gray-900 dark:text-white font-medium group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">Português</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- LOCATION / DISTANCE Accordion -->
                    <div class="accordion-item bg-gray-50 dark:bg-gray-700 rounded-xl border border-gray-200 dark:border-gray-600 overflow-hidden">
                        <button 
                            type="button"
                            class="accordion-header w-full flex items-center justify-between px-4 py-3 text-left hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors"
                            onclick="toggleAccordion(this)"
                        >
                            <h2 class="text-sm font-semibold uppercase text-gray-500 dark:text-gray-400">LOCATION / DISTANCE</h2>
                            <i class="ri-arrow-up-s-line accordion-icon text-blue-500 text-lg transition-transform duration-300"></i>
                        </button>
                        <div class="accordion-content hidden px-4 pb-4">
                            <div class="pt-2 space-y-4">
                                <!-- Location Input -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Location</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="ri-map-pin-line text-gray-400 dark:text-gray-500"></i>
                                        </div>
                                        <input 
                                            type="text" 
                                            name="filter_location"
                                            id="filter_location_advanced"
                                            value="{{ request('filter_location') }}"
                                            placeholder="" 
                                            class="w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl pl-10 pr-4 py-3 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                        />
                                    </div>
                                </div>

                                <!-- Distance Slider -->
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Distance</label>
                                        <span id="distanceValue" class="text-sm font-medium text-gray-600 dark:text-gray-400">0 mi</span>
                                    </div>
                                    <div class="relative">
                                        <input 
                                            type="range" 
                                            name="distance_range"
                                            id="distanceRange"
                                            min="0"
                                            max="100"
                                            value="{{ request('distance_range', 0) }}"
                                            class="w-full h-2 bg-gray-200 dark:bg-gray-600 rounded-lg appearance-none cursor-pointer slider"
                                            oninput="updateDistanceValue(this.value)"
                                        />
                                        <style>
                                            .slider::-webkit-slider-thumb {
                                                appearance: none;
                                                width: 18px;
                                                height: 18px;
                                                border-radius: 50%;
                                                background: #3b82f6;
                                                cursor: pointer;
                                            }
                                            .slider::-moz-range-thumb {
                                                width: 18px;
                                                height: 18px;
                                                border-radius: 50%;
                                                background: #3b82f6;
                                                cursor: pointer;
                                                border: none;
                                            }
                                        </style>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SUBSCRIPTION Accordion -->
                    <div class="accordion-item bg-gray-50 dark:bg-gray-700 rounded-xl border border-gray-200 dark:border-gray-600 overflow-hidden">
                        <button 
                            type="button"
                            class="accordion-header w-full flex items-center justify-between px-4 py-3 text-left hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors"
                            onclick="toggleAccordion(this)"
                        >
                            <h2 class="text-sm font-semibold uppercase text-gray-500 dark:text-gray-400">SUBSCRIPTION</h2>
                            <i class="ri-arrow-up-s-line accordion-icon text-blue-500 text-lg transition-transform duration-300"></i>
                        </button>
                        <div class="accordion-content hidden px-4 pb-4">
                            <div class="space-y-3 pt-2">
                                <!-- Lifetime Member -->
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input 
                                        type="radio" 
                                        name="subscription" 
                                        value="lifetime"
                                        {{ request('subscription') == 'lifetime' ? 'checked' : '' }}
                                        class="w-5 h-5 text-purple-600 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-purple-500 focus:ring-2"
                                    />
                                    <div class="flex items-center gap-3 flex-1">
                                        <i class="ri-star-fill text-yellow-500 text-lg"></i>
                                        <span class="text-gray-900 dark:text-white font-medium group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">Lifetime Member</span>
                                    </div>
                                </label>

                                <!-- Full Member -->
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input 
                                        type="radio" 
                                        name="subscription" 
                                        value="full"
                                        {{ request('subscription') == 'full' ? 'checked' : '' }}
                                        class="w-5 h-5 text-purple-600 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-purple-500 focus:ring-2"
                                    />
                                    <span class="text-gray-900 dark:text-white font-medium group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">Full Member</span>
                                </label>

                                <!-- Trial Member -->
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input 
                                        type="radio" 
                                        name="subscription" 
                                        value="trial"
                                        {{ request('subscription') == 'trial' ? 'checked' : '' }}
                                        class="w-5 h-5 text-purple-600 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-purple-500 focus:ring-2"
                                    />
                                    <span class="text-gray-900 dark:text-white font-medium group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">Trial Member</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- SEXUALITY Accordion -->
                    <div class="accordion-item bg-gray-50 dark:bg-gray-700 rounded-xl border border-gray-200 dark:border-gray-600 overflow-hidden">
                        <button 
                            type="button"
                            class="accordion-header w-full flex items-center justify-between px-4 py-3 text-left hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors"
                            onclick="toggleAccordion(this)"
                        >
                            <h2 class="text-sm font-semibold uppercase text-gray-500 dark:text-gray-400">SEXUALITY</h2>
                            <i class="ri-arrow-up-s-line accordion-icon text-blue-500 text-lg transition-transform duration-300"></i>
                        </button>
                        <div class="accordion-content hidden px-4 pb-4">
                            <!-- Search Mode -->
                            <div class="mb-4 pt-2">
                                <div class="flex items-center gap-4">
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Search Mode</label>
                                    <div class="flex items-center gap-4">
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input 
                                                type="radio" 
                                                name="sexuality_search_mode" 
                                                value="and"
                                                {{ request('sexuality_search_mode', 'and') == 'and' ? 'checked' : '' }}
                                                class="w-4 h-4 text-green-600 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-green-500 focus:ring-2"
                                            />
                                            <span class="text-gray-900 dark:text-white text-sm">and</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input 
                                                type="radio" 
                                                name="sexuality_search_mode" 
                                                value="or"
                                                {{ request('sexuality_search_mode') == 'or' ? 'checked' : '' }}
                                                class="w-4 h-4 text-green-600 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-green-500 focus:ring-2"
                                            />
                                            <span class="text-gray-900 dark:text-white text-sm">or</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Two Column Layout -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- FEMALES Column -->
                                <div>
                                    <div class="flex items-center gap-2 mb-3">
                                        <i class="ri-women-line text-pink-500 text-xl"></i>
                                        <h3 class="text-sm font-semibold uppercase text-pink-500">FEMALES</h3>
                                    </div>
                                    <div class="space-y-3">
                                        <!-- Any -->
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input 
                                                type="radio" 
                                                name="female_sexuality" 
                                                value="any"
                                                {{ !request('female_sexuality') || request('female_sexuality') == 'any' ? 'checked' : '' }}
                                                class="w-5 h-5 text-green-600 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-green-500 focus:ring-2"
                                            />
                                            <span class="text-gray-900 dark:text-white font-medium group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">Any</span>
                                        </label>

                                        <!-- Straight -->
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input 
                                                type="radio" 
                                                name="female_sexuality" 
                                                value="straight"
                                                {{ request('female_sexuality') == 'straight' ? 'checked' : '' }}
                                                class="w-5 h-5 text-green-600 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-green-500 focus:ring-2"
                                            />
                                            <span class="text-gray-900 dark:text-white font-medium group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">Straight</span>
                                        </label>

                                        <!-- Bi-sexual -->
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input 
                                                type="radio" 
                                                name="female_sexuality" 
                                                value="bi_sexual"
                                                {{ request('female_sexuality') == 'bi_sexual' ? 'checked' : '' }}
                                                class="w-5 h-5 text-green-600 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-green-500 focus:ring-2"
                                            />
                                            <span class="text-gray-900 dark:text-white font-medium group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">Bi-sexual</span>
                                        </label>

                                        <!-- Bi-curious -->
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input 
                                                type="radio" 
                                                name="female_sexuality" 
                                                value="bi_curious"
                                                {{ request('female_sexuality') == 'bi_curious' ? 'checked' : '' }}
                                                class="w-5 h-5 text-green-600 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-green-500 focus:ring-2"
                                            />
                                            <span class="text-gray-900 dark:text-white font-medium group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">Bi-curious</span>
                                        </label>

                                        <!-- Lesbian -->
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input 
                                                type="radio" 
                                                name="female_sexuality" 
                                                value="lesbian"
                                                {{ request('female_sexuality') == 'lesbian' ? 'checked' : '' }}
                                                class="w-5 h-5 text-green-600 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-green-500 focus:ring-2"
                                            />
                                            <span class="text-gray-900 dark:text-white font-medium group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">Lesbian</span>
                                        </label>

                                        <!-- Pansexual -->
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input 
                                                type="radio" 
                                                name="female_sexuality" 
                                                value="pansexual"
                                                {{ request('female_sexuality') == 'pansexual' ? 'checked' : '' }}
                                                class="w-5 h-5 text-green-600 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-green-500 focus:ring-2"
                                            />
                                            <span class="text-gray-900 dark:text-white font-medium group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">Pansexual</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- MALES Column -->
                                <div>
                                    <div class="flex items-center gap-2 mb-3">
                                        <i class="ri-men-line text-blue-500 text-xl"></i>
                                        <h3 class="text-sm font-semibold uppercase text-blue-500">MALES</h3>
                                    </div>
                                    <div class="space-y-3">
                                        <!-- Any -->
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input 
                                                type="radio" 
                                                name="male_sexuality" 
                                                value="any"
                                                {{ !request('male_sexuality') || request('male_sexuality') == 'any' ? 'checked' : '' }}
                                                class="w-5 h-5 text-green-600 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-green-500 focus:ring-2"
                                            />
                                            <span class="text-gray-900 dark:text-white font-medium group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">Any</span>
                                        </label>

                                        <!-- Straight -->
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input 
                                                type="radio" 
                                                name="male_sexuality" 
                                                value="straight"
                                                {{ request('male_sexuality') == 'straight' ? 'checked' : '' }}
                                                class="w-5 h-5 text-green-600 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-green-500 focus:ring-2"
                                            />
                                            <span class="text-gray-900 dark:text-white font-medium group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">Straight</span>
                                        </label>

                                        <!-- Bi-sexual -->
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input 
                                                type="radio" 
                                                name="male_sexuality" 
                                                value="bi_sexual"
                                                {{ request('male_sexuality') == 'bi_sexual' ? 'checked' : '' }}
                                                class="w-5 h-5 text-green-600 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-green-500 focus:ring-2"
                                            />
                                            <span class="text-gray-900 dark:text-white font-medium group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">Bi-sexual</span>
                                        </label>

                                        <!-- Bi-curious -->
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input 
                                                type="radio" 
                                                name="male_sexuality" 
                                                value="bi_curious"
                                                {{ request('male_sexuality') == 'bi_curious' ? 'checked' : '' }}
                                                class="w-5 h-5 text-green-600 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-green-500 focus:ring-2"
                                            />
                                            <span class="text-gray-900 dark:text-white font-medium group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">Bi-curious</span>
                                        </label>

                                        <!-- Gay -->
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input 
                                                type="radio" 
                                                name="male_sexuality" 
                                                value="gay"
                                                {{ request('male_sexuality') == 'gay' ? 'checked' : '' }}
                                                class="w-5 h-5 text-green-600 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-green-500 focus:ring-2"
                                            />
                                            <span class="text-gray-900 dark:text-white font-medium group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">Gay</span>
                                        </label>

                                        <!-- Pansexual -->
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input 
                                                type="radio" 
                                                name="male_sexuality" 
                                                value="pansexual"
                                                {{ request('male_sexuality') == 'pansexual' ? 'checked' : '' }}
                                                class="w-5 h-5 text-green-600 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-green-500 focus:ring-2"
                                            />
                                            <span class="text-gray-900 dark:text-white font-medium group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">Pansexual</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- AGE Accordion -->
                    <div class="accordion-item bg-gray-50 dark:bg-gray-700 rounded-xl border border-gray-200 dark:border-gray-600 overflow-hidden">
                        <button 
                            type="button"
                            class="accordion-header w-full flex items-center justify-between px-4 py-3 text-left hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors"
                            onclick="toggleAccordion(this)"
                        >
                            <h2 class="text-sm font-semibold uppercase text-gray-500 dark:text-gray-400">AGE</h2>
                            <i class="ri-arrow-up-s-line accordion-icon text-blue-500 text-lg transition-transform duration-300"></i>
                        </button>
                        <div class="accordion-content hidden px-4 pb-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                                <!-- FEMALES Column -->
                                <div>
                                    <div class="flex items-center gap-2 mb-3">
                                        <i class="ri-women-line text-pink-500 text-xl"></i>
                                        <h3 class="text-sm font-semibold uppercase text-pink-500">FEMALES</h3>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="flex-1">
                                            <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">from</label>
                                            <select 
                                                name="female_age_from"
                                                class="w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-purple-500"
                                            >
                                                <option value="">Select</option>
                                                @for($i = 18; $i <= 100; $i++)
                                                    <option value="{{ $i }}" {{ request('female_age_from') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="flex-1">
                                            <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Until</label>
                                            <select 
                                                name="female_age_until"
                                                class="w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-purple-500"
                                            >
                                                <option value="">Select</option>
                                                @for($i = 18; $i <= 100; $i++)
                                                    <option value="{{ $i }}" {{ request('female_age_until') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- MALES Column -->
                                <div>
                                    <div class="flex items-center gap-2 mb-3">
                                        <i class="ri-men-line text-blue-500 text-xl"></i>
                                        <h3 class="text-sm font-semibold uppercase text-blue-500">MALES</h3>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="flex-1">
                                            <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">from</label>
                                            <select 
                                                name="male_age_from"
                                                class="w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-purple-500"
                                            >
                                                <option value="">Select</option>
                                                @for($i = 18; $i <= 100; $i++)
                                                    <option value="{{ $i }}" {{ request('male_age_from') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="flex-1">
                                            <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Until</label>
                                            <select 
                                                name="male_age_until"
                                                class="w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-purple-500"
                                            >
                                                <option value="">Select</option>
                                                @for($i = 18; $i <= 100; $i++)
                                                    <option value="{{ $i }}" {{ request('male_age_until') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SMOKING Accordion -->
                    <div class="accordion-item bg-gray-50 dark:bg-gray-700 rounded-xl border border-gray-200 dark:border-gray-600 overflow-hidden">
                        <button 
                            type="button"
                            class="accordion-header w-full flex items-center justify-between px-4 py-3 text-left hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors"
                            onclick="toggleAccordion(this)"
                        >
                            <h2 class="text-sm font-semibold uppercase text-gray-500 dark:text-gray-400">SMOKING</h2>
                            <i class="ri-arrow-up-s-line accordion-icon text-blue-500 text-lg transition-transform duration-300"></i>
                        </button>
                        <div class="accordion-content hidden px-4 pb-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                                <!-- FEMALES Column -->
                                <div>
                                    <div class="flex items-center gap-2 mb-3">
                                        <i class="ri-women-line text-pink-500 text-xl"></i>
                                        <h3 class="text-sm font-semibold uppercase text-pink-500">FEMALES</h3>
                                    </div>
                                    <div class="space-y-3">
                                        <!-- Not important -->
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input 
                                                type="radio" 
                                                name="female_smoking" 
                                                value="not_important"
                                                {{ !request('female_smoking') || request('female_smoking') == 'not_important' ? 'checked' : '' }}
                                                class="w-5 h-5 text-green-600 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-green-500 focus:ring-2"
                                            />
                                            <span class="text-gray-900 dark:text-white font-medium group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">Not important</span>
                                        </label>

                                        <!-- Yes -->
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input 
                                                type="radio" 
                                                name="female_smoking" 
                                                value="yes"
                                                {{ request('female_smoking') == 'yes' ? 'checked' : '' }}
                                                class="w-5 h-5 text-green-600 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-green-500 focus:ring-2"
                                            />
                                            <span class="text-gray-900 dark:text-white font-medium group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">Yes</span>
                                        </label>

                                        <!-- No -->
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input 
                                                type="radio" 
                                                name="female_smoking" 
                                                value="no"
                                                {{ request('female_smoking') == 'no' ? 'checked' : '' }}
                                                class="w-5 h-5 text-green-600 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-green-500 focus:ring-2"
                                            />
                                            <span class="text-gray-900 dark:text-white font-medium group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">No</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- MALES Column -->
                                <div>
                                    <div class="flex items-center gap-2 mb-3">
                                        <i class="ri-men-line text-blue-500 text-xl"></i>
                                        <h3 class="text-sm font-semibold uppercase text-blue-500">MALES</h3>
                                    </div>
                                    <div class="space-y-3">
                                        <!-- Not important -->
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input 
                                                type="radio" 
                                                name="male_smoking" 
                                                value="not_important"
                                                {{ !request('male_smoking') || request('male_smoking') == 'not_important' ? 'checked' : '' }}
                                                class="w-5 h-5 text-green-600 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-green-500 focus:ring-2"
                                            />
                                            <span class="text-gray-900 dark:text-white font-medium group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">Not important</span>
                                        </label>

                                        <!-- Yes -->
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input 
                                                type="radio" 
                                                name="male_smoking" 
                                                value="yes"
                                                {{ request('male_smoking') == 'yes' ? 'checked' : '' }}
                                                class="w-5 h-5 text-green-600 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-green-500 focus:ring-2"
                                            />
                                            <span class="text-gray-900 dark:text-white font-medium group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">Yes</span>
                                        </label>

                                        <!-- No -->
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input 
                                                type="radio" 
                                                name="male_smoking" 
                                                value="no"
                                                {{ request('male_smoking') == 'no' ? 'checked' : '' }}
                                                class="w-5 h-5 text-green-600 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-green-500 focus:ring-2"
                                            />
                                            <span class="text-gray-900 dark:text-white font-medium group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">No</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Back Button and Submit Button -->
                <div class="mt-8 flex gap-4">
                    <button 
                        type="button"
                        id="backToRegularSearchBtn"
                        class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-semibold py-3 px-6 rounded-xl transition-colors"
                    >
                        Back
                    </button>
                    <button 
                        type="submit"
                        class="flex-1 bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-6 rounded-xl transition-colors"
                    >
                        Search
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const regularSearchSection = document.getElementById('regularSearchSection');
    const advancedSearchSection = document.getElementById('advancedSearchSection');
    const toggleAdvancedSearchBtn = document.getElementById('toggleAdvancedSearchBtn');
    const backToRegularSearchBtn = document.getElementById('backToRegularSearchBtn');
    const categoryButtons = document.querySelectorAll('.category-btn');
    const selectedCategoryInput = document.getElementById('selectedCategory');
    const currentCategory = '{{ request("category", "all") }}';
    const advancedSearchForm = document.getElementById('advancedSearchForm');

    // Set initial active state for category buttons
    categoryButtons.forEach(btn => {
        const category = btn.getAttribute('data-category');
        if (category === currentCategory) {
            btn.classList.add('ring-2', 'ring-white', 'ring-offset-2', 'ring-offset-blue-600');
        }
    });

    // Handle category button clicks
    categoryButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const category = this.getAttribute('data-category');
            
            // Update hidden input
            selectedCategoryInput.value = category;
            
            // Update button styles
            categoryButtons.forEach(b => {
                b.classList.remove('ring-2', 'ring-white', 'ring-offset-2', 'ring-offset-blue-600');
            });
            this.classList.add('ring-2', 'ring-white', 'ring-offset-2', 'ring-offset-blue-600');
            
            // Submit form if query is filled and category is actionable
            const searchQuery = document.getElementById('searchQuery').value;
            if (searchQuery && searchQuery.trim() !== '') {
                const actionableCategories = ['all', 'login_name', 'profile_text'];
                if (actionableCategories.includes(category)) {
                    document.getElementById('searchForm').submit();
                }
            }
        });
    });

    // Toggle to Advanced Search
    toggleAdvancedSearchBtn.addEventListener('click', function() {
        regularSearchSection.classList.add('hidden');
        advancedSearchSection.classList.remove('hidden');
    });

    // Back to Regular Search
    backToRegularSearchBtn.addEventListener('click', function() {
        advancedSearchSection.classList.add('hidden');
        regularSearchSection.classList.remove('hidden');
    });

    // Handle advanced search form submission
    advancedSearchForm.addEventListener('submit', function(e) {
        // Map looking_for values to filter parameters
        const lookingFor = document.querySelector('input[name="looking_for"]:checked');
        if (lookingFor) {
            const value = lookingFor.value;
            
            // Create hidden inputs for filter parameters
            if (value === 'couple') {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'filter_couples';
                input.value = '1';
                advancedSearchForm.appendChild(input);
            } else if (value === 'female') {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'filter_female';
                input.value = '1';
                advancedSearchForm.appendChild(input);
            } else if (value === 'male') {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'filter_male';
                input.value = '1';
                advancedSearchForm.appendChild(input);
            } else if (value === 'transgender') {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'filter_transgender';
                input.value = '1';
                advancedSearchForm.appendChild(input);
            } else if (value === 'business') {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'filter_business';
                input.value = '1';
                advancedSearchForm.appendChild(input);
            } else if (value === 'looking_for_me') {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'filter_looking_for_me';
                input.value = '1';
                advancedSearchForm.appendChild(input);
            }
        }

        // Map status values
        const status = document.querySelector('input[name="status"]:checked');
        if (status) {
            const value = status.value;
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'filter_status';
            input.value = value;
            advancedSearchForm.appendChild(input);
        }

        // Map visuals values
        const visuals = document.querySelector('input[name="visuals"]:checked');
        if (visuals) {
            const value = visuals.value;
            if (value === 'profile_picture') {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'with_photos_only';
                input.value = '1';
                advancedSearchForm.appendChild(input);
            } else if (value === 'video') {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'with_videos_only';
                input.value = '1';
                advancedSearchForm.appendChild(input);
            } else if (value === 'album') {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'with_photos_only';
                input.value = '1';
                advancedSearchForm.appendChild(input);
            }
        }
        
        // Map language values
        const language = document.querySelector('input[name="language"]:checked');
        if (language && language.value !== 'any') {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'filter_language';
            input.value = language.value;
            advancedSearchForm.appendChild(input);
        }

        // Map search_mode
        const searchMode = document.querySelector('input[name="search_mode"]:checked');
        if (searchMode) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'search_mode';
            input.value = searchMode.value;
            advancedSearchForm.appendChild(input);
        }
        
        // Map location and distance
        const location = document.getElementById('filter_location_advanced').value;
        if (location) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'filter_location';
            input.value = location;
            advancedSearchForm.appendChild(input);
        }

        const distanceRange = document.getElementById('distanceRange').value;
        if (distanceRange && distanceRange > 0) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'distance';
            input.value = distanceRange;
            advancedSearchForm.appendChild(input);
        }

        // Map subscription values
        const subscription = document.querySelector('input[name="subscription"]:checked');
        if (subscription) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'filter_subscription';
            input.value = subscription.value;
            advancedSearchForm.appendChild(input);
        }

        // Map sexuality values
        const femaleSexuality = document.querySelector('input[name="female_sexuality"]:checked');
        if (femaleSexuality && femaleSexuality.value !== 'any') {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'filter_female_sexuality';
            input.value = femaleSexuality.value;
            advancedSearchForm.appendChild(input);
        }

        const maleSexuality = document.querySelector('input[name="male_sexuality"]:checked');
        if (maleSexuality && maleSexuality.value !== 'any') {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'filter_male_sexuality';
            input.value = maleSexuality.value;
            advancedSearchForm.appendChild(input);
        }

        const sexualitySearchMode = document.querySelector('input[name="sexuality_search_mode"]:checked');
        if (sexualitySearchMode) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'sexuality_search_mode';
            input.value = sexualitySearchMode.value;
            advancedSearchForm.appendChild(input);
        }

        // Map age values
        const femaleAgeFrom = document.querySelector('select[name="female_age_from"]').value;
        const femaleAgeUntil = document.querySelector('select[name="female_age_until"]').value;
        if (femaleAgeFrom) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'filter_female_age_from';
            input.value = femaleAgeFrom;
            advancedSearchForm.appendChild(input);
        }
        if (femaleAgeUntil) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'filter_female_age_until';
            input.value = femaleAgeUntil;
            advancedSearchForm.appendChild(input);
        }

        const maleAgeFrom = document.querySelector('select[name="male_age_from"]').value;
        const maleAgeUntil = document.querySelector('select[name="male_age_until"]').value;
        if (maleAgeFrom) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'filter_male_age_from';
            input.value = maleAgeFrom;
            advancedSearchForm.appendChild(input);
        }
        if (maleAgeUntil) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'filter_male_age_until';
            input.value = maleAgeUntil;
            advancedSearchForm.appendChild(input);
        }

        // Map smoking values
        const femaleSmoking = document.querySelector('input[name="female_smoking"]:checked');
        if (femaleSmoking && femaleSmoking.value !== 'not_important') {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'filter_female_smoking';
            input.value = femaleSmoking.value;
            advancedSearchForm.appendChild(input);
        }

        const maleSmoking = document.querySelector('input[name="male_smoking"]:checked');
        if (maleSmoking && maleSmoking.value !== 'not_important') {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'filter_male_smoking';
            input.value = maleSmoking.value;
            advancedSearchForm.appendChild(input);
        }
        
        // Map login_name to search parameter
        const loginName = document.getElementById('login_name').value;
        if (loginName) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'search';
            input.value = loginName;
            advancedSearchForm.appendChild(input);
        }
    });
});

// Update distance value display
function updateDistanceValue(value) {
    const distanceValue = document.getElementById('distanceValue');
    distanceValue.textContent = value + ' mi';
}

// Initialize distance value on page load
document.addEventListener('DOMContentLoaded', function() {
    const distanceRange = document.getElementById('distanceRange');
    if (distanceRange) {
        updateDistanceValue(distanceRange.value);
    }
});

// Accordion toggle function
function toggleAccordion(button) {
    const accordionItem = button.closest('.accordion-item');
    const content = accordionItem.querySelector('.accordion-content');
    const icon = button.querySelector('.accordion-icon');
    
    // Toggle content visibility
    content.classList.toggle('hidden');
    
    // Rotate icon
    if (content.classList.contains('hidden')) {
        icon.style.transform = 'rotate(0deg)';
    } else {
        icon.style.transform = 'rotate(180deg)';
    }
}
</script>

@php
    $googleMapsApiKey = config('services.google_maps.api_key');
@endphp
@if($googleMapsApiKey)
<script>
// Initialize Google Maps Places Autocomplete for advanced search location input
function initAdvancedLocationAutocomplete() {
    const locationInput = document.getElementById('filter_location_advanced');
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

// Load Google Maps API with Places library for advanced search
(function() {
    // Check if script already exists
    if (document.querySelector('script[src*="maps.googleapis.com"]')) {
        // Script already loaded, initialize directly
        if (typeof google !== 'undefined' && google.maps && google.maps.places) {
            initAdvancedLocationAutocomplete();
        }
    } else {
        const script = document.createElement('script');
        script.src = 'https://maps.googleapis.com/maps/api/js?key={{ $googleMapsApiKey }}&loading=async&libraries=places&callback=initAdvancedLocationAutocompleteCallback';
        script.async = true;
        script.defer = true;
        document.head.appendChild(script);
    }
})();

// Callback function for when Google Maps loads
window.initAdvancedLocationAutocompleteCallback = function() {
    if (typeof google !== 'undefined' && google.maps && google.maps.places) {
        initAdvancedLocationAutocomplete();
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

