@extends('layouts.app')

@section('title', 'Blog - ' . config('app.name'))

@section('meta_description', 'Read our latest blog posts, articles, and updates')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        {{-- Page Header --}}
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">
                Our Blog
            </h1>
            <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                Discover the latest articles, insights, and stories from our community
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            {{-- Sidebar: Categories & Tags --}}
            <aside class="lg:col-span-1">
                <div class="sticky top-4 space-y-6">
                    {{-- Categories --}}
                    @if($categories->count() > 0)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                <i class="ri-folder-line text-primary"></i>
                                Categories
                            </h3>
                            <ul class="space-y-2">
                                <li>
                                    <a href="{{ route('blog.index') }}" 
                                       class="block px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('blog.index') && !request()->has('category') ? 'bg-primary/10 text-primary' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                        All Posts
                                        <span class="text-gray-500 dark:text-gray-400 ml-2">({{ $blogs->total() }})</span>
                                    </a>
                                </li>
                                @foreach($categories as $category)
                                    <li>
                                        <a href="{{ route('blog.category', $category->slug) }}" 
                                           class="block px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('blog.category') && request()->route('slug') == $category->slug ? 'bg-primary/10 text-primary' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                            {{ $category->name }}
                                            <span class="text-gray-500 dark:text-gray-400 ml-2">({{ $category->blogs_count }})</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Tags --}}
                    @if($tags->count() > 0)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                <i class="ri-price-tag-3-line text-primary"></i>
                                Popular Tags
                            </h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($tags->take(20) as $tag)
                                    <a href="{{ route('blog.tag', $tag->slug) }}" 
                                       class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 hover:bg-purple-200 dark:bg-purple-900/40 dark:text-purple-300 dark:hover:bg-purple-900/60 transition-colors">
                                        {{ $tag->name }}
                                        <span class="ml-1 text-purple-600 dark:text-purple-400">({{ $tag->blogs_count }})</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </aside>

            {{-- Main Content: Blog Posts --}}
            <main class="lg:col-span-3">
                @if($blogs->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        @foreach($blogs as $blog)
                            <article class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-all duration-300 group">
                                {{-- Featured Image --}}
                                @if($blog->featured_image)
                                    <a href="{{ route('blog.show', $blog->slug) }}" class="block overflow-hidden">
                                        <img src="{{ asset($blog->featured_image) }}" 
                                             alt="{{ $blog->title }}" 
                                             class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                                    </a>
                                @else
                                    <div class="w-full h-48 bg-gradient-to-br from-primary/20 to-secondary/20 dark:from-primary/30 dark:to-secondary/30 flex items-center justify-center">
                                        <i class="ri-article-line text-6xl text-gray-400 dark:text-gray-500"></i>
                                    </div>
                                @endif

                                <div class="p-6">
                                    {{-- Categories --}}
                                    @if($blog->categories->count() > 0)
                                        <div class="flex flex-wrap gap-2 mb-3">
                                            @foreach($blog->categories->take(2) as $category)
                                                <a href="{{ route('blog.category', $category->slug) }}" 
                                                   class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 hover:bg-blue-200 dark:bg-blue-900/40 dark:text-blue-300 dark:hover:bg-blue-900/60 transition-colors">
                                                    {{ $category->name }}
                                                </a>
                                            @endforeach
                                            @if($blog->categories->count() > 2)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400">
                                                    +{{ $blog->categories->count() - 2 }}
                                                </span>
                                            @endif
                                        </div>
                                    @endif

                                    {{-- Title --}}
                                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-3 group-hover:text-primary transition-colors">
                                        <a href="{{ route('blog.show', $blog->slug) }}">
                                            {{ $blog->title }}
                                        </a>
                                    </h2>

                                    {{-- Excerpt --}}
                                    @if($blog->excerpt)
                                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 line-clamp-2">
                                            {{ Str::limit($blog->excerpt, 120) }}
                                        </p>
                                    @endif

                                    {{-- Meta Information --}}
                                    <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 pt-4 border-t border-gray-200 dark:border-gray-700">
                                        <div class="flex items-center gap-4">
                                            <div class="flex items-center gap-1">
                                                <i class="ri-user-line"></i>
                                                <span>{{ $blog->author->name ?? 'Unknown' }}</span>
                                            </div>
                                            <div class="flex items-center gap-1">
                                                <i class="ri-calendar-line"></i>
                                                <span>{{ $blog->created_at->format('M d, Y') }}</span>
                                            </div>
                                            @if($blog->views > 0)
                                                <div class="flex items-center gap-1">
                                                    <i class="ri-eye-line"></i>
                                                    <span>{{ number_format($blog->views) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <a href="{{ route('blog.show', $blog->slug) }}" 
                                           class="text-primary hover:text-secondary font-medium flex items-center gap-1 transition-colors">
                                            Read More
                                            <i class="ri-arrow-right-line"></i>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-8">
                        {{ $blogs->links() }}
                    </div>
                @else
                    {{-- Empty State --}}
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-12 text-center border border-gray-200 dark:border-gray-700">
                        <i class="ri-article-line text-6xl text-gray-400 dark:text-gray-500 mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No blog posts found</h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            @if(request()->routeIs('blog.category') || request()->routeIs('blog.tag'))
                                There are no blog posts in this category/tag yet. Check back later!
                            @else
                                There are no blog posts published yet. Check back later!
                            @endif
                        </p>
                    </div>
                @endif
            </main>
        </div>
    </div>
</div>
@endsection

