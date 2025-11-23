@php
    $siteName = config('app.name', 'JB Fresh Chicken and Frozen Food');

    // Get active pages from database for footer links
    $quickPages = \App\Models\Page::where('is_active', true)
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
            ];
        })
        ->toArray();

    $infoPages = \App\Models\Page::where('is_active', true)
        ->whereIn('slug', ['terms', 'privacy'])
        ->orderByRaw("FIELD(slug, 'terms', 'privacy')")
        ->get()
        ->map(function($page) {
            $routeMap = [
                'terms' => 'terms',
                'privacy' => 'privacy',
            ];
            $routeName = $routeMap[$page->slug] ?? null;
            return [
                'label' => $page->title,
                'href' => $routeName && Route::has($routeName)
                    ? route($routeName)
                    : url('/' . $page->slug),
            ];
        })
        ->toArray();

    $quickLinks = $quickPages;
    $informationLinks = $infoPages;
@endphp

<footer class="bg-gray-100 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
            <div>
                <h4 class="text-xl font-extrabold text-primary mb-3">{{ $siteName }}</h4>
                <p class="text-gray-600 dark:text-gray-400 mb-4">
                    Your trusted source for quality products and exceptional service.
                </p>
                <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                    </svg>
                    <a href="tel:+923039345647" class="hover:text-primary transition-colors">
                        0303-9345647
                    </a>
                </div>
            </div>

            <div>
                <h5 class="font-bold mb-4 uppercase text-sm text-gray-500 dark:text-gray-400 tracking-wide">Quick Links</h5>
                <ul class="space-y-2">
                    @foreach ($quickLinks as $link)
                        <li>
                            <a href="{{ $link['href'] }}" class="text-gray-600 hover:text-primary transition-colors dark:text-gray-400 dark:hover:text-primary">
                                {{ $link['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h5 class="font-bold mb-4 uppercase text-sm text-gray-500 dark:text-gray-400 tracking-wide">Information</h5>
                <ul class="space-y-2">
                    @foreach ($informationLinks as $link)
                        <li>
                            <a href="{{ $link['href'] }}" class="text-gray-600 hover:text-primary transition-colors dark:text-gray-400 dark:hover:text-primary">
                                {{ $link['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

        </div>

        <div class="mt-12 border-t border-gray-200 dark:border-gray-700 pt-8 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <p class="text-sm text-gray-500 dark:text-gray-400">&copy; {{ now()->year }} {{ $siteName }}. All rights reserved.</p>
            <div class="flex items-center space-x-4">
                <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" class="text-gray-500 hover:text-primary transition-colors dark:text-gray-400" aria-label="Facebook">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M22 12.07C22 6.48 17.52 2 11.93 2S1.86 6.48 1.86 12.07c0 4.99 3.65 9.13 8.43 9.92v-7.02H7.9v-2.9h2.39V9.93c0-2.37 1.42-3.68 3.58-3.68 1.04 0 2.13.19 2.13.19v2.35h-1.2c-1.18 0-1.55.73-1.55 1.48v1.78h2.64l-.42 2.9h-2.22v7.02c4.78-.79 8.43-4.93 8.43-9.92z" />
                    </svg>
                </a>
                <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" class="text-gray-500 hover:text-primary transition-colors dark:text-gray-400" aria-label="Instagram">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M7 2C4.24 2 2 4.24 2 7v10c0 2.76 2.24 5 5 5h10c2.76 0 5-2.24 5-5V7c0-2.76-2.24-5-5-5H7zm10 2a3 3 0 013 3v10a3 3 0 01-3 3H7a3 3 0 01-3-3V7a3 3 0 013-3h10zm-5 3.2a4.8 4.8 0 100 9.6 4.8 4.8 0 000-9.6zm0 2.4a2.4 2.4 0 110 4.8 2.4 2.4 0 010-4.8zm5.38-.98a1.12 1.12 0 11-2.24 0 1.12 1.12 0 012.24 0z" />
                    </svg>
                </a>
                <a href="https://twitter.com" target="_blank" rel="noopener noreferrer" class="text-gray-500 hover:text-primary transition-colors dark:text-gray-400" aria-label="Twitter">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8.29 20c7.55 0 11.68-6.26 11.68-11.68 0-.18 0-.35-.01-.53A8.35 8.35 0 0022 5.92a8.19 8.19 0 01-2.35.64 4.1 4.1 0 001.8-2.27 8.2 8.2 0 01-2.6.99 4.09 4.09 0 00-7 3.73A11.6 11.6 0 013 4.89a4.09 4.09 0 001.27 5.46 4.07 4.07 0 01-1.85-.51v.05a4.09 4.09 0 003.27 4.01 4.1 4.1 0 01-1.84.07 4.1 4.1 0 003.83 2.85A8.22 8.22 0 012 18.16 11.6 11.6 0 008.29 20z" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</footer>

