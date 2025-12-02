<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Onboarding') - {{ config('app.name') }}</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.7.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Arimo:wght@400;500;600;700&display=swap" rel="stylesheet">
    
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
    </script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="bg-gray-50 dark:bg-gray-900 font-sans antialiased min-h-screen">
    <!-- Logo -->
    <div class="py-6 px-4 md:px-7">
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2">
            <i class="ri-hearts-fill text-2xl" style="color: #9810FA;"></i>
            <span class="text-xl font-bold text-gray-900 dark:text-white">swingers place</span>
        </a>
        @if(isset($showExit) && $showExit)
            <a href="{{ route('home') }}" class="float-right text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">Exit</a>
        @endif
    </div>

    <!-- Progress Bar -->
    @if(isset($step) && $step > 0)
        <div class="w-full bg-gray-200 dark:bg-gray-700 h-1">
            <div class="bg-[linear-gradient(90deg,#9810FA_0%,#E60076_100%)] h-1 transition-all duration-300" 
                 style="width: {{ ($step / 9) * 100 }}%"></div>
        </div>
    @endif

    <!-- Main Content -->
    <main class="py-8 px-4">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="mt-auto py-12 px-4 bg-gradient-to-r from-pink-400 via-pink-500 to-orange-400">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-white">
                <!-- Brand -->
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <i class="ri-hearts-fill text-2xl"></i>
                        <span class="text-xl font-bold">swingers place</span>
                    </div>
                    <p class="text-sm opacity-90">Find your perfect match today.</p>
                </div>

                <!-- Company -->
                <div>
                    <h3 class="font-semibold mb-3">Company</h3>
                    <ul class="space-y-2 text-sm opacity-90">
                        <li><a href="#" class="hover:opacity-100">About Us</a></li>
                        <li><a href="#" class="hover:opacity-100">Careers</a></li>
                        <li><a href="#" class="hover:opacity-100">Press</a></li>
                    </ul>
                </div>

                <!-- Legal -->
                <div>
                    <h3 class="font-semibold mb-3">Legal</h3>
                    <ul class="space-y-2 text-sm opacity-90">
                        <li><a href="#" class="hover:opacity-100">Terms of Service</a></li>
                        <li><a href="#" class="hover:opacity-100">Privacy Policy</a></li>
                        <li><a href="#" class="hover:opacity-100">Cookie Policy</a></li>
                    </ul>
                </div>

                <!-- Support -->
                <div>
                    <h3 class="font-semibold mb-3">Support</h3>
                    <ul class="space-y-2 text-sm opacity-90">
                        <li><a href="#" class="hover:opacity-100">Help Center</a></li>
                        <li><a href="#" class="hover:opacity-100">Safety Tips</a></li>
                        <li><a href="#" class="hover:opacity-100">Contact Us</a></li>
                    </ul>
                </div>
            </div>

            <!-- Social Links & Copyright -->
            <div class="mt-12 pt-8 border-t border-white/20 flex flex-col md:flex-row justify-between items-center gap-4 text-white text-sm">
                <p class="opacity-90">© 2025 swingers place. All rights reserved.</p>
                <div class="flex gap-4">
                    <a href="#" class="hover:opacity-100 opacity-90"><i class="ri-facebook-fill text-xl"></i></a>
                    <a href="#" class="hover:opacity-100 opacity-90"><i class="ri-twitter-fill text-xl"></i></a>
                    <a href="#" class="hover:opacity-100 opacity-90"><i class="ri-instagram-fill text-xl"></i></a>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>

