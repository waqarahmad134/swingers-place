@extends('layouts.admin')

@section('title', 'Personal Details - Admin')

@php
    $step = 6;
@endphp

@section('content')
<div class="p-6">
<div class="min-h-[60vh] flex items-center justify-center">
    <div class="w-full max-w-2xl">
        <!-- Step Icon -->
        <div class="flex justify-center mb-4">
            <div class="w-16 h-16 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                <i class="ri-user-line text-3xl text-[#9810FA]"></i>
            </div>
        </div>

        <!-- Step Info -->
        <div class="text-center mb-6">
            <p class="text-sm text-gray-600 dark:text-gray-400">Step {{ $step }} of 9 - Creating profile for: <strong>{{ $user->name ?? $user->username ?? 'User' }}</strong></p>
        </div>

        <!-- Card -->
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl p-8 border border-gray-100 dark:border-gray-700">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center justify-between mb-2">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                        Personal Details
                    </h1>
                    <button type="button" onclick="skipStep({{ $step }})" 
                            class="text-sm font-semibold text-[#9810FA] hover:text-[#E60076] transition-colors">
                        Skip
                    </button>
                </div>
                <p class="text-gray-500 dark:text-gray-400 text-sm">
                    Help others know you better
                </p>
            </div>

            <!-- Form -->
            <form id="step-form" class="space-y-5">
                @csrf
                
                @php
                    $isCouple = isset($profile) && $profile->category === 'couple';
                @endphp

                @if($isCouple)
                    <!-- Couple Mode: Show Her and Him sections -->
                    
                    <!-- Her Section -->
                    <div class="border-2 border-pink-200 dark:border-pink-800 rounded-2xl p-6 mb-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <span class="text-pink-500">👩</span> Her Details
                        </h3>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Weight - Her -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Weight
                                </label>
                                <div class="relative">
                                    <input type="number" name="weight_her" placeholder="kg" 
                                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                                </div>
                            </div>

                            <!-- Height - Her -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Height
                                </label>
                                <div class="relative">
                                    <input type="number" name="height_her" placeholder="cm" 
                                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <!-- Body Type - Her -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Body Type
                                </label>
                                <input type="text" name="body_type_her" placeholder="EXP: Ectomorph, Mesomorph, and Endomorph" 
                                       class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                            </div>

                            <!-- Eye Color - Her -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Eye Color
                                </label>
                                <input type="text" name="eye_color_her" placeholder="EXP: brown, blue, green, gray" 
                                       class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <!-- Hair Color - Her -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Hair Color
                                </label>
                                <input type="text" name="hair_color_her" placeholder="EXP: black, brown, blond, and red" 
                                       class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                            </div>

                            <!-- Tattoos - Her -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Tattoos
                                </label>
                                <select name="tattoos_her" 
                                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                                    <option value="">Select...</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <!-- Piercings - Her -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Piercings
                                </label>
                                <input type="text" name="piercings_her" placeholder="Type Here" 
                                       class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                            </div>

                            <!-- Race - Her -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Race
                                </label>
                                <input type="text" name="race_her" placeholder="Type Here" 
                                       class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                            </div>
                        </div>
                    </div>

                    <!-- Him Section -->
                    <div class="border-2 border-blue-200 dark:border-blue-800 rounded-2xl p-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <span class="text-blue-500">👨</span> Him Details
                        </h3>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Weight - Him -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Weight
                                </label>
                                <div class="relative">
                                    <input type="number" name="weight_him" placeholder="kg" 
                                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                                </div>
                            </div>

                            <!-- Height - Him -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Height
                                </label>
                                <div class="relative">
                                    <input type="number" name="height_him" placeholder="cm" 
                                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <!-- Body Type - Him -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Body Type
                                </label>
                                <input type="text" name="body_type_him" placeholder="EXP: Ectomorph, Mesomorph, and Endomorph" 
                                       class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                            </div>

                            <!-- Eye Color - Him -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Eye Color
                                </label>
                                <input type="text" name="eye_color_him" placeholder="EXP: brown, blue, green, gray" 
                                       class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <!-- Hair Color - Him -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Hair Color
                                </label>
                                <input type="text" name="hair_color_him" placeholder="EXP: black, brown, blond, and red" 
                                       class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                            </div>

                            <!-- Tattoos - Him -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Tattoos
                                </label>
                                <select name="tattoos_him" 
                                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                                    <option value="">Select...</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <!-- Piercings - Him -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Piercings
                                </label>
                                <input type="text" name="piercings_him" placeholder="Type Here" 
                                       class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                            </div>

                            <!-- Race - Him -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Race
                                </label>
                                <input type="text" name="race_him" placeholder="Type Here" 
                                       class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Single Mode: Original fields -->
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Weight -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Weight
                            </label>
                            <div class="relative">
                                <input type="number" name="weight" placeholder="kg" 
                                       class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                            </div>
                        </div>

                        <!-- Height -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Height
                            </label>
                            <div class="relative">
                                <input type="number" name="height" placeholder="cm" 
                                       class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Body Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Body Type
                            </label>
                            <input type="text" name="body_type" placeholder="EXP: Ectomorph, Mesomorph, and Endomorph" 
                                   class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                        </div>

                        <!-- Eye Color -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Eye Color
                            </label>
                            <input type="text" name="eye_color" placeholder="EXP: brown, blue, green, gray" 
                                   class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Hair Color -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Hair Color
                            </label>
                            <input type="text" name="hair_color" placeholder="EXP: black, brown, blond, and red" 
                                   class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                        </div>

                        <!-- Tattoos -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tattoos
                            </label>
                            <select name="tattoos" 
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                                <option value="">Select...</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Piercings -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Piercings
                            </label>
                            <input type="text" name="piercings" placeholder="Type Here" 
                                   class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                        </div>

                        <!-- Race -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Race
                            </label>
                            <input type="text" name="race" placeholder="Type Here" 
                                   class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                        </div>
                    </div>
                @endif
            </form>

            <!-- Navigation -->
            <div class="mt-8 flex items-center justify-between">
                <a href="{{ route('admin.users.onboarding.step5') }}" 
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
</div>

@push('scripts')
<script>
document.getElementById('step-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    try {
        const response = await fetch('{{ route('admin.users.onboarding.step6.store') }}', {
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
        fetch(`/admin/users/onboarding/skip/${step}`, {
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

