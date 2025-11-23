@extends('layouts.app')
@section('full-width')
    <div class="space-y-24 py-10 md:py-10">
        <section class="relative" data-carousel data-carousel-autoplay="false">
            <div class="relative mx-auto max-w-6xl h-[22rem] md:h-[30rem] overflow-hidden rounded-3xl border border-gray-200 bg-gray-900 shadow-xl dark:border-gray-700">
                @foreach ($slides as $index => $slide)
                    <article
                        data-carousel-slide
                        class="absolute inset-0 h-full w-full transition-opacity duration-700 ease-in-out {{ $index === 0 ? 'opacity-100 z-10' : 'opacity-0 -z-10' }}"
                    >
                        <img src="{{ $slide['image'] }}" alt="{{ $slide['title'] }}" class="absolute inset-0 h-full w-full object-cover" loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/60 to-black/30"></div>
                        <div class="relative z-10 flex h-full flex-col justify-center gap-4 p-8 text-white sm:p-20">
                            @if(!empty($slide['tagline']))
                                <span class="inline-flex items-center gap-2 self-start rounded-full bg-white/20 px-4 py-1 text-xs font-semibold uppercase tracking-wider">
                                    <span class="h-2 w-2 rounded-full bg-primary"></span> {{ $slide['tagline'] }}
                                </span>
                            @endif
                            <h2 class="text-3xl font-extrabold sm:text-5xl">{{ $slide['title'] }}</h2>
                            <p class="max-w-xl text-base text-white/80 sm:text-lg">{{ $slide['subtitle'] }}</p>
                            @if(!empty($slide['button_label']))
                                <div>
                                    <a href="{{ $slide['button_url'] ?? '#' }}" class="inline-flex items-center rounded-full bg-primary px-6 py-3 text-sm font-semibold text-white transition-colors hover:bg-secondary">
                                        {{ $slide['button_label'] }}
                                        <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.25 8.75L21 12m0 0l-3.75 3.25M21 12H3" />
                                        </svg>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </article>
                @endforeach

                <button type="button" data-carousel-prev class="hidden md:block absolute left-4 top-1/2 z-20 -translate-y-1/2 rounded-full bg-white/90 dark:bg-gray-800/90 p-3 text-gray-800 dark:text-gray-200 shadow-lg transition hover:bg-white dark:hover:bg-gray-800 hover:scale-110 focus:outline-none focus:ring-2 focus:ring-primary">
                    <span class="sr-only">Previous</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 010 1.06L9.06 10l3.72 3.71a.75.75 0 11-1.06 1.06l-4.25-4.25a.75.75 0 010-1.06l4.25-4.25a.75.75 0 011.06 0z" clip-rule="evenodd" />
                    </svg>
                </button>
                <button type="button" data-carousel-next class="hidden md:block absolute right-4 top-1/2 z-20 -translate-y-1/2 rounded-full bg-white/90 dark:bg-gray-800/90 p-3 text-gray-800 dark:text-gray-200 shadow-lg transition hover:bg-white dark:hover:bg-gray-800 hover:scale-110 focus:outline-none focus:ring-2 focus:ring-primary">
                    <span class="sr-only">Next</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 010-1.06L10.94 10 7.2 6.29a.75.75 0 111.06-1.06l4.25 4.25a.75.75 0 010 1.06l-4.25 4.25a.75.75 0 01-1.06 0z" clip-rule="evenodd" />
                    </svg>
                </button>

                <div class="absolute bottom-6 left-1/2 z-20 flex -translate-x-1/2 items-center gap-2">
                    @foreach ($slides as $index => $slide)
                        <button type="button" data-carousel-indicator class="h-2 w-6 rounded-full transition-all duration-300 hover:w-8 cursor-pointer {{ $index === 0 ? 'bg-primary' : 'bg-white/60' }}">
                            <span class="sr-only">Go to slide {{ $index + 1 }}</span>
                        </button>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <p class="text-sm font-semibold uppercase tracking-widest text-primary">Explore Profiles</p>
                <h3 class="mt-2 text-3xl font-extrabold text-dark dark:text-white">Our Business Partners</h3>
            </div>
            <div class="mt-10" data-tab-group data-tab-initial="all">
                <div class="flex flex-wrap justify-center gap-3">
                    @foreach ($tabs as $tab)
                        <button
                            type="button"
                            data-tab-trigger
                            data-tab-target="{{ $tab['id'] }}"
                            class="rounded-full border border-gray-200 px-6 py-2 text-sm font-semibold text-gray-700 transition hover:border-primary hover:text-primary dark:border-gray-700 dark:text-gray-300"
                        >
                            {{ $tab['label'] }}
                        </button>
                    @endforeach
                </div>

                @foreach ($tabs as $tab)
                    @php
                        $users = $tabbedUsers[$tab['id']] ?? collect();
                    @endphp
                    <div
                        data-tab-panel
                        data-tab-id="{{ $tab['id'] }}"
                        class="mt-10 {{ $loop->first ? '' : 'hidden' }}"
                    >
                        @if ($users->isNotEmpty())
                            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                                @foreach ($users as $user)
                                    <a href="{{ route('user.profile', $user->id) }}" class="group rounded-2xl border border-gray-200 bg-white p-6 shadow-sm text-gray-900 transition hover:-translate-y-1 hover:shadow-lg dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 cursor-pointer block">
                                        <div class="flex items-center gap-4">
                                            <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-full bg-primary/10 text-primary">
                                                @if($user->profile_image)
                                                    <img src="{{ asset('storage/' . $user->profile_image) }}" alt="{{ $user->name }}" class="h-16 w-16 rounded-full object-cover">
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                @endif
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <h4 class="text-lg font-bold text-dark dark:text-white truncate">{{ $user->name }}</h4>
                                                @if($user->company)
                                                    <p class="text-sm text-gray-600 dark:text-gray-400 truncate">{{ $user->company }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        @if($user->email)
                                            <div class="mt-4 flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                </svg>
                                                <span class="truncate">{{ $user->email }}</span>
                                            </div>
                                        @endif

                                        @if($user->phone)
                                            <div class="mt-2 flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                </svg>
                                                <span>{{ $user->phone }}</span>
                                            </div>
                                        @endif

                                        @if($user->website_url)
                                            <div class="mt-2">
                                                <span class="text-sm text-primary hover:underline">
                                                    Visit Website →
                                                </span>
                                            </div>
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="rounded-2xl border border-dashed border-gray-300 p-10 text-center dark:border-gray-700">
                                <p class="text-sm text-gray-600 dark:text-gray-400">No profiles available in this category. Check back soon!</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </section>

        <section class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid gap-6 md:grid-cols-3">
                <article class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm text-gray-900 transition hover:-translate-y-1 hover:shadow-lg dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-primary/10 text-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6">
                            <path fill-rule="evenodd" d="M11.54 2.47a.75.75 0 01.92 0l7.5 5.5a.75.75 0 01-.44 1.35H4.48a.75.75 0 01-.44-1.35l7.5-5.5zM3.75 10.25a.75.75 0 01.75-.75h15a.75.75 0 01.75.75v8a3 3 0 01-3 3H6.75a3 3 0 01-3-3v-8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <h4 class="mt-5 text-xl font-bold text-dark dark:text-white">Verified Profiles</h4>
                    <p class="mt-3 text-sm text-gray-600 dark:text-gray-300">Connect with trusted businesses and professionals. All profiles are verified for authenticity and quality.</p>
                </article>
                <article class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm text-gray-900 transition hover:-translate-y-1 hover:shadow-lg dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-primary/10 text-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75" />
                        </svg>
                    </div>
                    <h4 class="mt-5 text-xl font-bold text-dark dark:text-white">Easy Discovery</h4>
                    <p class="mt-3 text-sm text-gray-600 dark:text-gray-300">Browse businesses by category and find exactly what you're looking for with our intuitive search and filter system.</p>
                </article>
                <article class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm text-gray-900 transition hover:-translate-y-1 hover:shadow-lg dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-primary/10 text-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6">
                            <path fill-rule="evenodd" d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.21 5.21 0 0112 5.052 5.21 5.21 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.011-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.002z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <h4 class="mt-5 text-xl font-bold text-dark dark:text-white">Trusted Network</h4>
                    <p class="mt-3 text-sm text-gray-600 dark:text-gray-300">Join a growing community of businesses and professionals building meaningful connections and partnerships.</p>
                </article>
            </div>
        </section>
    </div>
@endsection

