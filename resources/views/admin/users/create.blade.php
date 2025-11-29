@extends('layouts.admin')

@section('title', 'Create User - Admin Panel')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-600 hover:text-primary dark:text-gray-400">
            ← Back to Users
        </a>
    </div>

    <div class="min-h-[calc(100vh-200px)] flex items-center justify-center">
        <div class="w-full max-w-2xl">
            <!-- Register Card -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl p-8 md:p-10 border border-gray-100 dark:border-gray-700">
                <!-- Header -->
                <div class="text-center mb-8">
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-2">
                        Create New User
                    </h1>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">
                        Profile Type: <strong class="capitalize">{{ $profileType ?? 'normal' }}</strong>
                    </p>
                </div>

                <!-- Register Form -->
                <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-5">
                    @csrf

                    <!-- Hidden profile type -->
                    <input type="hidden" name="profile_type" value="{{ $profileType ?? 'normal' }}">

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Name / Nickname -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Name / Nickname
                            </label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name') }}"
                                autofocus
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all"
                                placeholder="Name or Nickname"
                            >
                            @error('name')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Username -->
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Username <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                id="username"
                                name="username"
                                value="{{ old('username') }}"
                                required
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all"
                                placeholder="Username"
                            >
                            @error('username')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all"
                            placeholder="you@example.com"
                        >
                        @error('email')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Password
                            </label>
                            <div class="relative">
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all"
                                    placeholder="••••••••"
                                >
                                <button 
                                    type="button" 
                                    onclick="togglePasswordRegister('password')"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
                                >
                                    <i id="eye-icon-password" class="ri-eye-off-line text-xl"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Confirm Password
                            </label>
                            <div class="relative">
                                <input
                                    type="password"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all"
                                    placeholder="••••••••"
                                >
                                <button 
                                    type="button" 
                                    onclick="togglePasswordRegister('password_confirmation')"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
                                >
                                    <i id="eye-icon-password_confirmation" class="ri-eye-off-line text-xl"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Terms and Conditions Checkbox -->
                    <div class="flex items-start gap-2 border-t pt-4 dark:border-gray-700">
                        <input
                            type="checkbox"
                            id="terms_accepted"
                            name="terms_accepted"
                            value="1"
                            required
                            class="mt-1 h-4 w-4 rounded border-gray-300 text-[#9810FA] focus:ring-[#9810FA]"
                        >
                        <label for="terms_accepted" class="text-sm text-gray-700 dark:text-gray-300">
                            I confirm that this user agrees to the <a href="{{ route('terms') }}" target="_blank" class="text-[#9810FA] hover:text-[#E60076] underline">Terms of Service</a> and <a href="{{ route('privacy') }}" target="_blank" class="text-[#9810FA] hover:text-[#E60076] underline">Privacy Policy</a> <span class="text-red-500">*</span>
                        </label>
                    </div>
                    @error('terms_accepted')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror

                    <fieldset class="space-y-3 border-t pt-4 dark:border-gray-700">
                        <legend class="text-sm font-medium text-gray-700 dark:text-gray-300">User Role</legend>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="is_admin" value="1" {{ old('is_admin') ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Admin (can access admin panel)</span>
                        </label>
                    </fieldset>

                    <!-- Create Button -->
                    <button
                        type="submit"
                        class="w-full py-3.5 px-4 bg-[linear-gradient(90deg,#9810FA_0%,#E60076_100%)] text-white text-base font-semibold rounded-full hover:shadow-lg hover:scale-[1.02] transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:ring-offset-2 mt-6"
                    >
                        Create User
                    </button>

                    <!-- Cancel Link -->
                    <div class="mt-4 text-center">
                        <a href="{{ route('admin.users.index') }}" class="text-sm font-semibold text-[#9810FA] hover:text-[#E60076] transition-colors">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Password Toggle Script -->
<script>
    function togglePasswordRegister(fieldId) {
        const passwordInput = document.getElementById(fieldId);
        const eyeIcon = document.getElementById('eye-icon-' + fieldId);
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.remove('ri-eye-off-line');
            eyeIcon.classList.add('ri-eye-line');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove('ri-eye-line');
            eyeIcon.classList.add('ri-eye-off-line');
        }
    }
</script>
@endsection
