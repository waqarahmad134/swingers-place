@extends('layouts.admin')

@section('title', 'Create Category - Admin Panel')

@section('content')
    <div class="mb-6">
        <a href="{{ route(($routePrefix ?? 'admin') . '.categories.index') }}" class="text-sm text-gray-600 hover:text-primary dark:text-gray-400">
            ← Back to Categories
        </a>
    </div>

    <h1 class="mb-6 text-3xl font-extrabold text-secondary">Create New Category</h1>

    <div class="max-w-2xl rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <form action="{{ route(($routePrefix ?? 'admin') . '.categories.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name *</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required class="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-primary focus:outline-none focus:ring-primary dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Slug</label>
                <input type="text" id="slug" name="slug" value="{{ old('slug') }}" placeholder="auto-generated-from-name" class="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-primary focus:outline-none focus:ring-primary dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                @error('slug')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Leave empty to auto-generate from name</p>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                <textarea id="description" name="description" rows="3" class="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-primary focus:outline-none focus:ring-primary dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('description') }}</textarea>
                @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-gray-200 pt-6 dark:border-gray-700">
                <a href="{{ route(($routePrefix ?? 'admin') . '.categories.index') }}" class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm transition-colors hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                    Cancel
                </a>
                <button type="submit" class="bg-[#FF8FA3] hover:bg-[#FF7A91] text-white px-6 py-2.5 rounded-xl font-semibold transition-colors flex items-center gap-2 shadow-md hover:shadow-lg">
                    <i class="ri-save-line text-lg"></i>
                    <span>Create Category</span>
                </button>
            </div>
        </form>
    </div>
@endsection

