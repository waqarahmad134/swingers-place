@php
    $siteSettings = \App\Models\SiteSetting::getSettings();
    $siteName = $siteSettings->site_title ?? config('app.name', 'swingers nest');
    $logoUrl = $siteSettings->site_icon ?? config('app.logo_url', null);
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
        [
            'label' => 'Blog',
            'href' => Route::has('blog.index') ? route('blog.index') : url('/blog'),
            'is_active' => request()->routeIs('blog.*'),
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

    <nav class="flex justify-between items-center md:px-7 px-3 h-[68px] bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
      <!-- Logo -->
      <div class="flex gap-2 items-center">
        <a href="{{ Auth::check() ? route('dashboard.members') : (Route::has('home') ? route('home') : url('/')) }}" class="group">
          @if($hasLogo)
            <img src="{{ asset($logoUrl) }}" height="32" width="172" alt="{{ $siteName }}" class="h-8 md:h-10 w-auto" />
          @else
            <span class="text-xl md:text-2xl font-semibold text-gray-900 dark:text-white tracking-wide hover:text-[#9810FA] dark:hover:text-[#E60076] transition-colors duration-200">
              {{ $siteName }}
            </span>
          @endif
        </a>
      </div>

      <!-- Search Bar (Center) - Only show when logged in -->
      @auth
        <div class="flex-1 max-w-2xl mx-4 hidden md:block">
          <div class="relative">
            <input 
              type="text" 
              placeholder="Search members, events, businesses..." 
              class="w-full px-4 py-2 pl-10 rounded-full border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
            >
            <i class="ri-search-line absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
          </div>
        </div>
      @endauth

      <!-- Right Icons & Actions -->
      <div class="flex items-center gap-3 md:gap-4">
        @auth
          <!-- Members Link -->
          <a href="{{ route('dashboard.members') }}" class="p-2 text-gray-600 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 transition-colors" title="Members">
            <i class="ri-group-line text-2xl"></i>
          </a>
          
          <!-- Notifications -->
          <!-- <a href="#" class="relative p-2 text-gray-600 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 transition-colors">
            <i class="ri-notification-3-line text-2xl"></i>
            <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
          </a> -->
          
          <!-- Messages -->
          <a href="{{ route('messages.index') }}" class="relative p-2 text-gray-600 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 transition-colors">
            <i class="ri-message-3-line text-2xl"></i>
            @if($unreadMessageCount > 0)
              <span class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">{{ $unreadMessageCount > 9 ? '9+' : $unreadMessageCount }}</span>
            @endif
          </a>
          
          <!-- Friends/Requests -->
          <!-- <a href="#" class="p-2 text-gray-600 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 transition-colors">
            <i class="ri-user-add-line text-2xl"></i>
          </a> -->
          
          <!-- Theme Toggle -->
          <button id="theme-toggle" data-theme-toggle aria-label="Toggle theme" class="p-2 text-gray-600 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 transition-colors">
            <i class="ri-moon-line text-2xl" data-theme-icon="light"></i>
            <i class="ri-sun-line text-2xl hidden" data-theme-icon="dark"></i>
          </button>
          
          <!-- Blog Link -->
          <a href="{{ Route::has('blog.index') ? route('blog.index') : url('/blog') }}" 
             class="p-2 text-gray-600 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 transition-colors {{ request()->routeIs('blog.*') ? 'text-purple-600 dark:text-purple-400' : '' }}" 
             title="Blog">
            <i class="ri-article-line text-2xl"></i>
          </a>
          
          <!-- Settings Icon -->
          <button id="settings-toggle-btn" class="p-2 text-gray-600 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 transition-colors" title="Settings">
            <i class="ri-settings-3-line text-2xl"></i>
          </button>

        <form method="POST" action="{{ Route::has('logout') ? route('logout') : url('/logout') }}">
            @csrf
            <button type="submit" class="p-2 text-gray-600 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 transition-colors" title="Logout">
                <i class="ri-logout-box-line text-2xl"></i>
            </button>
        </form>
          
          <!-- User Avatar Dropdown -->
          <div class="relative group">
            <button class="flex items-center focus:outline-none">
              @if(Auth::user()->profile_image)
                <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" alt="{{ Auth::user()->name }}" class="w-10 h-10 rounded-full object-cover border-2 border-purple-500 hover:border-purple-600 transition-colors">
              @else
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-400 to-pink-400 flex items-center justify-center text-white font-bold text-sm border-2 border-purple-500 hover:border-purple-600 transition-colors">
                  {{ strtoupper(substr(Auth::user()->first_name ?? Auth::user()->name, 0, 1)) }}{{ strtoupper(substr(Auth::user()->last_name ?? '', 0, 1)) }}
                </div>
              @endif
            </button>
            
            <!-- Dropdown Menu -->
            <div id="user-dropdown-menu" class="absolute right-0 top-full pt-1 w-48 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
              <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl ring-1 ring-black/10 dark:ring-white/10">
                <div class="py-1">
                  <!-- User Info -->
                  <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ Auth::user()->name ?? 'User' }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</p>
                  </div>
                  
                  <!-- Profile Link -->
                  <a href="{{ route('account.profile') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <i class="ri-user-line mr-2"></i>Profile
                  </a>
                  
                  <!-- Members Link -->
                  <a href="{{ route('dashboard.members') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <i class="ri-group-line mr-2"></i>Members
                  </a>
                  
                  <!-- Admin Panel Link (if admin) -->
                  @if (Auth::user()?->is_admin)
                    <a href="{{ url('/admin') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                      <i class="ri-admin-line mr-2"></i>Admin Panel
                    </a>
                  @endif
                  
                  <!-- Logout -->
                  <form method="POST" action="{{ Route::has('logout') ? route('logout') : url('/logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                      <i class="ri-logout-box-line mr-2"></i>Logout
                    </button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        @else
          <!-- Guest: Show Theme Toggle, Blog, Login & Sign Up -->
          <!-- Theme Toggle -->
          <button id="theme-toggle" data-theme-toggle aria-label="Toggle theme" class="p-2 text-gray-600 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 transition-colors">
            <i class="ri-moon-line text-2xl" data-theme-icon="light"></i>
            <i class="ri-sun-line text-2xl hidden" data-theme-icon="dark"></i>
          </button>
          
          <!-- Blog Link -->
          <a href="{{ Route::has('blog.index') ? route('blog.index') : url('/blog') }}" 
             class="text-gray-700 dark:text-gray-300 hover:text-[#9810FA] dark:hover:text-[#E60076] transition-colors font-semibold {{ request()->routeIs('blog.*') ? 'text-[#9810FA] dark:text-[#E60076]' : '' }}">
            Blog
          </a>
          
          <a href="{{ route('login') }}" 
             class="border-2 border-[#9810FA] md:py-2 py-1 md:text-base text-sm px-3 md:px-6 rounded-3xl text-[#9810FA] hover:bg-[#9810FA] hover:text-white transition-all">
            Login
          </a>
          <a href="{{ route('register') }}" 
             class="text-white bg-[#9810FA] md:text-base text-xs py-2 px-4 md:py-3 md:px-5 rounded-3xl hover:bg-[#E60076] transition-all">
            Sign up
          </a>
        @endauth
      </div>
    </nav>


