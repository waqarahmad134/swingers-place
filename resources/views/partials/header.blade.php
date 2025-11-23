@php
    $siteName = config('app.name', 'JB Fresh Chicken and Frozen Food');
    $logoUrl = config('app.logo_url', null);
    $hasLogo = !empty($logoUrl);

    // Get active pages from database for navigation
    $activePages = \App\Models\Page::where('is_active', true)
        ->whereIn('slug', ['about', 'contact'])
        ->orderByRaw("FIELD(slug, 'about', 'contact')")
        ->get()
        ->map(function($page) {
            $routeMap = [
                'about' => 'about',
                'contact' => 'contact',
            ];
            $routeName = $routeMap[$page->slug] ?? null;
            return [
                'label' => $page->title,
                'href' => $routeName && Route::has($routeName)
                    ? route($routeName)
                    : url('/' . $page->slug),
                'is_active' => request()->is($page->slug),
            ];
        })
        ->toArray();

    $navLinks = [
        [
            'label' => 'Home',
            'href' => Route::has('home') ? route('home') : url('/'),
            'is_active' => request()->routeIs('home') || request()->is('/'),
        ],
    ];
    
    $navLinks = array_merge($navLinks, $activePages);

    $navLinkClass = function (bool $isActive): string {
        $base = 'text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition-colors font-semibold';
        return trim($base . ($isActive ? ' text-primary dark:text-primary' : ''));
    };

    $mobileMenuId = 'primary-mobile-nav';

    // Get unread message count for authenticated users
    $unreadMessageCount = 0;
    if (Auth::check()) {
        $unreadMessageCount = \App\Models\Message::where('receiver_id', Auth::id())
            ->whereNull('read_at')
            ->count();
    }
@endphp

