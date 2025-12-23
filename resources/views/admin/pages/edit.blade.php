@extends('layouts.admin')

@section('title', 'Edit Page - Admin Panel')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <h1 class="text-3xl font-extrabold text-secondary">Edit Page</h1>
        <a href="{{ route(($routePrefix ?? 'admin') . '.pages.index') }}" class="inline-flex items-center gap-2 rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 transition-colors hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
            Back to Pages
        </a>
    </div>

    <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm text-gray-900 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
        <form action="{{ route(($routePrefix ?? 'admin') . '.pages.update', $page) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title *</label>
                <input type="text" id="title" name="title" value="{{ old('title', $page->title) }}" required class="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-primary focus:outline-none focus:ring-primary dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                @error('title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Slug</label>
                <input type="text" id="slug" name="slug" value="{{ old('slug', $page->slug) }}" placeholder="auto-generated-from-title" class="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-primary focus:outline-none focus:ring-primary dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                @error('slug')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Leave empty to auto-generate from title. Used in URL (e.g., /about, /contact)</p>
            </div>

            <div>
                <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Content *</label>
                <textarea id="content" name="content" required>{{ old('content', $page->content) }}</textarea>
                @error('content')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Use the editor toolbar to format your content. Switch to Code View (< / >) to add HTML directly.</p>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <label for="meta_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Meta Title</label>
                    <input type="text" id="meta_title" name="meta_title" value="{{ old('meta_title', $page->meta_title) }}" class="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-primary focus:outline-none focus:ring-primary dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    @error('meta_title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="template" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Page Template</label>
                    <select id="template" name="template" class="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-primary focus:outline-none focus:ring-primary dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        <option value="1" {{ old('template', $page->template ?? 1) == 1 ? 'selected' : '' }}>Template 1 (Default)</option>
                        <option value="2" {{ old('template', $page->template ?? 1) == 2 ? 'selected' : '' }}>Template 2</option>
                        <option value="3" {{ old('template', $page->template ?? 1) == 3 ? 'selected' : '' }}>Template 3</option>
                    </select>
                    @error('template')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Choose the page design template</p>
                </div>
            </div>

            <div>
                <label for="meta_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Meta Description</label>
                <textarea id="meta_description" name="meta_description" rows="3" class="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-primary focus:outline-none focus:ring-primary dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('meta_description', $page->meta_description) }}</textarea>
                @error('meta_description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="meta_keywords" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Meta Keywords</label>
                <input type="text" id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords', $page->meta_keywords) }}" placeholder="keyword1, keyword2, keyword3" class="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-primary focus:outline-none focus:ring-primary dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                @error('meta_keywords')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Comma-separated keywords for SEO</p>
            </div>

            <div class="border-t border-gray-200 pt-6 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Open Graph (Social Media) Settings</h3>
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
                        <label for="og_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">OG Title</label>
                        <input type="text" id="og_title" name="og_title" value="{{ old('og_title', $page->og_title) }}" class="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-primary focus:outline-none focus:ring-primary dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        @error('og_title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="og_image" class="block text-sm font-medium text-gray-700 dark:text-gray-300">OG Image URL</label>
                        <input type="text" id="og_image" name="og_image" value="{{ old('og_image', $page->og_image) }}" placeholder="https://example.com/image.jpg" class="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-primary focus:outline-none focus:ring-primary dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        @error('og_image')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div class="mt-4">
                    <label for="og_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">OG Description</label>
                    <textarea id="og_description" name="og_description" rows="2" class="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-primary focus:outline-none focus:ring-primary dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('og_description', $page->og_description) }}</textarea>
                    @error('og_description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="border-t border-gray-200 pt-6 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Twitter Card Settings</h3>
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
                        <label for="twitter_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Twitter Title</label>
                        <input type="text" id="twitter_title" name="twitter_title" value="{{ old('twitter_title', $page->twitter_title) }}" class="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-primary focus:outline-none focus:ring-primary dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        @error('twitter_title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="twitter_image" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Twitter Image URL</label>
                        <input type="text" id="twitter_image" name="twitter_image" value="{{ old('twitter_image', $page->twitter_image) }}" placeholder="https://example.com/image.jpg" class="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-primary focus:outline-none focus:ring-primary dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        @error('twitter_image')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div class="mt-4">
                    <label for="twitter_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Twitter Description</label>
                    <textarea id="twitter_description" name="twitter_description" rows="2" class="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-primary focus:outline-none focus:ring-primary dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('twitter_description', $page->twitter_description) }}</textarea>
                    @error('twitter_description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 border-t border-gray-200 pt-6 dark:border-gray-700">
                <div>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $page->is_active) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Active</span>
                    </label>
                    <p class="mt-1 text-xs text-gray-500">Only active pages are accessible on the frontend</p>
                </div>
                <div>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="allow_indexing" value="1" {{ old('allow_indexing', $page->allow_indexing ?? true) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Allow Search Engine Indexing</span>
                    </label>
                    <p class="mt-1 text-xs text-gray-500">Uncheck to add noindex meta tag</p>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-gray-200 pt-6 dark:border-gray-700">
                <a href="{{ route(($routePrefix ?? 'admin') . '.pages.index') }}" class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm transition-colors hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                    Cancel
                </a>
                <button type="submit" class="rounded-md bg-primary px-4 py-2 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-primary-dark">
                    Update Page
                </button>
            </div>
        </form>
    </div>
@endsection

@push('head')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin-editor.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
    <script>
        {!! file_get_contents(resource_path('js/admin-editor.js')) !!}
    </script>
@endpush

