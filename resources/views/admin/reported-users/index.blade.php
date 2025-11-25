@extends('layouts.admin')

@section('title', 'Reported Users - Admin Panel')
@section('page-title', 'Reported Users')

@section('content')
    <div class="pt-[14px] pb-8">
        <h2 class="text-[#0A0A0A] text-[24px] font-medium font-['poppins']">Reported Users</h2>
        <p class="text-[#717182] font-['poppins']">Review and take action on user reports</p>
    </div>

    <!-- Filter Tabs -->
    <div class="flex gap-2 mb-6">
        <a href="{{ route('admin.reported-users.index', ['status' => 'pending']) }}" class="py-2 px-6 rounded-xl {{ $currentStatus === 'pending' ? 'bg-[#FF8FA3] text-white' : 'border border-[#0000001A]' }}">
            Pending({{ $pendingCount }})
        </a>
        <a href="{{ route('admin.reported-users.index', ['status' => 'resolved']) }}" class="px-6 py-2 rounded-xl {{ $currentStatus === 'resolved' ? 'bg-[#FF8FA3] text-white' : 'border border-[#0000001A]' }}">
            Resolved
        </a>
        <a href="{{ route('admin.reported-users.index', ['status' => 'all']) }}" class="px-4 py-2 rounded-xl {{ $currentStatus === 'all' ? 'bg-[#FF8FA3] text-white' : 'border border-[#0000001A]' }}">
            All
        </a>
    </div>

    <!-- Report Cards -->
    <div class="space-y-4">
        @forelse($reports as $report)
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                <div class="flex items-start gap-4">
                    <!-- Warning Icon -->
                    <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                        <i class="ri-error-warning-line text-red-600 text-xl"></i>
                    </div>
                    
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <h3 class="text-lg font-semibold text-[#0A0A0A]">{{ $report['type'] }}</h3>
                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $report['status'] === 'resolved' ? 'bg-gray-100 text-gray-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ ucfirst($report['status']) }}
                            </span>
                        </div>
                        
                        <p class="text-sm text-[#717182] mb-4">Reported on {{ $report['reported_date'] }}</p>
                        
                        <div class="space-y-2 mb-4">
                            <div>
                                <span class="text-sm font-medium text-[#0A0A0A]">Reported User:</span>
                                <span class="text-sm text-[#717182] ml-2">{{ $report['reported_user']['name'] }} ({{ $report['reported_user']['email'] }})</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-[#0A0A0A]">Reason:</span>
                                <span class="text-sm text-[#717182] ml-2">{{ $report['reason'] }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-[#0A0A0A]">Reported By:</span>
                                <span class="text-sm text-[#717182] ml-2">{{ $report['reported_by'] }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Review Button -->
                    <a href="{{ route('admin.reported-users.review', $report['id']) }}" class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-[#0A0A0A] hover:bg-gray-50 transition-colors">
                        <i class="ri-eye-line"></i>
                        Review
                    </a>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-12 text-center">
                <p class="text-gray-500 text-lg">No reports found for this status.</p>
            </div>
        @endforelse
    </div>
@endsection

