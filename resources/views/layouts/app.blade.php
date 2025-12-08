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

    <link rel="stylesheet" href="{{ asset('public/style.css') }}">

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

