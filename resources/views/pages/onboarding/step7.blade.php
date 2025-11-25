@extends('layouts.onboarding')

@section('title', 'Add Photos')

@php
    $step = 7;
    $showExit = true;
@endphp

@section('content')
<div class="min-h-[60vh] flex items-center justify-center">
    <div class="w-full max-w-2xl">
        <!-- Step Icon -->
        <div class="flex justify-center mb-4">
            <div class="w-16 h-16 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                <i class="ri-camera-line text-3xl text-[#9810FA]"></i>
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
                        Add Photos
                    </h1>
                    <button type="button" onclick="skipStep({{ $step }})" 
                            class="text-sm font-semibold text-[#9810FA] hover:text-[#E60076] transition-colors">
                        Skip
                    </button>
                </div>
                <p class="text-gray-500 dark:text-gray-400 text-sm">
                    Make your profile stand out
                </p>
            </div>

            <!-- Form -->
            <form id="step-form" class="space-y-6" enctype="multipart/form-data">
                @csrf
                
                <!-- Profile Photo -->
                <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-2xl p-8 text-center hover:border-[#9810FA] transition-all cursor-pointer" 
                     onclick="document.getElementById('profile_photo').click()">
                    <input type="file" id="profile_photo" name="profile_photo" accept="image/*" class="hidden" onchange="previewImage(this, 'profile-preview')">
                    <div id="profile-preview">
                        <i class="ri-camera-line text-5xl text-gray-400 mb-3"></i>
                        <p class="font-semibold text-gray-900 dark:text-white mb-1">Upload Profile Photo</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Click to select or drag and drop</p>
                    </div>
                </div>

                <!-- Cover Photo -->
                <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-2xl p-8 text-center hover:border-[#9810FA] transition-all cursor-pointer" 
                     onclick="document.getElementById('cover_photo').click()">
                    <input type="file" id="cover_photo" name="cover_photo" accept="image/*" class="hidden" onchange="previewImage(this, 'cover-preview')">
                    <div id="cover-preview">
                        <i class="ri-image-line text-5xl text-gray-400 mb-3"></i>
                        <p class="font-semibold text-gray-900 dark:text-white mb-1">Upload Cover Photo</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Click to select or drag and drop</p>
                    </div>
                </div>

                <!-- Album Photos -->
                <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-2xl p-8 text-center hover:border-[#9810FA] transition-all cursor-pointer" 
                     onclick="document.getElementById('album_photos').click()">
                    <input type="file" id="album_photos" name="album_photos[]" accept="image/*" multiple class="hidden" onchange="previewMultipleImages(this)">
                    <div id="album-preview">
                        <i class="ri-gallery-line text-5xl text-gray-400 mb-3"></i>
                        <p class="font-semibold text-gray-900 dark:text-white mb-1">Add to Album</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Upload multiple photos</p>
                    </div>
                </div>
            </form>

            <!-- Navigation -->
            <div class="mt-8 flex items-center justify-between">
                <a href="{{ route('onboarding.step6') }}" 
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
function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById(previewId).innerHTML = `
                <img src="${e.target.result}" class="max-h-48 rounded-lg mx-auto">
                <p class="text-sm text-green-600 mt-3">✓ Photo selected</p>
            `;
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function previewMultipleImages(input) {
    if (input.files && input.files.length > 0) {
        document.getElementById('album-preview').innerHTML = `
            <i class="ri-check-line text-5xl text-green-500 mb-3"></i>
            <p class="font-semibold text-gray-900 dark:text-white mb-1">${input.files.length} photo(s) selected</p>
            <p class="text-sm text-gray-500 dark:text-gray-400">Click to change</p>
        `;
    }
}

document.getElementById('step-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    try {
        const response = await fetch('{{ route('onboarding.step7.store') }}', {
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

