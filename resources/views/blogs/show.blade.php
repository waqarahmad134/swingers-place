@extends('layouts.app')

@section('title', ($blog->meta_title ?? $blog->title) . ' - ' . config('app.name'))

@section('meta_description', $blog->meta_description ?? $blog->excerpt ?? '')

@push('head')
    {{-- Meta Keywords --}}
    @if($blog->meta_keywords)
        <meta name="keywords" content="{{ $blog->meta_keywords }}">
    @endif

    {{-- Robots Meta Tag --}}
    @if(!($blog->allow_indexing ?? true))
        <meta name="robots" content="noindex, nofollow">
    @else
        <meta name="robots" content="index, follow">
    @endif

    {{-- Open Graph Meta Tags --}}
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $blog->og_title ?? $blog->meta_title ?? $blog->title }}">
    <meta property="og:description" content="{{ $blog->og_description ?? $blog->meta_description ?? $blog->excerpt ?? '' }}">
    <meta property="og:url" content="{{ url('/blog/' . $blog->slug) }}">
    @if($blog->og_image)
        <meta property="og:image" content="{{ url(asset($blog->og_image)) }}">
    @elseif($blog->featured_image)
        <meta property="og:image" content="{{ url(asset($blog->featured_image)) }}">
    @endif
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="article:published_time" content="{{ $blog->created_at->toIso8601String() }}">
    <meta property="article:modified_time" content="{{ $blog->updated_at->toIso8601String() }}">
    <meta property="article:author" content="{{ $blog->author->name ?? '' }}">
    @foreach($blog->categories as $category)
        <meta property="article:section" content="{{ $category->name }}">
    @endforeach
    @foreach($blog->tags as $tag)
        <meta property="article:tag" content="{{ $tag->name }}">
    @endforeach

    {{-- Twitter Card Meta Tags --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $blog->twitter_title ?? $blog->og_title ?? $blog->meta_title ?? $blog->title }}">
    <meta name="twitter:description" content="{{ $blog->twitter_description ?? $blog->og_description ?? $blog->meta_description ?? $blog->excerpt ?? '' }}">
    @if($blog->twitter_image)
        <meta name="twitter:image" content="{{ url(asset($blog->twitter_image)) }}">
    @elseif($blog->og_image)
        <meta name="twitter:image" content="{{ url(asset($blog->og_image)) }}">
    @elseif($blog->featured_image)
        <meta name="twitter:image" content="{{ url(asset($blog->featured_image)) }}">
    @endif
@endpush

@section('content')
    @include($templateView)

    {{-- Related Posts --}}
    @if($relatedBlogs->count() > 0)
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mt-16">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">Related Posts</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($relatedBlogs as $relatedBlog)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-shadow">
                        @if($relatedBlog->featured_image)
                            <img src="{{ asset($relatedBlog->featured_image) }}" alt="{{ $relatedBlog->title }}" class="w-full h-48 object-cover">
                        @endif
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                                <a href="{{ url('/blog/' . $relatedBlog->slug) }}" class="hover:text-primary">{{ $relatedBlog->title }}</a>
                            </h3>
                            @if($relatedBlog->excerpt)
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">{{ Str::limit($relatedBlog->excerpt, 100) }}</p>
                            @endif
                            <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                                <span>{{ $relatedBlog->created_at->format('M d, Y') }}</span>
                                <a href="{{ url('/blog/' . $relatedBlog->slug) }}" class="text-primary hover:underline">Read More →</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
@endsection

