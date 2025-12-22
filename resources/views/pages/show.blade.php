@extends('layouts.app')

@section('title', ($page->meta_title ?? $page->title) . ' - ' . config('app.name'))

@section('meta_description', $page->meta_description ?? '')

@push('head')
    {{-- Meta Keywords --}}
    @if($page->meta_keywords)
        <meta name="keywords" content="{{ $page->meta_keywords }}">
    @endif

    {{-- Robots Meta Tag --}}
    @if(!($page->allow_indexing ?? true))
        <meta name="robots" content="noindex, nofollow">
    @else
        <meta name="robots" content="index, follow">
    @endif

    {{-- Open Graph Meta Tags --}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $page->og_title ?? $page->meta_title ?? $page->title }}">
    <meta property="og:description" content="{{ $page->og_description ?? $page->meta_description ?? '' }}">
    <meta property="og:url" content="{{ url('/' . $page->slug) }}">
    @if($page->og_image)
        <meta property="og:image" content="{{ $page->og_image }}">
    @endif
    <meta property="og:site_name" content="{{ config('app.name') }}">

    {{-- Twitter Card Meta Tags --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $page->twitter_title ?? $page->og_title ?? $page->meta_title ?? $page->title }}">
    <meta name="twitter:description" content="{{ $page->twitter_description ?? $page->og_description ?? $page->meta_description ?? '' }}">
    @if($page->twitter_image)
        <meta name="twitter:image" content="{{ $page->twitter_image }}">
    @elseif($page->og_image)
        <meta name="twitter:image" content="{{ $page->og_image }}">
    @endif
@endpush

@section('content')
    @php
        if (!isset($templateView)) {
            $template = $page->template ?? 1;
            $templateView = 'pages.templates.template-' . $template;
            if (!view()->exists($templateView)) {
                $templateView = 'pages.templates.template-1';
            }
        }
    @endphp
    @include($templateView)
@endsection

