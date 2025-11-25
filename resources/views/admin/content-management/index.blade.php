@extends('layouts.admin')

@section('title', 'Content Management - Admin Panel')
@section('page-title', 'Content & Text Management')

@section('content')
    <div class="pt-[14px] pb-8">
        <h2 class="text-[#0A0A0A] text-[24px] font-medium font-['poppins']">Content & Text Management</h2>
        <p class="text-[#717182] font-['poppins']">Edit text content displayed throughout the application.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($contentItems as $item)
            @if($item['type'] === 'landing_page')
                <!-- Landing Page Text Card -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                    <div class="flex items-start gap-4">
                        <!-- Document Icon -->
                        <div class="w-12 h-12 rounded-full bg-[#FFF5F7] flex items-center justify-center flex-shrink-0">
                            <i class="ri-file-text-line text-[#FF8FA3] text-xl"></i>
                        </div>
                        
                        <div class="flex-1">
                            <!-- Title and Subtitle -->
                            <h3 class="text-lg font-semibold text-[#0A0A0A] mb-1">{{ $item['title'] }}</h3>
                            <p class="text-sm text-[#717182] mb-4">{{ $item['subtitle'] }}</p>
                            
                            <!-- Content Preview -->
                            <div class="bg-gray-50 rounded-lg p-4 mb-4">
                                <h4 class="font-semibold text-[#0A0A0A] mb-2">{{ $item['headline'] }}</h4>
                                <p class="text-sm text-[#717182]">{{ $item['body'] }}</p>
                            </div>
                            
                            <!-- Last Updated -->
                            <p class="text-xs text-[#717182] mb-4">Last updated: {{ $item['last_updated'] }}</p>
                            
                            <!-- Action Buttons -->
                            <div class="flex gap-3">
                                <a href="{{ route('admin.content-management.preview', $item['id']) }}" class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-[#0A0A0A] hover:bg-gray-50 transition-colors">
                                    <i class="ri-eye-line"></i>
                                    Preview
                                </a>
                                <a href="{{ route('admin.content-management.edit', $item['id']) }}" class="flex items-center gap-2 px-4 py-2 bg-[#FF8FA3] rounded-lg text-sm font-medium text-white hover:bg-[#FF7A91] transition-colors">
                                    <i class="ri-file-edit-line"></i>
                                    Edit
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($item['type'] === 'banner')
                <!-- Home Banner Text Card -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                    <div class="flex items-start gap-4">
                        <!-- Document Icon -->
                        <div class="w-12 h-12 rounded-full bg-[#FFF5F7] flex items-center justify-center flex-shrink-0">
                            <i class="ri-file-text-line text-[#FF8FA3] text-xl"></i>
                        </div>
                        
                        <div class="flex-1">
                            <!-- Title and Subtitle -->
                            <h3 class="text-lg font-semibold text-[#0A0A0A] mb-1">{{ $item['title'] }}</h3>
                            <p class="text-sm text-[#717182] mb-4">{{ $item['subtitle'] }}</p>
                            
                            <!-- Content Preview -->
                            <div class="bg-gray-50 rounded-lg p-4 mb-4 flex items-center gap-2">
                                <i class="ri-sparkling-line text-[#FF8FA3] text-lg"></i>
                                <p class="text-sm text-[#0A0A0A]">{{ $item['message'] }}</p>
                            </div>
                            
                            <!-- Last Updated -->
                            <p class="text-xs text-[#717182] mb-4">Last updated: {{ $item['last_updated'] }}</p>
                            
                            <!-- Action Buttons -->
                            <div class="flex gap-3">
                                <a href="{{ route('admin.content-management.preview', $item['id']) }}" class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-[#0A0A0A] hover:bg-gray-50 transition-colors">
                                    <i class="ri-eye-line"></i>
                                    Preview
                                </a>
                                <a href="{{ route('admin.content-management.edit', $item['id']) }}" class="flex items-center gap-2 px-4 py-2 bg-[#FF8FA3] rounded-lg text-sm font-medium text-white hover:bg-[#FF7A91] transition-colors">
                                    <i class="ri-file-edit-line"></i>
                                    Edit
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
@endsection

