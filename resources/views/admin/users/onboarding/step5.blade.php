@extends('layouts.admin')

@section('title', 'Basic Information - Admin')

@php
    $step = 5;
@endphp

@section('content')
<div class="p-6">
<div class="min-h-[60vh] flex items-center justify-center">
    <div class="w-full max-w-2xl">
        <!-- Step Icon -->
        <div class="flex justify-center mb-4">
            <div class="w-16 h-16 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                <i class="ri-information-line text-3xl text-[#9810FA]"></i>
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
                        Basic Information
                    </h1>
                    <button type="button" onclick="skipStep({{ $step }})" 
                            class="text-sm font-semibold text-[#9810FA] hover:text-[#E60076] transition-colors">
                        Skip
                    </button>
                </div>
                <p class="text-gray-500 dark:text-gray-400 text-sm">
                    Tell us more about yourself
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
                            <span class="text-pink-500">👩</span> Her Information
                        </h3>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Date of Birth - Her -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Date of Birth
                                </label>
                                <div class="relative">
                                    <input type="date" name="date_of_birth_her" 
                                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                                    <i class="ri-calendar-line absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                                </div>
                            </div>

                            <!-- Sexuality - Her -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Sexuality
                                </label>
                                <select name="sexuality_her" 
                                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                                    <option value="">Select...</option>
                                    <option value="heterosexual">Heterosexual</option>
                                    <option value="bisexual">Bisexual</option>
                                    <option value="homosexual">Homosexual</option>
                                    <option value="pansexual">Pansexual</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <!-- Relationship Status - Her -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Relationship Status
                                </label>
                                <select name="relationship_status_her" 
                                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                                    <option value="">Select...</option>
                                    <option value="single">Single</option>
                                    <option value="relationship">In a Relationship</option>
                                    <option value="married">Married</option>
                                    <option value="open">Open Relationship</option>
                                </select>
                            </div>

                            <!-- Smoking - Her -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Smoking
                                </label>
                                <select name="smoking_her" 
                                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                                    <option value="">Select...</option>
                                    <option value="never">Never</option>
                                    <option value="occasionally">Occasionally</option>
                                    <option value="regularly">Regularly</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <!-- Experience - Her -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Experience
                                </label>
                                <select name="experience_her" 
                                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                                    <option value="">Select...</option>
                                    <option value="beginner">Beginner</option>
                                    <option value="intermediate">Intermediate</option>
                                    <option value="experienced">Experienced</option>
                                    <option value="expert">Expert</option>
                                </select>
                            </div>

                            <!-- Travel Options - Her -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Travel Options
                                </label>
                                <select name="travel_options_her" 
                                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                                    <option value="">Select...</option>
                                    <option value="can_host">Can Host</option>
                                    <option value="can_travel">Can Travel</option>
                                    <option value="both">Both</option>
                                    <option value="neither">Neither</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Him Section -->
                    <div class="border-2 border-blue-200 dark:border-blue-800 rounded-2xl p-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <span class="text-blue-500">👨</span> Him Information
                        </h3>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Date of Birth - Him -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Date of Birth
                                </label>
                                <div class="relative">
                                    <input type="date" name="date_of_birth_him" 
                                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                                    <i class="ri-calendar-line absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                                </div>
                            </div>

                            <!-- Sexuality - Him -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Sexuality
                                </label>
                                <select name="sexuality_him" 
                                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                                    <option value="">Select...</option>
                                    <option value="heterosexual">Heterosexual</option>
                                    <option value="bisexual">Bisexual</option>
                                    <option value="homosexual">Homosexual</option>
                                    <option value="pansexual">Pansexual</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <!-- Relationship Status - Him -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Relationship Status
                                </label>
                                <select name="relationship_status_him" 
                                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                                    <option value="">Select...</option>
                                    <option value="single">Single</option>
                                    <option value="relationship">In a Relationship</option>
                                    <option value="married">Married</option>
                                    <option value="open">Open Relationship</option>
                                </select>
                            </div>

                            <!-- Smoking - Him -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Smoking
                                </label>
                                <select name="smoking_him" 
                                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                                    <option value="">Select...</option>
                                    <option value="never">Never</option>
                                    <option value="occasionally">Occasionally</option>
                                    <option value="regularly">Regularly</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <!-- Experience - Him -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Experience
                                </label>
                                <select name="experience_him" 
                                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                                    <option value="">Select...</option>
                                    <option value="beginner">Beginner</option>
                                    <option value="intermediate">Intermediate</option>
                                    <option value="experienced">Experienced</option>
                                    <option value="expert">Expert</option>
                                </select>
                            </div>

                            <!-- Travel Options - Him -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Travel Options
                                </label>
                                <select name="travel_options_him" 
                                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                                    <option value="">Select...</option>
                                    <option value="can_host">Can Host</option>
                                    <option value="can_travel">Can Travel</option>
                                    <option value="both">Both</option>
                                    <option value="neither">Neither</option>
                                </select>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Single Mode: Original fields -->
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Date of Birth -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Date of Birth
                            </label>
                            <div class="relative">
                                <input type="date" name="date_of_birth" 
                                       class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                                <i class="ri-calendar-line absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                            </div>
                        </div>

                        <!-- Sexuality -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Sexuality
                            </label>
                            <select name="sexuality" 
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                                <option value="">Select...</option>
                                <option value="heterosexual">Heterosexual</option>
                                <option value="bisexual">Bisexual</option>
                                <option value="homosexual">Homosexual</option>
                                <option value="pansexual">Pansexual</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Relationship Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Relationship Status
                            </label>
                            <select name="relationship_status" 
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                                <option value="">Select...</option>
                                <option value="single">Single</option>
                                <option value="relationship">In a Relationship</option>
                                <option value="married">Married</option>
                                <option value="open">Open Relationship</option>
                            </select>
                        </div>

                        <!-- Smoking -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Smoking
                            </label>
                            <select name="smoking" 
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                                <option value="">Select...</option>
                                <option value="never">Never</option>
                                <option value="occasionally">Occasionally</option>
                                <option value="regularly">Regularly</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Experience -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Experience
                            </label>
                            <select name="experience" 
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                                <option value="">Select...</option>
                                <option value="beginner">Beginner</option>
                                <option value="intermediate">Intermediate</option>
                                <option value="experienced">Experienced</option>
                                <option value="expert">Expert</option>
                            </select>
                        </div>

                        <!-- Travel Options -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Travel Options
                            </label>
                            <select name="travel_options" 
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                                <option value="">Select...</option>
                                <option value="can_host">Can Host</option>
                                <option value="can_travel">Can Travel</option>
                                <option value="both">Both</option>
                                <option value="neither">Neither</option>
                            </select>
                        </div>
                    </div>
                @endif
            </form>

            <!-- Navigation -->
            <div class="mt-8 flex items-center justify-between">
                <a href="{{ route('admin.users.onboarding.step4') }}" 
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
        const response = await fetch('{{ route('admin.users.onboarding.step5.store') }}', {
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

