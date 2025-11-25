@extends('layouts.admin')

@section('title', 'User Verification - Admin Panel')
@section('page-title', 'User Verification Center')

@section('content')
    <div class="pt-[14px] pb-8">
        <h2 class="text-[#0A0A0A] text-[24px] font-medium font-['poppins']">User Verification Center</h2>
        <p class="text-[#717182] font-['poppins']">Review and approve user verification submissions</p>
    </div>

    <!-- Tabs -->
    <div class="flex gap-2">
        <a href="{{ route('admin.verification.index', ['status' => 'pending']) }}" class="py-2 px-6 rounded-xl {{ $currentStatus === 'pending' ? 'bg-[#FF8FA3] text-white' : 'border border-[#0000001A]' }}">
            Pending
        </a>
        <a href="{{ route('admin.verification.index', ['status' => 'approved']) }}" class="px-6 py-2 rounded-xl {{ $currentStatus === 'approved' ? 'bg-[#FF8FA3] text-white' : 'border border-[#0000001A]' }}">
            Approved
        </a>
        <a href="{{ route('admin.verification.index', ['status' => 'rejected']) }}" class="px-6 py-2 rounded-xl {{ $currentStatus === 'rejected' ? 'bg-[#FF8FA3] text-white' : 'border border-[#0000001A]' }}">
            Rejected
        </a>
        <a href="{{ route('admin.verification.index', ['status' => 'all']) }}" class="px-4 py-2 rounded-xl {{ $currentStatus === 'all' ? 'bg-[#FF8FA3] text-white' : 'border border-[#0000001A]' }}">
            All
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 py-5 px-0">
        @forelse($verifications as $verification)
            @php
                $user = $verification->user;
                $userInitials = $user ? strtoupper(substr($user->first_name ?? $user->name, 0, 1)) . strtoupper(substr($user->last_name ?? '', 0, 1)) : 'U';
                $typeLabels = [
                    'photo_id' => 'Photo ID',
                    'selfie' => 'Selfie Verification',
                    'document' => 'Document',
                ];
                $typeLabel = $typeLabels[$verification->type] ?? 'Photo ID';
                $statusColors = [
                    'pending' => 'bg-yellow-100 text-yellow-700',
                    'approved' => 'bg-green-100 text-green-700',
                    'rejected' => 'bg-red-100 text-red-700',
                ];
                $statusColor = $statusColors[$verification->status] ?? 'bg-yellow-100 text-yellow-700';
            @endphp
            <!-- Verification Card -->
            <div class="bg-white rounded-2xl shadow-lg border overflow-hidden">
                <!-- Header -->
                <div class="flex items-center gap-3 p-4 border-b">
                    @if($user && $user->profile_image)
                        <img src="{{ asset('storage/' . $user->profile_image) }}" alt="{{ $user->name }}" class="w-12 h-12 rounded-full object-cover" />
                    @else
                        <div class="w-12 h-12 rounded-full bg-gradient-to-b from-[#FF8FA3] to-[#FF6F61] flex items-center justify-center text-xl font-semibold text-white">
                            {{ $userInitials }}
                        </div>
                    @endif

                    <div class="flex flex-col">
                        <h2 class="font-semibold text-lg">{{ $user ? $user->name : 'Unknown User' }}</h2>
                        <p class="text-gray-500 text-sm">{{ $user ? $user->email : 'N/A' }}</p>
                    </div>

                    <span class="ml-auto px-3 py-1 rounded-full text-xs {{ $statusColor }}">
                        {{ ucfirst($verification->status) }}
                    </span>
                </div>

                <!-- Image -->
                @if($verification->document_image)
                    <img src="{{ asset('storage/' . $verification->document_image) }}" class="w-full object-cover h-64 rounded-2xl p-4" alt="Verification Document" />
                @else
                    <div class="w-full h-64 rounded-2xl p-4 bg-gray-100 flex items-center justify-center">
                        <p class="text-gray-400">No image uploaded</p>
                    </div>
                @endif

                <!-- Info -->
                <div class="px-5 py-3">
                    <p class="text-gray-500 text-sm">
                        <span class="font-semibold text-gray-700">Type:</span> {{ $typeLabel }}
                    </p>
                    <p class="mt-1 text-gray-500 text-sm">
                        <span class="font-semibold text-gray-700">Submitted:</span>
                        {{ $verification->created_at->format('Y-m-d') }}
                    </p>
                    @if($verification->reviewed_at)
                        <p class="mt-1 text-gray-500 text-sm">
                            <span class="font-semibold text-gray-700">Reviewed:</span>
                            {{ $verification->reviewed_at->format('Y-m-d') }}
                            @if($verification->reviewer)
                                by {{ $verification->reviewer->name }}
                            @endif
                        </p>
                    @endif
                </div>

                <!-- Buttons -->
                @if($verification->status === 'pending')
                    <div class="flex gap-4 px-5 pb-5 mt-3">
                        <form action="{{ route('admin.verification.approve', $verification) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full flex items-center justify-center gap-2 bg-green-600 text-white py-2 rounded-xl hover:bg-green-700 transition">
                                ✔ Approve
                            </button>
                        </form>
                        <form action="{{ route('admin.verification.reject', $verification) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full flex items-center justify-center gap-2 border border-red-500 text-red-500 py-2 rounded-xl hover:bg-red-50 transition">
                                ✖ Reject
                            </button>
                        </form>
                    </div>
                @else
                    <div class="px-5 pb-5 mt-3">
                        <p class="text-center text-sm text-gray-500 py-2">
                            This verification has been {{ $verification->status }}.
                        </p>
                    </div>
                @endif
            </div>
        @empty
            <div class="md:col-span-3 text-center py-12">
                <p class="text-gray-500 text-lg">No verification requests found for this status.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if ($verifications->hasPages())
        <div class="mt-6">
            {{ $verifications->links() }}
        </div>
    @endif
@endsection

