@extends('layouts.admin')

@section('title', 'Photo Moderation - Admin Panel')
@section('page-title', 'Profile Picture Moderation')

@section('content')
    <div class="pt-[14px] pb-8">
        <h2 class="text-[#0A0A0A] text-[24px] font-medium font-['poppins']">Profile Picture Moderation</h2>
        <p class="text-[#717182] font-['poppins']">Review and approve user profile photos</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 gap-4 md:grid-cols-4 mb-8">
        <!-- Pending Review -->
        <div class="md:col-span-1 flex items-center justify-center">
            <div class="shadow-[0px_1px_2px_-1px_#0000001A,0px_1px_3px_0px_#0000001A] border border-gray-100 w-full max-w-sm rounded-[16px] p-6 bg-white">
                <div class="flex justify-between items-start">
                    <div class="space-y-8">
                        <p class="text-[13px] font-normal text-[#717182] font-sans tracking-normal leading-normal">Pending Reviews</p>
                        <div class="font-['arial'] text-[24px] font-normal text-black font-sans leading-none">{{ $stats['pending'] }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Flagged Photos -->
        <div class="md:col-span-1 flex items-center justify-center">
            <div class="shadow-[0px_1px_2px_-1px_#0000001A,0px_1px_3px_0px_#0000001A] border border-gray-100 w-full max-w-sm rounded-[16px] p-6 bg-white">
                <div class="flex justify-between items-start">
                    <div class="space-y-8">
                        <p class="text-[13px] font-normal text-[#717182] font-sans tracking-normal leading-normal">Flagged Photos</p>
                        <div class="font-['arial'] text-[24px] font-normal text-red-400 font-sans leading-none">{{ $stats['flagged'] }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Approved -->
        <div class="md:col-span-1 flex items-center justify-center">
            <div class="shadow-[0px_1px_2px_-1px_#0000001A,0px_1px_3px_0px_#0000001A] border border-gray-100 w-full max-w-sm rounded-[16px] p-6 bg-white">
                <div class="flex justify-between items-start">
                    <div class="space-y-8">
                        <p class="text-[13px] font-normal text-[#717182] font-sans tracking-normal leading-normal">Approved</p>
                        <div class="font-['arial'] text-[24px] font-normal text-green-400 font-sans leading-none">{{ $stats['approved'] }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rejected -->
        <div class="md:col-span-1 flex items-center justify-center">
            <div class="shadow-[0px_1px_2px_-1px_#0000001A,0px_1px_3px_0px_#0000001A] border border-gray-100 w-full max-w-sm rounded-[16px] p-6 bg-white">
                <div class="flex justify-between items-start">
                    <div class="space-y-8">
                        <p class="text-[13px] font-normal text-[#717182] font-sans tracking-normal leading-normal">Rejected</p>
                        <div class="font-['arial'] text-[24px] font-normal text-red-600 font-sans leading-none">{{ $stats['rejected'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="flex gap-2 mt-8">
        <a href="{{ route('admin.photo-moderation.index', ['status' => 'pending']) }}" class="py-2 px-6 rounded-xl {{ $currentStatus === 'pending' ? 'bg-[#FF8FA3] text-white' : 'border border-[#0000001A]' }}">
            Pending({{ $stats['pending'] }})
        </a>
        <a href="{{ route('admin.photo-moderation.index', ['status' => 'flagged']) }}" class="px-6 py-2 rounded-xl {{ $currentStatus === 'flagged' ? 'bg-[#FF8FA3] text-white' : 'border border-[#0000001A]' }}">
            Flagged({{ $stats['flagged'] }})
        </a>
        <a href="{{ route('admin.photo-moderation.index', ['status' => 'approved']) }}" class="px-6 py-2 rounded-xl {{ $currentStatus === 'approved' ? 'bg-[#FF8FA3] text-white' : 'border border-[#0000001A]' }}">
            Approved
        </a>
        <a href="{{ route('admin.photo-moderation.index', ['status' => 'rejected']) }}" class="px-6 py-2 rounded-xl {{ $currentStatus === 'rejected' ? 'bg-[#FF8FA3] text-white' : 'border border-[#0000001A]' }}">
            Rejected
        </a>
        <a href="{{ route('admin.photo-moderation.index', ['status' => 'all']) }}" class="px-4 py-2 rounded-xl {{ $currentStatus === 'all' ? 'bg-[#FF8FA3] text-white' : 'border border-[#0000001A]' }}">
            All
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-4 gap-4 py-5 px-0">
        @forelse($photos as $photo)
            @php
                $user = $photo['user'] ?? null;
            @endphp
            <!-- Photo Card -->
            <div class="bg-white rounded-2xl shadow-lg border overflow-hidden">
                <!-- Image -->
                <img src="{{ $photo['image'] }}" class="w-full object-cover h-64 rounded-2xl p-4" alt="Profile Photo" />

                <div class="flex items-center gap-3 p-4">
                    <div class="flex flex-col">
                        <h2 class="font-semibold text-lg">{{ $user ? $user->name : 'Unknown User' }}</h2>
                        <p class="text-gray-500 text-sm">{{ $user ? $user->email : 'N/A' }}</p>
                        <p class="text-gray-500 text-sm">Uploaded: {{ $photo['uploaded_at'] ? \Carbon\Carbon::parse($photo['uploaded_at'])->format('Y-m-d') : 'N/A' }}</p>
                    </div>

                    <span class="ml-auto px-3 py-1 rounded-full text-xs bg-yellow-100 text-yellow-700">
                        {{ ucfirst($photo['status']) }}
                    </span>
                </div>

                <!-- Buttons -->
                <div class="flex gap-4 px-5 pb-5 mt-3">
                    <form action="{{ route('admin.photo-moderation.approve', $photo['id']) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center gap-2 bg-green-600 text-white py-2 rounded-xl hover:bg-green-700 transition">
                            ✔ Approve
                        </button>
                    </form>
                    <form action="{{ route('admin.photo-moderation.reject', $photo['id']) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center gap-2 border border-red-500 text-red-500 py-2 rounded-xl hover:bg-red-50 transition">
                            ✖ Reject
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="md:col-span-4 text-center py-12">
                <p class="text-gray-500 text-lg">No photos found for this status.</p>
            </div>
        @endforelse
    </div>
@endsection

