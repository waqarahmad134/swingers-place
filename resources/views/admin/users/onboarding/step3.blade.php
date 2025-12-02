@extends('layouts.admin')

@section('title', 'Location - Admin')

@php
    $step = 3;
@endphp

@section('content')
<div class="p-6">
<div class="min-h-[60vh] flex items-center justify-center">
    <div class="w-full max-w-2xl">
        <!-- Step Icon -->
        <div class="flex justify-center mb-4">
            <div class="w-16 h-16 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                <i class="ri-map-pin-line text-3xl text-[#9810FA]"></i>
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
                        Location
                    </h1>
                    <button type="button" onclick="skipStep({{ $step }})" 
                            class="text-sm font-semibold text-[#9810FA] hover:text-[#E60076] transition-colors">
                        Skip
                    </button>
                </div>
                <p class="text-gray-500 dark:text-gray-400 text-sm">
                    Where are you based?
                </p>
            </div>

            <!-- Form -->
            <form id="step-form" class="space-y-6">
                @csrf
                
                <!-- Home Location -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Home Location
                    </label>
                    <input type="text" id="home_location" name="home_location" 
                           placeholder="Search for your city..."
                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                    <input type="hidden" id="home_location_lat" name="home_location_lat">
                    <input type="hidden" id="home_location_lng" name="home_location_lng">
                </div>

                <!-- Country and City Fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Country -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Country
                        </label>
                        <input type="text" id="country" name="country" 
                               placeholder="Country will auto-fill from location..."
                               class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                    </div>

                    <!-- City -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            City
                        </label>
                        <input type="text" id="city" name="city" 
                               placeholder="City will auto-fill from location..."
                               class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                    </div>
                </div>

                <!-- Map Display -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Location Map
                    </label>
                    <div id="map" class="rounded-xl h-64 w-full border border-gray-200 dark:border-gray-600" style="display: none;"></div>
                    <div id="map-placeholder" class="bg-gray-100 dark:bg-gray-700 rounded-xl h-64 flex items-center justify-center border-2 border-dashed border-gray-300 dark:border-gray-600">
                        <div class="text-center">
                            <i class="ri-map-pin-2-line text-4xl text-gray-400 mb-2"></i>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Map will appear when you select a location</p>
                        </div>
                    </div>
                </div>

                <!-- Travel Location -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Travel Location (Optional)
                    </label>
                    <input type="text" id="travel_location" name="travel_location" 
                           placeholder="Where do you travel to?"
                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#9810FA] focus:border-transparent transition-all">
                </div>
            </form>

            <!-- Navigation -->
            <div class="mt-8 flex items-center justify-between">
                <a href="{{ route('admin.users.onboarding.step2') }}" 
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
@php
    $googleMapsApiKey = config('services.google_maps.api_key');
@endphp
@if($googleMapsApiKey)
<script>
let map;
let marker;
let homeLocationAutocomplete;
let travelLocationAutocomplete;

function extractCountryAndCity(addressComponents) {
    let country = '';
    let city = '';
    
    if (addressComponents) {
        for (let component of addressComponents) {
            const types = component.types;
            
            // Extract country
            if (types.includes('country')) {
                country = component.long_name;
            }
            
            // Extract city - try multiple types in order of preference
            if (!city && types.includes('locality')) {
                city = component.long_name;
            } else if (!city && types.includes('administrative_area_level_2')) {
                city = component.long_name;
            } else if (!city && types.includes('administrative_area_level_1')) {
                city = component.long_name;
            } else if (!city && types.includes('sublocality')) {
                city = component.long_name;
            }
        }
    }
    
    // Update country and city fields
    const countryInput = document.getElementById('country');
    const cityInput = document.getElementById('city');
    if (countryInput) countryInput.value = country;
    if (cityInput) cityInput.value = city;
}

function initGoogleMaps() {
    // Initialize Home Location Autocomplete
    const homeLocationInput = document.getElementById('home_location');
    if (homeLocationInput && typeof google !== 'undefined' && google.maps && google.maps.places) {
        try {
            homeLocationAutocomplete = new google.maps.places.Autocomplete(homeLocationInput, {
                types: ['(cities)'],
                fields: ['formatted_address', 'geometry', 'name', 'address_components']
            });

            homeLocationAutocomplete.addListener('place_changed', function() {
                const place = homeLocationAutocomplete.getPlace();
                if (!place.geometry) {
                    return;
                }

                document.getElementById('home_location_lat').value = place.geometry.location.lat();
                document.getElementById('home_location_lng').value = place.geometry.location.lng();
                homeLocationInput.value = place.formatted_address || place.name;

                // Extract country and city from address components
                extractCountryAndCity(place.address_components);

                if (!map) {
                    initMap(place.geometry.location);
                } else {
                    map.setCenter(place.geometry.location);
                    marker.setPosition(place.geometry.location);
                }

                document.getElementById('map-placeholder').style.display = 'none';
                document.getElementById('map').style.display = 'block';
            });
        } catch (error) {
            console.error('Error initializing autocomplete:', error);
            if (error.message && error.message.includes('legacy')) {
                console.error('⚠️ PLACES API NOT ENABLED:');
                console.error('Please enable "Places API" (legacy) in Google Cloud Console:');
                console.error('1. Go to: https://console.cloud.google.com/apis/library');
                console.error('2. Search for "Places API" (without "New")');
                console.error('3. Click ENABLE');
                console.error('4. Wait 1-5 minutes and refresh this page');
            }
        }
    }

    // Initialize Travel Location Autocomplete
    const travelLocationInput = document.getElementById('travel_location');
    if (travelLocationInput && typeof google !== 'undefined' && google.maps && google.maps.places) {
        try {
            travelLocationAutocomplete = new google.maps.places.Autocomplete(travelLocationInput, {
                types: ['(cities)'],
                fields: ['formatted_address', 'name']
            });

            travelLocationAutocomplete.addListener('place_changed', function() {
                const place = travelLocationAutocomplete.getPlace();
                if (place.formatted_address) {
                    travelLocationInput.value = place.formatted_address || place.name;
                }
            });
        } catch (error) {
            console.error('Error initializing travel autocomplete:', error);
        }
    }
}

function initMap(location) {
    const mapElement = document.getElementById('map');
    if (!mapElement) return;

    map = new google.maps.Map(mapElement, {
        center: location || { lat: 0, lng: 0 },
        zoom: 12,
        mapTypeControl: false,
        streetViewControl: false,
        fullscreenControl: false
    });

    marker = new google.maps.Marker({
        map: map,
        position: location || { lat: 0, lng: 0 },
        draggable: true,
        animation: google.maps.Animation.DROP
    });

    marker.addListener('dragend', function() {
        const position = marker.getPosition();
        document.getElementById('home_location_lat').value = position.lat();
        document.getElementById('home_location_lng').value = position.lng();
        
        const geocoder = new google.maps.Geocoder();
        geocoder.geocode({ location: position }, (results, status) => {
            if (status === 'OK' && results[0]) {
                document.getElementById('home_location').value = results[0].formatted_address;
                // Extract country and city from geocoded results
                extractCountryAndCity(results[0].address_components);
            }
        });
    });
}

// Load Google Maps API with proper async loading and callback
(function() {
    const script = document.createElement('script');
    script.src = 'https://maps.googleapis.com/maps/api/js?key={{ $googleMapsApiKey }}&loading=async&libraries=places&callback=initMapCallback';
    script.async = true;
    script.defer = true;
    document.head.appendChild(script);
})();

// Callback function for when Google Maps loads
window.initMapCallback = function() {
    if (typeof google !== 'undefined' && google.maps) {
        initGoogleMaps();
    }
};

document.getElementById('step-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    try {
        const response = await fetch('{{ route('admin.users.onboarding.step3.store') }}', {
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
@else
<script>
console.warn('Google Maps API key is not configured. Please add GOOGLE_MAPS_API_KEY to your .env file.');
</script>
@endif
@endpush
@endsection

