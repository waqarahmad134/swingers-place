@extends('layouts.admin')

@section('title', 'Edit Content - Admin Panel')
@section('page-title', 'Edit Content')

@section('content')
    <div class="pt-[14px] pb-8">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-[#0A0A0A] text-[24px] font-medium font-['poppins']">Edit Content</h2>
                <p class="text-[#717182] font-['poppins']">Update the content for: {{ $section->section_name }}</p>
            </div>
            <a href="{{ route('admin.content-management.index') }}" 
               class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                ← Back to Content Management
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-700 dark:border-green-800 dark:bg-green-900/40 dark:text-green-300">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
        <form action="{{ route('admin.content-management.update', $section->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Section Name</label>
                <input type="text" value="{{ $section->section_name }}" readonly 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Section Key</label>
                <input type="text" value="{{ $section->section_key }}" readonly 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 text-xs">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Content <span class="text-red-600">*</span></label>
                @if($section->type === 'html')
                    <textarea name="content" rows="10" required
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FF8FA3] font-mono text-sm">{{ old('content', $section->content) }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">HTML content is allowed</p>
                @else
                    <textarea name="content" rows="5" required
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FF8FA3]">{{ old('content', $section->content) }}</textarea>
                @endif
            </div>

            <div class="flex items-center gap-3">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" 
                           {{ old('is_active', $section->is_active) ? 'checked' : '' }}
                           class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#FF8FA3]/30 rounded-full peer dark:bg-gray-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-[#FF8FA3]"></div>
                </label>
                <span class="text-sm font-medium text-gray-700">Active</span>
            </div>

            <div class="flex justify-end gap-4 pt-4">
                <a href="{{ route('admin.content-management.index') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-[#FF8FA3] text-white rounded-lg hover:bg-[#FF7A91] transition-colors font-semibold">
                    Update Content
                </button>
            </div>
        </form>
    </div>
@endsection

