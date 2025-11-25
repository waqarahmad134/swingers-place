@extends('layouts.onboarding')

@section('title', 'Profile Complete!')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center">
    <div class="w-full max-w-md">
        <!-- Card -->
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl p-8 md:p-12 border border-gray-100 dark:border-gray-700 text-center">
            <!-- Success Icon -->
            <div class="flex justify-center mb-6">
                <div class="w-20 h-20 rounded-full bg-gradient-to-br from-pink-400 to-pink-600 flex items-center justify-center animate-bounce">
                    <i class="ri-check-line text-4xl text-white"></i>
                </div>
            </div>

            <!-- Header -->
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-3">
                Profile Complete! 🎉
            </h1>
            <p class="text-gray-500 dark:text-gray-400 mb-8">
                Your profile is all set up and ready to go.<br>Let's start journey
            </p>

            <!-- CTA Button -->
            <a href="{{ route('home') }}" 
               class="inline-flex items-center justify-center w-full py-4 px-6 bg-[linear-gradient(90deg,#9810FA_0%,#E60076_100%)] text-white rounded-full font-semibold hover:shadow-lg hover:scale-[1.02] transition-all duration-200">
                Go to the news feed
            </a>

            <!-- View Profile Link -->
            <div class="mt-6">
                <a href="{{ route('account.profile') }}" 
                   class="text-sm font-semibold text-[#9810FA] hover:text-[#E60076] transition-colors">
                    View your profile
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Add confetti effect or celebration animation here if desired
setTimeout(() => {
    // Remove bounce animation after a few seconds
    document.querySelector('.animate-bounce')?.classList.remove('animate-bounce');
}, 3000);
</script>
@endpush
@endsection

