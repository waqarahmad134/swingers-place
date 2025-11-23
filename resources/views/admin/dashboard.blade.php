@extends('layouts.admin')

@section('title', 'Dashboard - Admin Panel')

@section('content')
    <h1 class="mb-6 text-3xl font-extrabold text-secondary">Dashboard</h1>
    
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        {{-- Total Users Card --}}
        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm text-gray-900 transition-shadow hover:shadow-md dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Users</p>
                    <p class="mt-2 text-3xl font-extrabold text-dark dark:text-white">
                        {{ number_format($stats['total_users']) }}
                    </p>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Registered users</p>
                </div>
                <div class="rounded-full bg-purple-100 p-3 dark:bg-purple-900/30">
                    <svg class="h-8 w-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="mt-8">
        <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-gray-100">Quick Actions</h2>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <a href="{{ route('admin.users.create') }}" class="flex items-center gap-3 rounded-lg border border-gray-200 bg-white p-4 text-gray-900 transition-all hover:border-primary hover:shadow-md dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                <svg class="h-6 w-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span class="font-semibold">Add User</span>
            </a>
            
            <a href="{{ route('admin.media.index') }}" class="flex items-center gap-3 rounded-lg border border-gray-200 bg-white p-4 text-gray-900 transition-all hover:border-primary hover:shadow-md dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                <svg class="h-6 w-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span class="font-semibold">Media Library</span>
            </a>
            
            <a href="{{ route('admin.database.index') }}" class="flex items-center gap-3 rounded-lg border border-gray-200 bg-white p-4 text-gray-900 transition-all hover:border-primary hover:shadow-md dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                <svg class="h-6 w-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/>
                </svg>
                <span class="font-semibold">Database</span>
            </a>
            
            <a href="{{ route('admin.settings.general') }}" class="flex items-center gap-3 rounded-lg border border-gray-200 bg-white p-4 text-gray-900 transition-all hover:border-primary hover:shadow-md dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                <svg class="h-6 w-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span class="font-semibold">Settings</span>
            </a>
        </div>
    </div>
@endsection
