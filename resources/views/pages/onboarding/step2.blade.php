@extends('layouts.onboarding')

@section('title', 'What Do You Prefer?')

@php
    $step = 2;
    $showExit = true;
@endphp

@section('content')
<div class="min-h-[60vh] flex items-center justify-center">
    <div class="w-full max-w-2xl">
        <!-- Step Icon -->
        <div class="flex justify-center mb-4">
            <div class="w-16 h-16 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                <i class="ri-heart-3-line text-3xl text-[#9810FA]"></i>
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
                        What Do You Prefer?
                    </h1>
                    <button type="button" onclick="skipStep({{ $step }})" 
                            class="text-sm font-semibold text-[#9810FA] hover:text-[#E60076] transition-colors">
                        Skip
                    </button>
                </div>
                <p class="text-gray-500 dark:text-gray-400 text-sm">
                    Select all that apply
                </p>
            </div>

            <!-- Form -->
            <form id="step-form" class="space-y-4">
                @csrf
                
                <div class="grid grid-cols-2 gap-4">
                    <!-- Full Swap -->
                    <label class="preference-option">
                        <input type="checkbox" name="preferences[]" value="full_swap" class="sr-only preference-input">
                        <div class="preference-card p-6 border-2 border-gray-200 dark:border-gray-700 rounded-2xl cursor-pointer hover:border-[#9810FA] transition-all text-center">
                            <div class="text-4xl mb-3">🔄</div>
                            <p class="font-semibold text-gray-900 dark:text-white">Full swap</p>
                        </div>
                    </label>

                    <!-- Soft Swap -->
                    <label class="preference-option">
                        <input type="checkbox" name="preferences[]" value="soft_swap" class="sr-only preference-input">
                        <div class="preference-card p-6 border-2 border-gray-200 dark:border-gray-700 rounded-2xl cursor-pointer hover:border-[#9810FA] transition-all text-center">
                            <div class="text-4xl mb-3">💛</div>
                            <p class="font-semibold text-gray-900 dark:text-white">Soft swap</p>
                        </div>
                    </label>

                    <!-- Exhibitionist -->
                    <label class="preference-option">
                        <input type="checkbox" name="preferences[]" value="exhibitionist" class="sr-only preference-input">
                        <div class="preference-card p-6 border-2 border-gray-200 dark:border-gray-700 rounded-2xl cursor-pointer hover:border-[#9810FA] transition-all text-center">
                            <div class="text-4xl mb-3">👀</div>
                            <p class="font-semibold text-gray-900 dark:text-white">Exhibitionist</p>
                        </div>
                    </label>

                    <!-- Voyeur -->
                    <label class="preference-option">
                        <input type="checkbox" name="preferences[]" value="voyeur" class="sr-only preference-input">
                        <div class="preference-card p-6 border-2 border-gray-200 dark:border-gray-700 rounded-2xl cursor-pointer hover:border-[#9810FA] transition-all text-center">
                            <div class="text-4xl mb-3">✨</div>
                            <p class="font-semibold text-gray-900 dark:text-white">Voyeur</p>
                        </div>
                    </label>

                    <!-- Still Exploring -->
                    <label class="preference-option">
                        <input type="checkbox" name="preferences[]" value="still_exploring" class="sr-only preference-input">
                        <div class="preference-card p-6 border-2 border-gray-200 dark:border-gray-700 rounded-2xl cursor-pointer hover:border-[#9810FA] transition-all text-center">
                            <div class="text-4xl mb-3">🧭</div>
                            <p class="font-semibold text-gray-900 dark:text-white">Still exploring</p>
                        </div>
                    </label>

                    <!-- Hotwife -->
                    <label class="preference-option">
                        <input type="checkbox" name="preferences[]" value="hotwife" class="sr-only preference-input">
                        <div class="preference-card p-6 border-2 border-gray-200 dark:border-gray-700 rounded-2xl cursor-pointer hover:border-[#9810FA] transition-all text-center">
                            <div class="text-4xl mb-3">💋</div>
                            <p class="font-semibold text-gray-900 dark:text-white">Hotwife</p>
                        </div>
                    </label>

                    <!-- Others -->
                    <label class="preference-option">
                        <input type="checkbox" name="preferences[]" value="others" class="sr-only preference-input">
                        <div class="preference-card p-6 border-2 border-gray-200 dark:border-gray-700 rounded-2xl cursor-pointer hover:border-[#9810FA] transition-all text-center">
                            <div class="text-4xl mb-3">✨</div>
                            <p class="font-semibold text-gray-900 dark:text-white">Others</p>
                        </div>
                    </label>
                </div>
            </form>

            <!-- Navigation -->
            <div class="mt-8 flex items-center justify-between">
                <a href="{{ route('onboarding.step1') }}" 
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
// Handle preference selection
document.querySelectorAll('.preference-input').forEach(input => {
    input.addEventListener('change', function() {
        const card = this.closest('.preference-option').querySelector('.preference-card');
        if(this.checked) {
            card.classList.add('border-[#9810FA]', 'bg-pink-50', 'dark:bg-pink-900/10');
        } else {
            card.classList.remove('border-[#9810FA]', 'bg-pink-50', 'dark:bg-pink-900/10');
        }
    });
});

// Handle form submission
document.getElementById('step-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    try {
        const response = await fetch('{{ route('onboarding.step2.store') }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if(data.success) {
            window.location.href = data.next;
        }
    } catch(error) {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    }
});

function skipStep(step) {
    if(confirm('Are you sure you want to skip this step?')) {
        fetch(`/onboarding/skip/${step}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                window.location.href = data.next;
            }
        });
    }
}
</script>
@endpush
@endsection

