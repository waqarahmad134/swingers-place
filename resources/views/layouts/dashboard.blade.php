<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard - ' . config('app.name'))</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {{-- Favicon --}}
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.svg') }}">
    
    <!-- Remix Icons CDN -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.7.0/fonts/remixicon.css" rel="stylesheet" />
    <style>
        /* Ensure RemixIcon icons display correctly */
        [class^="ri-"], [class*=" ri-"] {
            font-family: 'remixicon' !important;
            font-style: normal;
            font-weight: normal;
            font-variant: normal;
            text-transform: none;
            line-height: 1;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            display: inline-block;
        }
    </style>
    
    <!-- Google Font: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet" />
    
    <style>
        .poppins {
            font-family: "Poppins", sans-serif;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
        }
    </style>
    <!-- Tailwind CSS CDN -->
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
        
        // Theme toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Update theme icons based on current theme
            const root = document.documentElement;
            const isDark = root.classList.contains('dark');
            
            // Support both new data-theme-icon and old class-based icons
            document.querySelectorAll('[data-theme-icon]').forEach((icon) => {
                const targetTheme = icon.getAttribute('data-theme-icon');
                icon.classList.toggle('hidden', targetTheme !== (isDark ? 'dark' : 'light'));
            });
            
            const lightIcons = document.querySelectorAll('.theme-icon-light');
            const darkIcons = document.querySelectorAll('.theme-icon-dark');
            
            if (isDark) {
                lightIcons.forEach(icon => icon.classList.add('hidden'));
                darkIcons.forEach(icon => icon.classList.remove('hidden'));
            } else {
                lightIcons.forEach(icon => icon.classList.remove('hidden'));
                darkIcons.forEach(icon => icon.classList.add('hidden'));
            }
            
            // Theme toggle button functionality
            const toggleButtons = document.querySelectorAll('[data-theme-toggle], #theme-toggle');
            toggleButtons.forEach((button) => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const isCurrentlyDark = root.classList.contains('dark');
                    const newTheme = isCurrentlyDark ? 'light' : 'dark';
                    
                    if (newTheme === 'dark') {
                        root.classList.add('dark');
                    } else {
                        root.classList.remove('dark');
                    }
                    
                    localStorage.setItem('theme', newTheme);
                    
                    // Update theme icons
                    document.querySelectorAll('[data-theme-icon]').forEach((icon) => {
                        const targetTheme = icon.getAttribute('data-theme-icon');
                        icon.classList.toggle('hidden', targetTheme !== newTheme);
                    });
                    
                    if (newTheme === 'dark') {
                        lightIcons.forEach(icon => icon.classList.add('hidden'));
                        darkIcons.forEach(icon => icon.classList.remove('hidden'));
                    } else {
                        lightIcons.forEach(icon => icon.classList.remove('hidden'));
                        darkIcons.forEach(icon => icon.classList.add('hidden'));
                    }
                });
            });
        });
    </script>
    
    @stack('head')
</head>
<body class="bg-gray-50 dark:bg-gray-900">
    <!-- Header -->
    @include('partials.header')
    
    <div class="flex gap-4 p-4 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <!-- Sidebar -->
        @include('components.sidebar.profile-sidebar')

        <!-- Main Content -->
        <main class="flex-1 w-full">
            @yield('content')
        </main>
    </div>
    @include('partials.footer')

    @stack('scripts')
</body>
</html>

