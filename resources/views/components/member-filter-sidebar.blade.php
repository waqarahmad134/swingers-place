<form method="GET" action="{{ route('dashboard.members') }}" id="memberFilterForm">
    <div class="bg-gray-800 dark:bg-gray-900 rounded-2xl p-6 text-white">
        <!-- Filter Options with Toggles -->
        <div class="space-y-4 mb-6">
            <!-- Couples -->
            <div class="flex items-center justify-between">
                <label class="flex items-center gap-3 cursor-pointer flex-1">
                    <div class="relative">
                        <input 
                            type="checkbox" 
                            name="filter_couples" 
                            value="1"
                            {{ request('filter_couples') ? 'checked' : '' }}
                            class="sr-only peer"
                            onchange="updateFilterForm()"
                        >
                        <div class="relative w-12 h-6 bg-gray-600 rounded-full transition-colors peer-checked:bg-green-500">
                            <div class="absolute top-1 left-1 w-4 h-4 bg-white rounded-full transition-transform duration-300 peer-checked:translate-x-6"></div>
                        </div>
                    </div>
                    <span class="text-white font-medium">Couples</span>
                </label>
                <div class="w-8 h-8 rounded-full bg-purple-500 flex-shrink-0"></div>
            </div>

            <!-- Female -->
            <div class="flex items-center justify-between">
                <label class="flex items-center gap-3 cursor-pointer flex-1">
                    <div class="relative">
                        <input 
                            type="checkbox" 
                            name="filter_female" 
                            value="1"
                            {{ request('filter_female') ? 'checked' : '' }}
                            class="sr-only peer"
                            onchange="updateFilterForm()"
                        >
                        <div class="relative w-12 h-6 bg-gray-600 rounded-full transition-colors peer-checked:bg-green-500">
                            <div class="absolute top-1 left-1 w-4 h-4 bg-white rounded-full transition-transform duration-300 peer-checked:translate-x-6"></div>
                        </div>
                    </div>
                    <span class="text-white font-medium">Female</span>
                </label>
                <div class="w-8 h-8 rounded-full bg-pink-500 flex-shrink-0"></div>
            </div>

            <!-- Male -->
            <div class="flex items-center justify-between">
                <label class="flex items-center gap-3 cursor-pointer flex-1">
                    <div class="relative">
                        <input 
                            type="checkbox" 
                            name="filter_male" 
                            value="1"
                            {{ request('filter_male') ? 'checked' : '' }}
                            class="sr-only peer"
                            onchange="updateFilterForm()"
                        >
                        <div class="relative w-12 h-6 bg-gray-600 rounded-full transition-colors peer-checked:bg-green-500">
                            <div class="absolute top-1 left-1 w-4 h-4 bg-white rounded-full transition-transform duration-300 peer-checked:translate-x-6"></div>
                        </div>
                    </div>
                    <span class="text-white font-medium">Male</span>
                </label>
                <div class="w-8 h-8 rounded-full bg-blue-400 flex-shrink-0"></div>
            </div>

            <!-- Business -->
            <div class="flex items-center justify-between">
                <label class="flex items-center gap-3 cursor-pointer flex-1">
                    <div class="relative">
                        <input 
                            type="checkbox" 
                            name="filter_business" 
                            value="1"
                            {{ request('filter_business') ? 'checked' : '' }}
                            class="sr-only peer"
                            onchange="updateFilterForm()"
                        >
                        <div class="relative w-12 h-6 bg-gray-600 rounded-full transition-colors peer-checked:bg-green-500">
                            <div class="absolute top-1 left-1 w-4 h-4 bg-white rounded-full transition-transform duration-300 peer-checked:translate-x-6"></div>
                        </div>
                    </div>
                    <span class="text-white font-medium">Business</span>
                </label>
                <div class="w-8 h-8 rounded-full bg-yellow-500 flex-shrink-0"></div>
            </div>

            <!-- Transgender -->
            <div class="flex items-center justify-between">
                <label class="flex items-center gap-3 cursor-pointer flex-1">
                    <div class="relative">
                        <input 
                            type="checkbox" 
                            name="filter_transgender" 
                            value="1"
                            {{ request('filter_transgender') ? 'checked' : '' }}
                            class="sr-only peer"
                            onchange="updateFilterForm()"
                        >
                        <div class="relative w-12 h-6 bg-gray-600 rounded-full transition-colors peer-checked:bg-green-500">
                            <div class="absolute top-1 left-1 w-4 h-4 bg-white rounded-full transition-transform duration-300 peer-checked:translate-x-6"></div>
                        </div>
                    </div>
                    <span class="text-white font-medium">Transgender</span>
                </label>
                <div class="w-8 h-8 rounded-full bg-purple-500 flex-shrink-0"></div>
            </div>

            <!-- Looking for me / us -->
            <div class="flex items-center justify-between">
                <label class="flex items-center gap-3 cursor-pointer flex-1">
                    <div class="relative">
                        <input 
                            type="checkbox" 
                            name="filter_looking_for_me" 
                            value="1"
                            {{ request('filter_looking_for_me') ? 'checked' : '' }}
                            class="sr-only peer"
                            onchange="updateFilterForm()"
                        >
                        <div class="relative w-12 h-6 bg-gray-600 rounded-full transition-colors peer-checked:bg-green-500">
                            <div class="absolute top-1 left-1 w-4 h-4 bg-white rounded-full transition-transform duration-300 peer-checked:translate-x-6"></div>
                        </div>
                    </div>
                    <span class="text-white font-medium">Looking for me / us</span>
                </label>
            </div>
        </div>

        <!-- Show Online Users -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <label class="flex items-center gap-3 cursor-pointer flex-1">
                    <div class="relative">
                        <input 
                            type="checkbox" 
                            name="online_only" 
                            value="1"
                            {{ request('online_only') ? 'checked' : '' }}
                            class="sr-only peer"
                            onchange="updateFilterForm()"
                        >
                        <div class="relative w-12 h-6 bg-gray-600 rounded-full transition-colors peer-checked:bg-green-500">
                            <div class="absolute top-1 left-1 w-4 h-4 bg-white rounded-full transition-transform duration-300 peer-checked:translate-x-6"></div>
                        </div>
                    </div>
                    <span class="text-white font-medium">Show online users only</span>
                </label>
                <div class="w-8 h-8 rounded-full bg-green-500 flex-shrink-0 flex items-center justify-center">
                    <div class="w-3 h-3 bg-white rounded-full animate-pulse"></div>
                </div>
            </div>
        </div>

        <!-- Location Section -->
        <div class="mb-6">
            <label class="block text-white font-semibold uppercase mb-3">LOCATION</label>
            <input 
                type="text" 
                name="filter_location"
                id="filter_location_sidebar"
                value="{{ request('filter_location') }}"
                placeholder="Pune, Maharashtra, IN" 
                class="w-full bg-gray-700 border border-gray-600 rounded-xl px-4 py-3 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500"
            />
        </div>

        <!-- Hidden inputs for other filters -->
        <input type="hidden" name="search" value="{{ request('search') }}">
        <input type="hidden" name="sort_by" value="{{ request('sort_by', 'Random') }}">
        <input type="hidden" name="distance" value="{{ request('distance', 'Any Distance') }}">
        <input type="hidden" name="age_range" value="{{ request('age_range', 'Any Age') }}">

        <!-- Ok Button -->
        <button 
            type="submit"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-xl transition-colors"
        >
            Ok
        </button>
    </div>
</form>

<style>
/* Ensure toggle switches show correct state on page load */
input[type="checkbox"]:checked ~ div {
    background-color: #10b981 !important; /* green-500 */
}

input[type="checkbox"]:checked ~ div > div {
    transform: translateX(1.5rem) !important; /* translate-x-6 */
}
</style>

<script>
function updateFilterForm() {
    // This function can be used to update the form dynamically if needed
    // For now, form submission will handle the updates
}
</script>

