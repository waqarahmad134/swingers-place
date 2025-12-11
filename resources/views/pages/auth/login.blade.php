@extends('layouts.app')

@section('title', 'Login - ' . config('app.name'))

@section('content')
    <div class="min-h-[calc(100vh-200px)] flex items-center justify-center py-12 px-4">
        <div class="w-full max-w-md">
            <!-- Login Card -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl p-8 md:p-10 border border-gray-100 dark:border-gray-700">
                <!-- Header -->
                <div class="text-center mb-8">
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-2">
                        Welcome Back
                    </h1>
                    <p class="text-gray-500 dark:text-gray-400 text-sm md:text-base">
                        Login to your account
                    </p>
                </div>

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <!-- Username Field -->
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Enter your Username or Email
                        </label>
                        <input
                            type="text"
                            id="username"
                            name="username"
                            value="{{ old('username') }}"
                            required
                            autofocus
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all"
                            placeholder="username or email@example.com"
                        >
                        @error('username')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Enter Your Password
                        </label>
                        <div class="relative">
                            <input
                                type="password"
                                id="password"
                                name="password"
                                required
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all"
                                placeholder="••••••••"
                            >
                            <button 
                                type="button" 
                                onclick="togglePassword()"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
                            >
                                <i id="eye-icon" class="ri-eye-off-line text-xl"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Forgot Password Link -->
                    <div class="text-right">
                        <a href="{{ route('password.request') }}" class="text-sm font-medium text-[#9810FA] hover:text-[#E60076] transition-colors">
                            Forgot password?
                        </a>
                    </div>

                    <!-- Login Button -->
                    <button
                        type="submit"
                        class="w-full py-3.5 px-4 bg-[linear-gradient(90deg,#9810FA_0%,#E60076_100%)] text-white text-base font-semibold rounded-full hover:shadow-lg hover:scale-[1.02] transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:ring-offset-2"
                    >
                        Login
                    </button>
                </form>

                <!-- Sign Up Link -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Don't have an account? 
                        <a href="{{ route('register') }}" class="font-semibold text-[#9810FA] hover:text-[#E60076] transition-colors">
                            Sign up
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Password Toggle Script -->
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            
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

