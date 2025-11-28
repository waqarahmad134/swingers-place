@extends('layouts.admin')

@section('title', 'Choose Profile Type - Admin')

@section('content')
<div class="p-6">
    <div class="min-h-[60vh] flex items-center justify-center">
        <div class="w-full max-w-md">
            <!-- Card -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl p-8 md:p-10 border border-gray-100 dark:border-gray-700">
                <!-- Header -->
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-3">
                        Choose Profile Type
                    </h1>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">
                        Select the type of profile you want to create
                    </p>
                </div>

                <!-- Form -->
                <form method="POST" action="{{ route('admin.users.store-profile-type') }}" class="space-y-4">
                    @csrf

                    <!-- Normal Profile Button -->
                    <button type="submit" name="profile_type" value="normal" 
                            class="w-full py-4 px-6 bg-[linear-gradient(90deg,#9810FA_0%,#E60076_100%)] text-white rounded-full font-semibold flex items-center justify-center gap-3 hover:shadow-lg transition-all duration-200">
                        <i class="ri-user-3-line text-xl"></i>
                        <span>Normal Profile</span>
                    </button>

                    <!-- Business Profile Button -->
                    <button type="submit" name="profile_type" value="business"
                            class="w-full py-4 px-6 bg-pink-500 text-white rounded-full font-semibold flex items-center justify-center gap-3 hover:shadow-lg transition-all duration-200">
                        <i class="ri-briefcase-line text-xl"></i>
                        <span>Business Profile</span>
                    </button>
                </form>

                <!-- Back Link -->
                <div class="mt-6 text-center">
                    <a href="{{ route('admin.users.index') }}" class="text-sm font-semibold text-[#9810FA] hover:text-[#E60076] transition-colors">
                        ← Back to Users
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

