@extends('layouts.onboarding')

@section('title', 'Tell Your Story')

@php
    $step = 8;
    $showExit = true;
@endphp

@section('content')
<div class="min-h-[60vh] flex items-center justify-center">
    <div class="w-full max-w-2xl">
        <!-- Step Icon -->
        <div class="flex justify-center mb-4">
            <div class="w-16 h-16 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                <i class="ri-file-text-line text-3xl text-[#9810FA]"></i>
            </div>
        </div>

        <!-- Step Info -->
        <div class="text-center mb-6">
            <p class="text-sm text-gray-600 dark:text-gray-400">Step {{ $step }} of 9</p>
        </div>

        <!-- Card -->
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl p-8 border border-gray-100 dark:border-gray-700">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center justify-between mb-2">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                        Tell Your Story
                    </h1>
                    <button type="button" onclick="skipStep({{ $step }})" 
                            class="text-sm font-semibold text-[#9810FA] hover:text-[#E60076] transition-colors">
                        Skip
                    </button>
                </div>
                <p class="text-gray-500 dark:text-gray-400 text-sm">
                    Help others get to know you
                </p>
            </div>

            <!-- Form -->
            <form id="step-form" class="space-y-6">
                @csrf
                
                @php
                    $isCouple = isset($profile) && $profile->category === 'couple';
                @endphp

                @if($isCouple)
                    <!-- Couple Mode: Show Her and Him sections -->
                    
                    <!-- Her Section -->
                    <div class="border-2 border-pink-200 dark:border-pink-800 rounded-2xl p-6 mb-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <span class="text-pink-500">👩</span> Her Story
                        </h3>
                        
                        <!-- Describe Yourself - Her -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Describe Yourself
                            </label>
                            <textarea name="bio_her" rows="4" 
                                      placeholder="Tell us about yourself, your interests, and what makes you unique..."
                                      class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all resize-none"></textarea>
                        </div>

                        <!-- What Are You Hoping to Find - Her -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                What Are You Hoping to Find?
                            </label>
                            <textarea name="looking_for_her" rows="4" 
                                      placeholder="Describe what kind of connections or experiences you're looking for..."
                                      class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all resize-none"></textarea>
                        </div>

                        <!-- Additional Notes - Her -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Additional Notes (Optional)
                            </label>
                            <textarea name="additional_notes_her" rows="3" 
                                      placeholder="Any other information you'd like to share..."
                                      class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all resize-none"></textarea>
                        </div>
                    </div>

                    <!-- Him Section -->
                    <div class="border-2 border-blue-200 dark:border-blue-800 rounded-2xl p-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <span class="text-blue-500">👨</span> Him Story
                        </h3>
                        
                        <!-- Describe Yourself - Him -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Describe Yourself
                            </label>
                            <textarea name="bio_him" rows="4" 
                                      placeholder="Tell us about yourself, your interests, and what makes you unique..."
                                      class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all resize-none"></textarea>
                        </div>

                        <!-- What Are You Hoping to Find - Him -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                What Are You Hoping to Find?
                            </label>
                            <textarea name="looking_for_him" rows="4" 
                                      placeholder="Describe what kind of connections or experiences you're looking for..."
                                      class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all resize-none"></textarea>
                        </div>

                        <!-- Additional Notes - Him -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Additional Notes (Optional)
                            </label>
                            <textarea name="additional_notes_him" rows="3" 
                                      placeholder="Any other information you'd like to share..."
                                      class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all resize-none"></textarea>
                        </div>
                    </div>
                @else
                    <!-- Single Mode: Original fields -->
                    <!-- Describe Yourself -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Describe Yourself
                        </label>
                        <textarea name="bio" rows="4" 
                                  placeholder="Tell us about yourself, your interests, and what makes you unique..."
                                  class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all resize-none"></textarea>
                    </div>

                    <!-- What Are You Hoping to Find -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            What Are You Hoping to Find?
                        </label>
                        <textarea name="looking_for" rows="4" 
                                  placeholder="Describe what kind of connections or experiences you're looking for..."
                                  class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all resize-none"></textarea>
                    </div>

                    <!-- Additional Notes -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Additional Notes (Optional)
                        </label>
                        <textarea name="additional_notes" rows="3" 
                                  placeholder="Any other information you'd like to share..."
                                  class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all resize-none"></textarea>
                    </div>
                @endif
            </form>

            <!-- Navigation -->
            <div class="mt-8 flex items-center justify-between">
                <a href="{{ route('onboarding.step7') }}" 
                   class="flex items-center gap-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                    <i class="ri-arrow-left-line"></i>
                    <span>Back</span>
                </a>
                <button type="submit" form="step-form"
                        class="py-3 px-8 bg-[linear-gradient(90deg,#9810FA_0%,#E60076_100%)] text-white rounded-full font-semibold hover:shadow-lg transition-all duration-200 flex items-center gap-2">
                    <span>Next</span>
                    <i class="ri-arrow-right-line"></i>
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('step-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    try {
        const response = await fetch('{{ route('onboarding.step8.store') }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        if(data.success) window.location.href = data.next;
    } catch(error) {
        alert('An error occurred. Please try again.');
    }
});

function skipStep(step) {
    fetch(`/onboarding/skip/${step}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    }).then(res => res.json()).then(data => {
        if(data.success) window.location.href = data.next;
    });
}
</script>
@endpush
@endsection

