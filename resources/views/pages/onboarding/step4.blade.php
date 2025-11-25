@extends('layouts.onboarding')

@section('title', 'Spoken Languages')

@php
    $step = 4;
    $showExit = true;
    $languages = ['English', 'Spanish', 'French', 'German', 'Italian', 'Portuguese', 'Mandarin', 'Japanese', 'Korean', 'Arabic', 'Russian', 'Hindi'];
@endphp

@section('content')
<div class="min-h-[60vh] flex items-center justify-center">
    <div class="w-full max-w-2xl">
        <!-- Step Icon -->
        <div class="flex justify-center mb-4">
            <div class="w-16 h-16 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                <i class="ri-translate-2 text-3xl text-[#9810FA]"></i>
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
                        Spoken Languages
                    </h1>
                    <button type="button" onclick="skipStep({{ $step }})" 
                            class="text-sm font-semibold text-[#9810FA] hover:text-[#E60076] transition-colors">
                        Skip
                    </button>
                </div>
                <p class="text-gray-500 dark:text-gray-400 text-sm">
                    What languages do you speak?
                </p>
            </div>

            <!-- Form -->
            <form id="step-form" class="space-y-3">
                @csrf
                
                @foreach($languages as $language)
                <label class="language-option flex items-center justify-between p-4 border-2 border-gray-200 dark:border-gray-700 rounded-xl cursor-pointer hover:border-[#9810FA] transition-all">
                    <span class="text-gray-900 dark:text-white font-medium">{{ $language }}</span>
                    <div class="toggle-container">
                        <input type="checkbox" name="languages[]" value="{{ strtolower($language) }}" class="sr-only language-toggle" {{ $language === 'English' ? 'checked' : '' }}>
                        <div class="toggle-switch w-12 h-6 bg-gray-300 dark:bg-gray-600 rounded-full relative transition-colors duration-200">
                            <div class="toggle-thumb absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform duration-200"></div>
                        </div>
                    </div>
                </label>
                @endforeach
            </form>

            <!-- Navigation -->
            <div class="mt-8 flex items-center justify-between">
                <a href="{{ route('onboarding.step3') }}" 
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
// Toggle switch functionality
document.querySelectorAll('.language-toggle').forEach(input => {
    const toggleSwitch = input.nextElementSibling;
    const thumb = toggleSwitch.querySelector('.toggle-thumb');
    const option = input.closest('.language-option');
    
    function updateToggle() {
        if(input.checked) {
            toggleSwitch.classList.add('bg-[#9810FA]');
            toggleSwitch.classList.remove('bg-gray-300', 'dark:bg-gray-600');
            thumb.classList.add('translate-x-6');
            option.classList.add('border-[#9810FA]', 'bg-pink-50', 'dark:bg-pink-900/10');
        } else {
            toggleSwitch.classList.remove('bg-[#9810FA]');
            toggleSwitch.classList.add('bg-gray-300', 'dark:bg-gray-600');
            thumb.classList.remove('translate-x-6');
            option.classList.remove('border-[#9810FA]', 'bg-pink-50', 'dark:bg-pink-900/10');
        }
    }
    
    updateToggle(); // Initialize
    
    input.addEventListener('change', updateToggle);
    toggleSwitch.addEventListener('click', function(e) {
        e.preventDefault();
        input.checked = !input.checked;
        input.dispatchEvent(new Event('change'));
    });
});

// Form submission
document.getElementById('step-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    try {
        const response = await fetch('{{ route('onboarding.step4.store') }}', {
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
    if(confirm('Skip this step?')) {
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
}
</script>
@endpush
@endsection

