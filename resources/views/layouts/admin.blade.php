<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Panel - ' . config('app.name'))</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    
    <!-- Remix Icons CDN -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.7.0/fonts/remixicon.css" rel="stylesheet" />
    
    <!-- Google Font: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet" />
    
    <style>
        .poppins {
            font-family: "Poppins";
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            display: flex;
        }
    </style>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    @stack('head')
</head>
<body>
    <div class="flex gap-4 w-full">
        <!-- Sidebar -->
        <aside class="min-h-screen md:min-w-[290px] min-w-[70px] items-center gap-5 flex flex-col py-14 bg-[#FFF5F7]">
            <!-- Logo -->
            <a href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('admin-assets/Logo.png') }}" class="md:hidden block w-[20px]" alt="Logo" />
                <img src="{{ asset('admin-assets/logo1.png') }}" class="md:w-[170px] md:block hidden" width="170" alt="Logo" />
            </a>
            
            <!-- Navigation Tabs -->
            <div class="flex gap-3 md:w-[90%] w-[50px] flex-col">
                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}">
                    <div class="md:w-[229px] w-full rounded-2xl flex md:justify-start justify-center hover:shadow-md hover:text-white py-1 md:py-3 cursor-pointer md:rounded-3xl md:px-4 transition-all hover:bg-[#FF8FA3] {{ request()->routeIs('admin.dashboard') ? 'bg-[#FF8FA3] text-white' : '' }}">
                        <div class="flex items-center gap-3">
                            <i class="ri-dashboard-line text-[20px]"></i>
                            <h2 class="text-[17px] md:block hidden font-normal">Dashboard</h2>
                        </div>
                    </div>
                </a>

                <!-- User Management -->
                <a href="{{ route('admin.users.index') }}">
                    <div class="md:w-[229px] w-full rounded-2xl flex md:justify-start justify-center hover:shadow-md hover:text-white py-2 md:py-3 cursor-pointer md:rounded-3xl md:px-4 transition-all hover:bg-[#FF8FA3] {{ request()->routeIs('admin.users.*') ? 'bg-[#FF8FA3] text-white' : '' }}">
                        <div class="flex gap-3 hover:text-white hover:brightness-[100] items-center">
                            <img src="{{ asset('admin-assets/Users.png') }}" width="20" alt="Users" />
                            <h2 class="text-[17px] md:block hidden font-normal">User Management</h2>
                        </div>
                    </div>
                </a>

                <!-- Verification Center -->
                <a href="{{ route('admin.verification.index') }}">
                    <div class="md:w-[229px] w-full rounded-2xl flex md:justify-start justify-center hover:shadow-md hover:text-white py-2 md:py-3 cursor-pointer md:rounded-3xl md:px-4 transition-all hover:bg-[#FF8FA3] {{ request()->routeIs('admin.verification.*') ? 'bg-[#FF8FA3] text-white' : '' }}">
                        <div class="flex gap-3 hover:text-white hover:brightness-[100] items-center">
                            <img src="{{ asset('admin-assets/verification.png') }}" width="20" alt="Verification" />
                            <h2 class="text-[17px] md:block hidden font-normal">Verification Center</h2>
                        </div>
                    </div>
                </a>

                <!-- Reported Users -->
                <a href="{{ route('admin.reported-users.index') }}">
                    <div class="md:w-[229px] w-full rounded-2xl flex md:justify-start justify-center hover:shadow-md hover:text-white py-2 md:py-3 cursor-pointer md:rounded-3xl md:px-4 transition-all hover:bg-[#FF8FA3] group {{ request()->routeIs('admin.reported-users.*') ? 'bg-[#FF8FA3] text-white' : '' }}">
                        <div class="flex gap-3 hover:text-white items-center">
                            <img src="{{ asset('admin-assets/report.png') }}" width="20" alt="Report" class="group-hover:brightness-100" />
                            <h2 class="text-[17px] md:block hidden font-normal">Reported Users</h2>
                        </div>
                    </div>
                </a>

                <!-- Registration Control -->
                <a href="{{ route('admin.registration-control.index') }}">
                    <div class="md:w-[229px] w-full rounded-2xl flex md:justify-start justify-center hover:shadow-md hover:text-white py-2 md:py-3 cursor-pointer md:rounded-3xl md:px-4 transition-all hover:bg-[#FF8FA3] {{ request()->routeIs('admin.registration-control.*') ? 'bg-[#FF8FA3] text-white' : '' }}">
                        <div class="flex gap-3 hover:text-white hover:brightness-[100] items-center">
                            <img src="{{ asset('admin-assets/register.png') }}" width="20" alt="Register" />
                            <h2 class="text-[17px] md:block hidden font-normal">Registration Control</h2>
                        </div>
                    </div>
                </a>

                <!-- Content Management -->
                <a href="{{ route('admin.content-management.index') }}">
                    <div class="md:w-[229px] w-full rounded-2xl flex md:justify-start justify-center hover:shadow-md hover:text-white py-2 md:py-3 cursor-pointer md:rounded-3xl md:px-4 transition-all hover:bg-[#FF8FA3] {{ request()->routeIs('admin.content-management.*') ? 'bg-[#FF8FA3] text-white' : '' }}">
                        <div class="flex gap-3 hover:text-white hover:brightness-[100] items-center">
                            <img src="{{ asset('admin-assets/content.png') }}" width="20" alt="Content" />
                            <h2 class="text-[17px] md:block hidden font-normal">Content Management</h2>
                        </div>
                    </div>
                </a>

                <!-- Photo Moderation -->
                <a href="{{ route('admin.photo-moderation.index') }}">
                    <div class="md:w-[229px] w-full rounded-2xl flex md:justify-start justify-center hover:shadow-md hover:text-white py-2 md:py-3 cursor-pointer md:rounded-3xl md:px-4 transition-all hover:bg-[#FF8FA3] {{ request()->routeIs('admin.photo-moderation.*') ? 'bg-[#FF8FA3] text-white' : '' }}">
                        <div class="flex gap-3 hover:text-white hover:brightness-[100] items-center">
                            <img src="{{ asset('admin-assets/photo.png') }}" width="20" alt="Photo" />
                            <h2 class="text-[17px] md:block hidden font-normal">Photo Moderation</h2>
                        </div>
                    </div>
                </a>

                <!-- Settings -->
                <a href="{{ route('admin.settings.general') }}">
                    <div class="md:w-[229px] w-full rounded-2xl flex md:justify-start justify-center hover:shadow-md hover:text-white py-2 md:py-3 cursor-pointer md:rounded-3xl md:px-4 transition-all hover:bg-[#FF8FA3] {{ request()->routeIs('admin.settings.*') ? 'bg-[#FF8FA3] text-white' : '' }}">
                        <div class="flex gap-3 hover:text-white hover:brightness-[100] items-center">
                            <img src="{{ asset('admin-assets/settings.png') }}" width="20" alt="Settings" />
                            <h2 class="text-[17px] md:block hidden font-normal">Settings</h2>
                        </div>
                    </div>
                </a>
            </div>
        </aside>

        <main class="min-h-screen w-full pe-6">
            <!-- Header -->
            <header class="p-6 h-[64px] justify-between flex items-center border border-gray-200 shadow-[0px_1px_2px_-1px_#0000001A,0px_1px_3px_0px_#0000001A]">
                <h2 class="text-[#0A0A0A] font-medium font-['poppins']">@yield('page-title', 'Admin Dashboard')</h2>

                <div class="gap-2 md:flex hidden">
                    <img class="size-9 cursor-pointer" src="{{ asset('admin-assets/bell.png') }}" width="36" height="36" alt="bell" />

                    <div class="pe-4 border-l border-solid ps-4 border-[#0000001A] flex gap-2 items-center">
                        @if(Auth::user()->profile_image)
                            <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" width="40" height="40" alt="user" class="rounded-full" />
                        @else
                            <img src="{{ asset('admin-assets/user.png') }}" width="40" height="40" alt="user" />
                        @endif
                        <div>
                            <h2 class="text-[#0A0A0A] text-sm">{{ Auth::user()->name ?? 'Admin User' }}</h2>
                            <p class="text-[#717182] text-xs">{{ Auth::user()->email }}</p>
                        </div>
                    </div>

                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="cursor-pointer">
                            <img src="{{ asset('admin-assets/logout.png') }}" class="size-9" width="36" height="36" alt="logout" />
                        </button>
                    </form>
                </div>
            </header>

            <!-- Main Content -->
            <div>
                @if (session('success'))
                    <div class="mt-6 mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-700 dark:border-green-800 dark:bg-green-900/40 dark:text-green-300">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if ($errors->any())
                    <div class="mt-6 mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-800 dark:bg-red-900/40 dark:text-red-300">
                        <p class="font-semibold">Please fix the following errors:</p>
                        <ul class="mt-2 list-disc space-y-1 pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                @yield('content')
            </div>
        </main>
    </div>
    
    @include('components.toast')
    
    @stack('modals')
    
    @stack('scripts')
</body>
</html>