<header class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-sm sticky top-0 z-90 shadow-sm text-gray-900 dark:text-gray-100">
    <nav class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            <div class="shrink-0">
                <a href="{{ Route::has('home') ? route('home') : url('/') }}" class="flex items-center gap-3 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                    @if($hasLogo)
                        <img src="{{ $logoUrl }}" alt="{{ $siteName }}" class="h-10 w-auto sm:h-12" loading="eager">
                    @else
                        <img src="{{ asset('public/logo.svg') }}" alt="{{ $siteName }}" class="h-10 w-auto sm:h-12" loading="eager">
                        <span class="hidden text-xl font-extrabold text-primary sm:block md:text-2xl">{{ $siteName }}</span>
                    @endif
                </a>
            </div>

            <div class="hidden md:block">
                <div class="ml-10 flex items-baseline space-x-6">
                    @foreach ($navLinks as $link)
                        <a href="{{ $link['href'] }}" class="{{ $navLinkClass($link['is_active']) }}">
                            {{ $link['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center space-x-3">
                @auth
                    <div class="relative" data-messages-dropdown data-messages-url="{{ route('messages.recent', [], false) }}">
                        <button type="button" data-messages-toggle class="relative p-2 rounded-full text-gray-700 dark:text-gray-300 hover:bg-amber-100 dark:hover:bg-gray-700 transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                            <span class="sr-only">Messages</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                            <span data-messages-count class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full {{ $unreadMessageCount > 0 ? '' : 'hidden' }}">{{ $unreadMessageCount > 99 ? '99+' : $unreadMessageCount }}</span>
                        </button>
                        <div data-messages-panel class="absolute right-0 mt-2 w-80 rounded-lg bg-white shadow-xl ring-1 ring-black/10 dark:bg-gray-800 dark:ring-white/10 opacity-0 invisible pointer-events-none transition-all duration-200 transform translate-y-1 z-50 max-h-[32rem] flex flex-col">
                            <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                                <h3 class="text-base font-semibold text-gray-900 dark:text-white">Messages</h3>
                                <button type="button" data-messages-close class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <div data-messages-list class="flex-1 overflow-y-auto">
                                <div data-messages-loading class="p-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                    Loading messages...
                                </div>
                                <div data-messages-empty class="hidden p-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                    No messages yet
                                </div>
                                <div data-messages-items class="divide-y divide-gray-200 dark:divide-gray-700"></div>
                            </div>
                        </div>
                    </div>
                @endauth

                <button type="button" data-theme-toggle aria-label="Toggle dark mode" class="p-2 rounded-full text-gray-700 dark:text-gray-300 hover:bg-amber-100 dark:hover:bg-gray-700 transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                    <span aria-hidden="true" data-theme-icon="light">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" />
                        </svg>
                    </span>
                    <span aria-hidden="true" data-theme-icon="dark" class="hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </span>
                </button>

                <div class="hidden md:block">
                    @auth
                        <div class="relative group">
                            <a href="{{ Route::has('account.profile') ? route('account.profile') : url('/account/profile') }}" class="p-2 flex items-center space-x-2 rounded-full hover:bg-amber-100 dark:hover:bg-gray-700 transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                                <span class="sr-only">Account</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </a>
                            <div class="absolute right-0 w-56 rounded-md bg-white py-2 text-sm shadow-lg ring-1 ring-black/10 dark:bg-gray-800 dark:ring-white/10 opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none group-hover:pointer-events-auto">
                                <div class="px-4 py-2 text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700">
                                    Signed in as<br>
                                    <span class="font-semibold">{{ Auth::user()->name ?? 'User' }}</span>
                                </div>
                                <a href="{{ Route::has('account.profile') ? route('account.profile') : url('/account/profile') }}" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Profile</a>
                                @if (Auth::user()?->is_admin)
                                    <a href="{{ url('/admin') }}" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Admin Panel</a>
                                @endif
                                @php
                                    $logoutUrl = Route::has('logout') ? route('logout') : url('/logout');
                                @endphp
                                <form method="POST" action="{{ $logoutUrl }}" class="block">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Logout</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="relative group">
                            <a href="{{ Route::has('login') ? route('login') : url('/login') }}" class="p-2 flex items-center space-x-2 rounded-full hover:bg-amber-100 dark:hover:bg-gray-700 transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                                <span class="sr-only">Sign in</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </a>
                        </div>
                    @endauth
                </div>

                <div class="md:hidden">
                    <button type="button" data-mobile-menu-toggle data-mobile-menu-target="{{ $mobileMenuId }}" aria-controls="{{ $mobileMenuId }}" aria-expanded="false" class="inline-flex items-center justify-center p-2 rounded-md text-gray-700 dark:text-gray-300 hover:text-primary focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                        <span class="sr-only">Toggle navigation</span>
                        <svg data-menu-icon="closed" class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                        </svg>
                        <svg data-menu-icon="open" class="h-6 w-6 hidden" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div id="{{ $mobileMenuId }}" class="md:hidden hidden pb-4">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                @foreach ($navLinks as $link)
                    <a href="{{ $link['href'] }}" data-mobile-menu-close class="block rounded-md px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-primary dark:text-gray-300 dark:hover:bg-gray-700">
                        {{ $link['label'] }}
                    </a>
                @endforeach
                <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4 space-y-2">
                    @auth
                        <a href="{{ Route::has('account.profile') ? route('account.profile') : url('/account/profile') }}" data-mobile-menu-close class="block rounded-md px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-primary dark:text-gray-300 dark:hover:bg-gray-700">Profile</a>
                        @if (Auth::user()?->is_admin)
                            <a href="{{ url('/admin') }}" data-mobile-menu-close class="block rounded-md px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-primary dark:text-gray-300 dark:hover:bg-gray-700">Admin Panel</a>
                        @endif
                        @php
                            $mobileLogoutUrl = Route::has('logout') ? route('logout') : url('/logout');
                        @endphp
                        <form method="POST" action="{{ $mobileLogoutUrl }}">
                            @csrf
                            <button type="submit" data-mobile-menu-close class="w-full text-left rounded-md px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-primary dark:text-gray-300 dark:hover:bg-gray-700">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ Route::has('login') ? route('login') : url('/login') }}" data-mobile-menu-close class="block rounded-md px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-primary dark:text-gray-300 dark:hover:bg-gray-700">Login</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" data-mobile-menu-close class="block rounded-md px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-primary dark:text-gray-300 dark:hover:bg-gray-700">Register</a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </nav>
</header>

