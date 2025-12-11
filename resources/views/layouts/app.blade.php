<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', config('app.name', 'swingers place'))</title>

    @php
        $defaultMetaDescription = config('app.meta_description', 'swingers place');
    @endphp

    <meta name="description" content="@yield('meta_description', $defaultMetaDescription)">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {{-- Favicon --}}
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.svg') }}">

    <!-- Remix Icons CDN -->
    <link
      href="https://cdn.jsdelivr.net/npm/remixicon@4.7.0/fonts/remixicon.css"
      rel="stylesheet"
    />

    <!-- Google Font: Arimo -->
    <link
      href="https://fonts.googleapis.com/css2?family=Arimo:wght@400;500;600;700&display=swap"
      rel="stylesheet"
    />

    <!-- Google Font: Leckeri One -->
    <link
      href="https://fonts.googleapis.com/css2?family=Leckerli+One&display=swap"
      rel="stylesheet"
    />

    <!-- Google Font: Grand Hotel -->
    <link
      href="https://fonts.googleapis.com/css2?family=Grand+Hotel&display=swap"
      rel="stylesheet"
    />

    <link rel="stylesheet" href="{{ asset('public/style.css') }}">

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* Ensure user dropdown hover works */
        .group:hover #user-dropdown-menu,
        #user-dropdown-menu:hover {
            opacity: 1 !important;
            visibility: visible !important;
        }
    </style>

    <script>
        // Initialize theme immediately to prevent flash of wrong theme
        // Default to 'light' mode for new visitors instead of system preference
        (function() {
            const storedTheme = localStorage.getItem('theme');
            const theme = storedTheme || 'light'; // Default to light mode, not system preference
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
        
        // Global auth flag
        window.isAuthenticated = @json(auth()->check());
    </script>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('head')
</head>
<body class="antialiased font-sans bg-light text-dark dark:bg-dark dark:text-light">
    <div class="min-h-screen">
        @include('partials.header')

        {{-- Profile Pending Approval Bar --}}
        @auth
            @if(!Auth::user()->is_active && !Auth::user()->is_admin)
                <div class="w-full bg-red-600 text-white py-3 px-4 shadow-md">
                    <div class="container mx-auto flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <i class="ri-alert-line text-xl"></i>
                            <p class="font-semibold">
                                Your profile is pending admin approval. You will be able to access all features once your profile is approved.
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        @endauth

        <main>
            @if (session('success'))
                <div class="container mx-auto px-4 py-4 sm:px-6 lg:px-8">
                    <div class="rounded-full border border-green-200 bg-green-50 px-6 py-3 text-sm font-semibold text-green-700 shadow-sm dark:border-green-800 dark:bg-green-900/40 dark:text-green-300">
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="container mx-auto px-4 py-4 sm:px-6 lg:px-8">
                    <div class="rounded-lg border border-red-200 bg-red-50 px-6 py-4 text-sm text-red-700 shadow-sm dark:border-red-800 dark:bg-red-900/40 dark:text-red-300">
                        <p class="font-semibold">Please fix the following:</p>
                        <ul class="mt-2 list-disc space-y-1 pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @hasSection('full-width')
                @yield('full-width')
            @else
                <div class="px-4 sm:px-6 lg:px-8 py-10">
                    @yield('content')
                </div>
            @endif
        </main>

        @include('partials.footer')
    </div>

    @include('components.toast')

    @stack('modals')
    
    <!-- Complete Profile Modal -->
    @auth
        @php
            $user = Auth::user();
            $profile = $user->profile;
            
            // Calculate Profile Completion Percentage (same logic as profile page)
            $languages = $profile && $profile->languages 
                ? (is_array($profile->languages) ? $profile->languages : json_decode($profile->languages, true) ?? [])
                : [];
            
            $preferences = $profile && $profile->preferences 
                ? (is_array($profile->preferences) ? $profile->preferences : json_decode($profile->preferences, true) ?? [])
                : [];
            
            $isCouple = $profile && $profile->category === 'couple';
            $coupleData = $profile && $profile->couple_data ? (is_array($profile->couple_data) ? $profile->couple_data : json_decode($profile->couple_data, true) ?? []) : [];
            
            $profileFields = [
                'category' => $profile && $profile->category ? 1 : 0,
                'preferences' => $profile && $profile->preferences && !empty($preferences) ? 1 : 0,
                'date_of_birth' => $profile && $profile->date_of_birth ? 1 : 0,
                'sexuality' => $profile && $profile->sexuality ? 1 : 0,
                'relationship_status' => $profile && $profile->relationship_status ? 1 : 0,
                'relationship_orientation' => $profile && $profile->relationship_orientation ? 1 : 0,
                'home_location' => $profile && $profile->home_location ? 1 : 0,
                'country' => $profile && $profile->country ? 1 : 0,
                'city' => $profile && $profile->city ? 1 : 0,
                'languages' => !empty($languages) ? 1 : 0,
                'bio' => $profile && $profile->bio ? 1 : 0,
                'weight' => $profile && $profile->weight ? 1 : 0,
                'height' => $profile && $profile->height ? 1 : 0,
                'body_type' => $profile && $profile->body_type ? 1 : 0,
                'eye_color' => $profile && $profile->eye_color ? 1 : 0,
                'hair_color' => $profile && $profile->hair_color ? 1 : 0,
                'profile_photo' => ($profile && $profile->profile_photo) || ($user->profile_image) ? 1 : 0,
            ];
            
            // For couple profiles, check couple_data fields
            if ($isCouple && !empty($coupleData)) {
                $profileFields['date_of_birth'] = (!empty($coupleData['date_of_birth_her']) || !empty($coupleData['date_of_birth_him'])) ? 1 : $profileFields['date_of_birth'];
                $profileFields['sexuality'] = (!empty($coupleData['sexuality_her']) || !empty($coupleData['sexuality_him'])) ? 1 : $profileFields['sexuality'];
            }
            
            $completedFields = array_sum($profileFields);
            $totalFields = count($profileFields);
            $profileCompletion = $totalFields > 0 ? round(($completedFields / $totalFields) * 100) : 0;
            
            // Show modal ONLY if profile completion is less than 80%
            // Modal will automatically stop showing once profile reaches 80% or more
            // Don't show on auth pages or edit profile page
            $excludedRoutes = ['account.profile.edit', 'login', 'register', 'password.request', 'password.reset'];
            $isExcludedRoute = false;
            foreach($excludedRoutes as $route) {
                if(request()->routeIs($route)) {
                    $isExcludedRoute = true;
                    break;
                }
            }
            
            // Modal shows when completion < 80%, hides automatically when >= 80%
            $showCompleteProfileModal = $profileCompletion < 80 && !$isExcludedRoute;
        @endphp
        
        @if($showCompleteProfileModal)
            <div id="complete-profile-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full transform transition-all">
                    <!-- Modal Header -->
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Complete Your Profile</h3>
                            <button id="close-complete-profile-modal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                                <i class="ri-close-line text-2xl"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Modal Body -->
                    <div class="px-6 py-6">
                        <div class="text-center mb-6">
                            <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-purple-100 to-pink-100 dark:from-purple-900/30 dark:to-pink-900/30 rounded-full flex items-center justify-center">
                                <i class="ri-user-settings-line text-4xl text-purple-600 dark:text-purple-400"></i>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                Your profile is {{ $profileCompletion }}% complete
                            </h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Complete your profile to get better matches and connect with more members. Add your photos, preferences, and personal details to stand out!
                            </p>
                            
                            <!-- Progress Bar -->
                            <div class="mt-4">
                                <div class="w-full h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-purple-600 via-purple-500 to-pink-600 rounded-full transition-all duration-300" style="width: {{ $profileCompletion }}%"></div>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">{{ $completedFields }} of {{ $totalFields }} fields completed</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Modal Footer -->
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex gap-3">
                        <button id="continue-browsing-btn" class="flex-1 px-4 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl font-semibold hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                            Continue Browsing
                        </button>
                        <a href="{{ route('account.profile.edit') }}" class="flex-1 px-4 py-2.5 bg-gradient-to-r from-[#9810FA] to-[#E60076] text-white rounded-xl font-semibold hover:shadow-lg transition-all text-center">
                            Complete Profile
                        </a>
                    </div>
                </div>
            </div>
            
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const modal = document.getElementById('complete-profile-modal');
                    const closeBtn = document.getElementById('close-complete-profile-modal');
                    const continueBtn = document.getElementById('continue-browsing-btn');
                    
                    // Show modal on page load (no persistence - will show again on next page visit)
                    if (modal) {
                        // Delay showing modal slightly for better UX
                        setTimeout(function() {
                            modal.classList.remove('hidden');
                            document.body.style.overflow = 'hidden';
                        }, 1000);
                    }
                    
                    function closeModal() {
                        if (modal) {
                            modal.classList.add('hidden');
                            document.body.style.overflow = '';
                            // No persistence - when user navigates to a new page, modal will show again
                        }
                    }
                    
                    if (closeBtn) {
                        closeBtn.addEventListener('click', closeModal);
                    }
                    
                    if (continueBtn) {
                        continueBtn.addEventListener('click', closeModal);
                    }
                    
                    // Close modal when clicking outside
                    if (modal) {
                        modal.addEventListener('click', function(e) {
                            if (e.target === modal) {
                                closeModal();
                            }
                        });
                    }
                    
                    // Close modal on Escape key
                    document.addEventListener('keydown', function(e) {
                        if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
                            closeModal();
                        }
                    });
                });
            </script>
        @endif
    @endauth

    @stack('scripts')
    
    <!-- Global Settings Toggle Script -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const settingsToggleBtn = document.getElementById('settings-toggle-btn');
        
        if (settingsToggleBtn) {
            // Remove any existing listeners
            const newBtn = settingsToggleBtn.cloneNode(true);
            settingsToggleBtn.parentNode.replaceChild(newBtn, settingsToggleBtn);
            
            newBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                // Check if we're on the profile page (index or edit)
                const isProfilePage = window.location.pathname.includes('/account/profile');
                const isEditPage = window.location.pathname.includes('/account/profile/edit');
                
                if (isProfilePage && !isEditPage) {
                    // If on profile index page, toggle sidebar
                    const sidebar = document.getElementById('settings-sidebar');
                    if (sidebar) {
                        const isOpen = sidebar.style.width !== '0px' && sidebar.style.width !== '0';
                        if (isOpen) {
                            // Close sidebar
                            sidebar.style.width = '0';
                            sidebar.style.minWidth = '0';
                            sidebar.style.overflow = 'hidden';
                        } else {
                            // Open sidebar
                            sidebar.style.width = '320px'; // w-80 = 320px
                            sidebar.style.minWidth = '320px';
                            sidebar.style.overflow = 'auto';
                        }
                    } else {
                        // Fallback: use global function if available
                        if (typeof window.toggleSettingsSidebar === 'function') {
                            window.toggleSettingsSidebar();
                        }
                    }
                } else {
                    // If on edit page or not on profile page, navigate to profile page and open sidebar
                    window.location.href = '{{ route("account.profile") }}?settings=true';
                }
            });
        }
    });
    </script>
</body>
</html>

