<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Editor Panel - User Management - ' . config('app.name'))</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    
    <!-- Remix Icons CDN -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.7.0/fonts/remixicon.css" rel="stylesheet" />
    <style>
        /* Ensure RemixIcon icons display correctly */
        [class^="ri-"], [class*=" ri-"] {
            font-family: 'remixicon' !important;
            font-style: normal;
            font-weight: normal;
            font-variant: normal;
            text-transform: none;
            line-height: 1;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            display: inline-block;
        }
    </style>
    
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
            <a href="{{ route('editor.users.index') }}">
                <img src="{{ asset('admin-assets/Logo.png') }}" class="md:hidden block w-[20px]" alt="Logo" />
                <img src="{{ asset('admin-assets/logo1.png') }}" class="md:w-[170px] md:block hidden" width="170" alt="Logo" />
            </a>
            
            <!-- Navigation Tabs -->
            <div class="flex gap-3 md:w-[90%] w-[50px] flex-col">
                <!-- User Management -->
                <a href="{{ route('editor.users.index') }}">
                    <div class="md:w-[229px] w-full rounded-2xl flex md:justify-start justify-center hover:shadow-md hover:text-white py-2 md:py-3 cursor-pointer md:rounded-3xl md:px-4 transition-all hover:bg-[#FF8FA3] {{ request()->routeIs('editor.users.*') ? 'bg-[#FF8FA3] text-white' : '' }}">
                        <div class="flex gap-3 hover:text-white hover:brightness-[100] items-center">
                            <img src="{{ asset('admin-assets/Users.png') }}" width="20" alt="Users" />
                            <h2 class="text-[17px] md:block hidden font-normal">User Management</h2>
                        </div>
                    </div>
                </a>

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}" class="mt-auto">
                    @csrf
                    <button type="submit" class="md:w-[229px] w-full rounded-2xl flex md:justify-start justify-center hover:shadow-md hover:text-white py-2 md:py-3 cursor-pointer md:rounded-3xl md:px-4 transition-all hover:bg-red-500">
                        <div class="flex gap-3 hover:text-white items-center">
                            <i class="ri-logout-box-line text-[20px]"></i>
                            <h2 class="text-[17px] md:block hidden font-normal">Logout</h2>
                        </div>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 bg-gray-50 dark:bg-gray-900 min-h-screen">
            <!-- Header -->
            <header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Editor Panel</h1>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">User Management</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-sm text-gray-600 dark:text-gray-400">
                            Logged in as: <strong class="text-gray-900 dark:text-white">{{ Auth::user()->name ?? Auth::user()->username }}</strong>
                        </span>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <div class="p-6">
                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    @stack('scripts')
</body>
</html>

