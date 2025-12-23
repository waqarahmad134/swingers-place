@extends('layouts.admin')

@section('title', 'Manage Blog Posts - Admin Panel')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-secondary">Blog Management</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Create and manage blog posts with categories, tags, and templates</p>
        </div>
        <a href="{{ route(($routePrefix ?? 'admin') . '.blog.create') }}" class="bg-[#FF8FA3] hover:bg-[#FF7A91] text-white px-6 py-2.5 rounded-xl font-semibold transition-colors flex items-center gap-2 shadow-md hover:shadow-lg">
            <i class="ri-file-add-line text-lg"></i>
            <span>Create Post</span>
        </a>
    </div>

    {{-- Search --}}
    <div class="mb-6">
        <form action="{{ route(($routePrefix ?? 'admin') . '.blog.index') }}" method="GET" class="flex gap-2">
            <input
                type="text"
                name="search"
                value="{{ $search }}"
                placeholder="Search blog posts..."
                class="flex-1 rounded-md border border-gray-300 bg-light px-4 py-2 text-sm focus:border-primary focus:outline-none focus:ring-primary dark:border-gray-600 dark:bg-gray-700"
            >
            <button type="submit" class="rounded-md bg-gray-600 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                Search
            </button>
            @if ($search)
                <a href="{{ route(($routePrefix ?? 'admin') . '.blog.index') }}" class="rounded-md bg-gray-500 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-600">
                    Clear
                </a>
            @endif
        </form>
    </div>

    {{-- Blog Posts Table --}}
    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Image</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Slug</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Template</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Author</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                    @forelse ($posts as $blog)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4">
                                @if($blog->featured_image)
                                    <img src="{{ asset($blog->featured_image) }}" alt="{{ $blog->title }}" class="h-16 w-16 object-cover rounded-md border border-gray-200 dark:border-gray-600">
                                @else
                                    <div class="h-16 w-16 rounded-md border border-gray-200 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                        <i class="ri-image-line text-2xl text-gray-400"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ Str::limit($blog->title, 50) }}
                                    @if ($blog->is_featured)
                                        <span class="ml-2 inline-flex items-center rounded bg-amber-100 px-2 py-0.5 text-xs font-medium text-amber-800 dark:bg-amber-900/40 dark:text-amber-300">Featured</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                <code class="text-xs bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">{{ $blog->slug }}</code>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                <span class="inline-flex rounded-full bg-indigo-100 px-2 py-1 text-xs font-semibold text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-300">Template {{ $blog->template ?? 1 }}</span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                {{ $blog->author->name ?? 'N/A' }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                {{ $blog->created_at->format('M d, Y') }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 {{ $blog->is_published ? 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                    {{ $blog->is_published ? 'Published' : 'Draft' }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route(($routePrefix ?? 'admin') . '.blog.edit', $blog) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                        Edit
                                    </a>
                                    <form action="{{ route(($routePrefix ?? 'admin') . '.blog.destroy', $blog) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this blog post?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-10 text-center text-sm text-gray-500">
                                No blog posts found. <a href="{{ route(($routePrefix ?? 'admin') . '.blog.create') }}" class="font-semibold text-primary hover:underline">Create your first post</a>.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination Info and Links --}}
    <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="text-sm text-gray-600 dark:text-gray-400">
            Showing {{ $posts->firstItem() ?? 0 }} to {{ $posts->lastItem() ?? 0 }} of {{ $posts->total() }} results
        </div>
        @if ($posts->hasPages())
            <div class="flex items-center gap-2">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
@endsection

