@extends('layouts.admin')

@section('title', 'Dashboard - Admin Panel')
@section('page-title', 'Admin Dashboard')

@section('content')
    <div class="pt-[14px] pb-8">
        <h2 class="text-[#0A0A0A] text-[24px] font-medium font-['poppins']">Dashboard Overview</h2>
        <p class="text-[#717182] font-['poppins']">Welcome back! Here's what's happening today.</p>
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
        <!-- Total Users -->
        <div class="md:col-span-1 flex items-center justify-center">
            <div class="shadow-[0px_1px_2px_-1px_#0000001A,0px_1px_3px_0px_#0000001A] border border-gray-100 w-full max-w-sm rounded-[16px] p-6 bg-white">
                <div class="flex justify-between items-start">
                    <div class="space-y-2">
                        <p class="text-[13px] font-normal text-[#717182] font-sans tracking-normal leading-normal">Total Users</p>
                        <div class="font-['arial'] text-[28px] font-normal text-black font-sans leading-none">{{ number_format($stats['total_users']) }}</div>
                    </div>

                    <div class="size-[48px] rounded-full flex items-center justify-center bg-[#FCEBEB] text-[#E60076]">
                        <i class="ri-group-line text-lg"></i>
                    </div>
                </div>

                <div class="mt-8">
                    <p class="text-[13px] font-normal text-[#22C55E] font-sans tracking-normal leading-normal">+12% from last month</p>
                </div>
            </div>
        </div>

        <!-- Active Users -->
        <div class="md:col-span-1 flex items-center justify-center">
            <div class="shadow-[0px_1px_2px_-1px_#0000001A,0px_1px_3px_0px_#0000001A] border border-gray-100 w-full max-w-sm rounded-[16px] p-6 bg-white">
                <div class="flex justify-between items-start">
                    <div class="space-y-2">
                        <p class="text-[13px] font-normal text-[#717182] font-sans tracking-normal leading-normal">Active Users</p>
                        <div class="font-['arial'] text-[28px] font-normal text-black font-sans leading-none">{{ number_format($stats['active_users']) }}</div>
                    </div>

                    <div class="size-[48px] rounded-full flex items-center justify-center bg-[#FCEBEB] text-[#E60076]">
                        <i class="ri-group-line text-lg"></i>
                    </div>
                </div>

                <div class="mt-8">
                    <p class="text-[13px] font-normal text-[#22C55E] font-sans tracking-normal leading-normal">+8% from last week</p>
                </div>
            </div>
        </div>

        <!-- Pending Verifications -->
        <div class="md:col-span-1 flex items-center justify-center">
            <div class="shadow-[0px_1px_2px_-1px_#0000001A,0px_1px_3px_0px_#0000001A] border border-gray-100 w-full max-w-sm rounded-[16px] p-6 bg-white">
                <div class="flex justify-between items-start">
                    <div class="space-y-2">
                        <p class="text-[13px] font-normal text-[#717182] font-sans tracking-normal leading-normal">Pending Verifications</p>
                        <div class="font-['arial'] text-[28px] font-normal text-black font-sans leading-none">{{ $stats['pending_verifications'] }}</div>
                    </div>

                    <div class="size-[48px] rounded-full flex items-center justify-center bg-[#FCEBEB] text-[#E60076]">
                        <i class="ri-group-line text-lg"></i>
                    </div>
                </div>

                <div class="mt-8">
                    <p class="text-[13px] font-normal text-[#22C55E] font-sans tracking-normal leading-normal">+8% from last week</p>
                </div>
            </div>
        </div>

        <!-- Reported Profiles -->
        <div class="md:col-span-1 flex items-center justify-center">
            <div class="shadow-[0px_1px_2px_-1px_#0000001A,0px_1px_3px_0px_#0000001A] border border-gray-100 w-full max-w-sm rounded-[16px] p-6 bg-white">
                <div class="flex justify-between items-start">
                    <div class="space-y-2">
                        <p class="text-[13px] font-normal text-[#717182] font-sans tracking-normal leading-normal">Reported Profiles</p>
                        <div class="font-['arial'] text-[28px] font-normal text-black font-sans leading-none">{{ $stats['reported_profiles'] }}</div>
                    </div>

                    <div class="size-[48px] rounded-full flex items-center justify-center bg-[#FCEBEB] text-[#E60076]">
                        <i class="ri-group-line text-lg"></i>
                    </div>
                </div>

                <div class="mt-8">
                    <p class="text-sm text-[#22C55E] font-sans tracking-normal leading-normal text-[#D4183D]">23 new today</p>
                </div>
            </div>
        </div>

        <!-- New Registrations Today -->
        <div class="md:col-span-2 flex items-center justify-center">
            <div class="shadow-[0px_1px_2px_-1px_#0000001A,0px_1px_3px_0px_#0000001A] border border-gray-100 w-full rounded-[16px] p-6 bg-white">
                <div class="flex justify-between items-start">
                    <div class="space-y-2">
                        <p class="text-[13px] font-normal text-[#717182] font-sans tracking-normal leading-normal">New Registrations Today</p>
                        <div class="font-['arial'] text-[28px] font-normal text-black font-sans leading-none">{{ $stats['new_registrations_today'] }}</div>
                    </div>

                    <div class="size-[48px] rounded-full flex items-center justify-center bg-[#FCEBEB] text-[#E60076]">
                        <i class="ri-group-line text-lg"></i>
                    </div>
                </div>

                <div class="mt-8">
                    <p class="text-[13px] font-normal text-[#22C55E] font-sans tracking-normal leading-normal">+35% from last month</p>
                </div>
            </div>
        </div>

        <!-- Growth Rate -->
        <div class="md:col-span-2 flex items-center justify-center">
            <div class="shadow-[0px_1px_2px_-1px_#0000001A,0px_1px_3px_0px_#0000001A] border border-gray-100 w-full rounded-[16px] p-6 bg-white">
                <div class="flex justify-between items-start">
                    <div class="space-y-2">
                        <p class="text-[13px] font-normal text-[#717182] font-sans tracking-normal leading-normal">Growth Rate</p>
                        <div class="font-['arial'] text-[28px] font-normal text-black font-sans leading-none">{{ $stats['growth_rate'] }}%</div>
                    </div>

                    <div class="size-[48px] rounded-full flex items-center justify-center bg-[#FCEBEB] text-[#E60076]">
                        <i class="ri-group-line text-lg"></i>
                    </div>
                </div>

                <div class="mt-8">
                    <p class="text-[13px] font-normal text-[#22C55E] font-sans tracking-normal leading-normal">+35% from last month</p>
                </div>
            </div>
        </div>

        <!-- User Growth -->
        <div class="md:col-span-2 flex items-center justify-center">
            <div class="shadow-[0px_1px_2px_-1px_#0000001A,0px_1px_3px_0px_#0000001A] border border-gray-100 w-full rounded-[16px] py-6 bg-white">
                <h2 class="px-6 text-[#0A0A0A] font-medium font-['poppins'] mb-4">User Growth</h2>
                <img src="{{ asset('admin-assets/chart.png') }}" width="525" height="213" class="w-full h-auto" alt="User Growth Chart" />
            </div>
        </div>

        <!-- Active Sessions -->
        <div class="md:col-span-2 flex items-center justify-center">
            <div class="shadow-[0px_1px_2px_-1px_#0000001A,0px_1px_3px_0px_#0000001A] border border-gray-100 w-full rounded-[16px] py-6 bg-white">
                <h2 class="px-6 text-[#0A0A0A] font-medium font-['poppins'] mb-4">Active Sessions (This Week)</h2>
                <img src="{{ asset('admin-assets/chartbar.png') }}" width="525" height="213" class="w-full h-auto" alt="Active Sessions Chart" />
            </div>
        </div>

        <!-- Quick Action -->
        <div class="mb-[85px] md:col-span-4 flex items-center justify-center">
            <div class="shadow-[0px_1px_2px_-1px_#0000001A,0px_1px_3px_0px_#0000001A] border border-gray-100 w-full rounded-[16px] p-6 bg-white">
                <h2 class="text-[#0A0A0A] font-medium font-['poppins'] mb-4">Quick Action</h2>

                <div class="flex gap-4 md:flex-row flex-col">
                    <a href="#" class="w-full flex flex-col gap-[6px] justify-between items-center border-t border-solid rounded-[12px] border-[1px] border-[#0000001A] bg-[#FFF2F2] p-4 hover:shadow-md transition-all">
                        <i class="ri-error-warning-line text-lg text-[#FF8FA3]"></i>
                        <p class="text-[#0A0A0A] font-['poppins']">Verify Users</p>
                    </a>

                    <a href="{{ route('admin.users.create') }}" class="border-t border-solid w-full flex flex-col gap-[6px] justify-between items-center rounded-[12px] border-[1px] border-[#0000001A] bg-white p-4 hover:shadow-md transition-all">
                        <i class="ri-user-add-line text-lg text-[#FF8FA3]"></i>
                        <p class="text-[#0A0A0A] font-['poppins']">Add New User</p>
                    </a>

                    <a href="{{ route('admin.users.index') }}" class="border-t border-solid w-full flex flex-col gap-[6px] justify-between items-center rounded-[12px] border-[1px] border-[#0000001A] bg-white p-4 hover:shadow-md transition-all">
                        <i class="ri-user-shared-line text-lg text-[#FF8FA3]"></i>
                        <p class="text-[#0A0A0A] font-['poppins']">View Details</p>
                    </a>

                    <a href="#" class="border-t border-solid w-full flex flex-col gap-[6px] justify-between items-center rounded-[12px] border-[1px] border-[#0000001A] bg-white p-4 hover:shadow-md transition-all">
                        <i class="ri-bar-chart-box-line text-lg text-[#FF8FA3]"></i>
                        <p class="text-[#0A0A0A] font-['poppins']">View Reports</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
