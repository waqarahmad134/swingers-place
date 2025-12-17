<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        @php
            // Get album photos from profile
            $albumPhotos = [];
            $nonAdultPhotos = [];
            $adultPhotos = [];
            
            if ($profile && $profile->album_photos) {
                $albumPhotos = is_array($profile->album_photos) 
                    ? $profile->album_photos 
                    : json_decode($profile->album_photos, true) ?? [];
                
                // Separate photos by category
                $adultPhotos = isset($albumPhotos['adult']) ? $albumPhotos['adult'] : [];
                $nonAdultPhotos = isset($albumPhotos['non_adult']) ? $albumPhotos['non_adult'] : [];
            }
            
            // Get primary profile picture
            $primaryProfilePhoto = null;
            if ($profile && $profile->profile_photo) {
                $primaryProfilePhoto = $profile->profile_photo;
            } elseif ($user->profile_image) {
                $primaryProfilePhoto = $user->profile_image;
            }
        @endphp

        <!-- PRIMARY PROFILE PICTURE Section -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <h2 class="text-lg font-bold text-gray-700 dark:text-white mb-3 uppercase">PRIMARY PROFILE PICTURE</h2>
            
            <p class="text-gray-400 dark:text-gray-400 mb-6 text-sm leading-relaxed">
                Your primary picture appears on both app and browser versions of swingers, and must be vanilla (non-adult) as per Google and Apple's app policies. Make sure you're clearly visible; showing your face is encouraged but optional.
            </p>
            
            @if($primaryProfilePhoto)
                <div class="mb-6">
                    <div class="relative inline-block border-2 border-green-500 rounded-lg overflow-hidden">
                        <img 
                            src="{{ asset('storage/' . $primaryProfilePhoto) }}" 
                            alt="Primary profile picture" 
                            class="w-64 h-64 object-cover"
                        />
                        @if($isOwnProfile)
                            <button 
                                type="button"
                                onclick="removePrimaryPicture()"
                                class="absolute top-2 right-2 bg-black/70 hover:bg-black/90 text-white rounded-full p-2 transition-colors"
                                title="Remove picture"
                            >
                                <i class="ri-delete-bin-line text-lg"></i>
                            </button>
                        @endif
                    </div>
                </div>
            @else
                <div class="mb-6">
                    <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg w-64 h-64 flex items-center justify-center bg-gray-100 dark:bg-gray-700">
                        <div class="text-center">
                            <i class="ri-image-line text-4xl text-gray-400 mb-2"></i>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">No primary picture</p>
                        </div>
                    </div>
                </div>
            @endif
            
            @if($isOwnProfile)
                <button 
                    type="button"
                    onclick="changePrimaryPicture()"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg transition-colors"
                >
                    Change Primary Picture
                </button>
                <input 
                    type="file" 
                    id="primaryPictureInput" 
                    accept="image/jpeg,image/jpg,image/png,image/gif,image/webp" 
                    class="hidden"
                    onchange="handlePrimaryPictureChange(this)"
                />
            @endif
        </div>

        <!-- PICTURES Header Section -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white uppercase">PICTURES</h2>
                    @if($isOwnProfile)
                        <button 
                            type="button"
                            onclick="showPhotoCategoryModal()"
                            class="w-8 h-8 rounded-full bg-blue-500 hover:bg-blue-600 text-white flex items-center justify-center transition-colors"
                            title="Help"
                        >
                            <i class="ri-question-line text-lg"></i>
                        </button>
                    @endif
                </div>
                @if($isOwnProfile)
                    <button 
                        type="button"
                        onclick="showPhotoCategoryModal()"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-6 py-3 rounded-lg transition-colors flex items-center gap-2"
                    >
                        <i class="ri-add-line text-lg"></i>
                        Add Pictures
                    </button>
                @endif
            </div>
        </div>

        <!-- NON-ADULT Section -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <h3 class="text-xl font-bold text-blue-500 dark:text-blue-400 mb-2 uppercase">NON-ADULT</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6 text-sm">
                "Vanilla" photos are not sexually suggestive and don't contain nudity; they will appear on both our swingers app and website.
            </p>
            
            @if(!empty($nonAdultPhotos))
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                    @foreach($nonAdultPhotos as $index => $photo)
                        <div class="relative group">
                            <img 
                                src="{{ asset('storage/' . $photo) }}" 
                                alt="Non-adult photo" 
                                class="w-full h-48 object-cover rounded-lg border-2 border-gray-200 dark:border-gray-700 cursor-pointer hover:border-blue-500 transition-colors image-modal-trigger"
                                data-src="{{ asset('storage/' . $photo) }}"
                            />
                            @if($isOwnProfile)
                                <button 
                                    type="button"
                                    onclick="removePhoto('non_adult', '{{ $photo }}', this)"
                                    class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-2 opacity-0 group-hover:opacity-100 transition-opacity"
                                    title="Remove photo"
                                >
                                    <i class="ri-delete-bin-line text-sm"></i>
                                </button>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-gray-100 dark:bg-gray-700 rounded-xl p-12 text-center min-h-[300px] flex flex-col items-center justify-center">
                    <i class="ri-image-line text-6xl text-gray-400 dark:text-gray-500 mb-4"></i>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">
                        There are no photos in this category yet. Tap or click the Add Pictures button to upload some now!
                    </p>
                </div>
            @endif
        </div>

        <!-- ADULT Section -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <h3 class="text-xl font-bold text-blue-500 dark:text-blue-400 mb-2 uppercase">ADULT</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6 text-sm">
                Nude or suggestive pictures that will appear exclusively on our swingers website due to app store rules prohibiting adult photos.
            </p>
            
            @if(!empty($adultPhotos))
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                    @foreach($adultPhotos as $index => $photo)
                        <div class="relative group">
                            <img 
                                src="{{ asset('storage/' . $photo) }}" 
                                alt="Adult photo" 
                                class="w-full h-48 object-cover rounded-lg border-2 border-red-200 dark:border-red-700 cursor-pointer hover:border-red-500 transition-colors image-modal-trigger"
                                data-src="{{ asset('storage/' . $photo) }}"
                            />
                            @if($isOwnProfile)
                                <button 
                                    type="button"
                                    onclick="removePhoto('adult', '{{ $photo }}', this)"
                                    class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-2 opacity-0 group-hover:opacity-100 transition-opacity"
                                    title="Remove photo"
                                >
                                    <i class="ri-delete-bin-line text-sm"></i>
                                </button>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-gray-100 dark:bg-gray-700 rounded-xl p-12 text-center min-h-[300px] flex flex-col items-center justify-center">
                    <i class="ri-image-line text-6xl text-gray-400 dark:text-gray-500 mb-4"></i>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">
                        There are no photos in this category yet. Tap or click the Add Pictures button to upload some now!
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Photo Category Selection Modal -->
<div id="photoCategoryModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4" onclick="closePhotoCategoryModal()">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl max-w-md w-full p-6" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Add Pictures</h3>
            <button 
                type="button"
                onclick="closePhotoCategoryModal()"
                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
            >
                <i class="ri-close-line text-2xl"></i>
            </button>
        </div>
        
        <p class="text-gray-600 dark:text-gray-400 mb-6 text-sm">
            Select the category for the pictures you want to upload:
        </p>
        
        <div class="space-y-4">
            <button 
                type="button"
                onclick="selectPhotoCategory('non_adult')"
                class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold px-6 py-4 rounded-lg transition-colors flex items-center justify-center gap-2"
            >
                <i class="ri-image-line text-xl"></i>
                Non-Adult Photos
            </button>
            
            <button 
                type="button"
                onclick="selectPhotoCategory('adult')"
                class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold px-6 py-4 rounded-lg transition-colors flex items-center justify-center gap-2"
            >
                <i class="ri-image-line text-xl"></i>
                Adult Photos
            </button>
        </div>
    </div>
</div>

<!-- Upload Progress Modal -->
<div id="uploadProgressModal" class="fixed inset-0 bg-black/70 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-gray-800 dark:bg-gray-900 rounded-2xl shadow-xl max-w-md w-full p-8" onclick="event.stopPropagation()">
        <div class="text-center mb-6">
            <h3 class="text-xl font-semibold text-white mb-2">Uploading...</h3>
            <div class="w-full bg-gray-700 dark:bg-gray-600 rounded-full h-3 overflow-hidden">
                <div 
                    id="uploadProgressBar" 
                    class="bg-green-500 h-full rounded-full transition-all duration-300 ease-out relative"
                    style="width: 0%"
                >
                    <span id="uploadProgressText" class="absolute inset-0 flex items-center justify-center text-white text-xs font-semibold">0%</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hidden file inputs for photo uploads -->
@if($isOwnProfile)
    <input 
        type="file" 
        id="nonAdultPhotosInput" 
        name="non_adult_photos[]" 
        accept="image/jpeg,image/jpg,image/png,image/gif,image/webp" 
        multiple
        class="hidden"
        onchange="handlePhotoUpload(this, 'non_adult')"
    />
    <input 
        type="file" 
        id="adultPhotosInput" 
        name="adult_photos[]" 
        accept="image/jpeg,image/jpg,image/png,image/gif,image/webp" 
        multiple
        class="hidden"
        onchange="handlePhotoUpload(this, 'adult')"
    />
@endif

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black/90 z-50 hidden flex items-center justify-center p-4" onclick="closeImageModal()">
    <div class="relative max-w-7xl max-h-full">
        <button 
            onclick="closeImageModal()" 
            class="absolute top-4 right-4 text-white hover:text-gray-300 text-3xl z-10"
        >
            <i class="ri-close-line"></i>
        </button>
        <img id="modalImage" src="" alt="Full size" class="max-w-full max-h-[90vh] object-contain rounded-lg" onclick="event.stopPropagation()">
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add click handlers to all image modal triggers
    document.querySelectorAll('.image-modal-trigger').forEach(img => {
        img.addEventListener('click', function() {
            const imageSrc = this.getAttribute('data-src');
            openImageModal(imageSrc);
        });
    });
});

function openImageModal(imageSrc) {
    const modal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    modalImage.src = imageSrc;
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.classList.add('hidden');
    document.body.style.overflow = '';
}

function showPhotoCategoryModal() {
    const modal = document.getElementById('photoCategoryModal');
    if (modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
}

function closePhotoCategoryModal() {
    const modal = document.getElementById('photoCategoryModal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }
}

function selectPhotoCategory(category) {
    closePhotoCategoryModal();
    
    const inputId = category === 'non_adult' ? 'nonAdultPhotosInput' : 'adultPhotosInput';
    const input = document.getElementById(inputId);
    if (input) {
        input.click();
    }
}

function handlePhotoUpload(input, category) {
    if (input.files && input.files.length > 0) {
        const formData = new FormData();
        
        // Append all selected files
        Array.from(input.files).forEach((file, index) => {
            const fieldName = category === 'non_adult' ? 'non_adult_photos[]' : 'adult_photos[]';
            formData.append(fieldName, file);
        });
        
        formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}');
        formData.append('_method', 'PUT');
        
        // Show upload progress modal
        const progressModal = document.getElementById('uploadProgressModal');
        const progressBar = document.getElementById('uploadProgressBar');
        const progressText = document.getElementById('uploadProgressText');
        
        if (progressModal) {
            progressModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        
        // Update progress to 0%
        if (progressBar) {
            progressBar.style.width = '0%';
        }
        if (progressText) {
            progressText.textContent = '0%';
        }
        
        // Use XMLHttpRequest for progress tracking
        const xhr = new XMLHttpRequest();
        
        // Track upload progress
        xhr.upload.addEventListener('progress', function(e) {
            if (e.lengthComputable) {
                const percentComplete = Math.round((e.loaded / e.total) * 100);
                if (progressBar) {
                    progressBar.style.width = percentComplete + '%';
                }
                if (progressText) {
                    progressText.textContent = percentComplete + '%';
                }
            }
        });
        
        // Handle completion
        xhr.addEventListener('load', function() {
            if (xhr.status === 200 || xhr.status === 302) {
                // Update progress to 100%
                if (progressBar) {
                    progressBar.style.width = '100%';
                }
                if (progressText) {
                    progressText.textContent = '100%';
                }
                
                // Wait a moment to show 100%, then reload only the pictures tab
                setTimeout(() => {
                    if (progressModal) {
                        progressModal.classList.add('hidden');
                        document.body.style.overflow = '';
                    }
                    
                    // Reload only the pictures tab content without changing tabs
                    reloadPicturesTab();
                }, 500);
            } else {
                // Error handling
                if (progressModal) {
                    progressModal.classList.add('hidden');
                    document.body.style.overflow = '';
                }
                alert('Failed to upload photos. Please try again.');
            }
        });
        
        // Handle errors
        xhr.addEventListener('error', function() {
            if (progressModal) {
                progressModal.classList.add('hidden');
                document.body.style.overflow = '';
            }
            alert('An error occurred while uploading the photos');
        });
        
        // Send request
        xhr.open('POST', '{{ route("account.profile.update") }}');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.send(formData);
    }
}

function reloadPicturesTab() {
    // Store current tab state
    const currentTab = 'pictures'; // We're always on pictures tab when uploading
    
    // Reload the page but preserve the pictures tab
    // Use sessionStorage to remember we want to stay on pictures tab
    sessionStorage.setItem('activeProfileTab', 'pictures');
    
    // Reload the page - the tab switching script will read from sessionStorage
    location.reload();
}

function removePhoto(category, photoPath, buttonElement) {
    if (confirm('Are you sure you want to delete this photo?')) {
        const formData = new FormData();
        formData.append('deleted_' + category + '_photos[]', photoPath);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}');
        formData.append('_method', 'PUT');
        
        // Show loading state
        const photoDiv = buttonElement.closest('.relative.group');
        if (photoDiv) {
            photoDiv.style.opacity = '0.5';
            photoDiv.style.pointerEvents = 'none';
        }
        
        fetch('{{ route("account.profile.update") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        .then(response => {
            if (response.redirected) {
                window.location.href = response.url;
                return;
            }
            return response.json().catch(() => ({ success: true }));
        })
        .then(data => {
            if (data && data.success === false) {
                alert(data.message || 'Failed to delete photo');
                if (photoDiv) {
                    photoDiv.style.opacity = '1';
                    photoDiv.style.pointerEvents = 'auto';
                }
            } else {
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the photo');
            if (photoDiv) {
                photoDiv.style.opacity = '1';
                photoDiv.style.pointerEvents = 'auto';
            }
        });
    }
}

function changePrimaryPicture() {
    const input = document.getElementById('primaryPictureInput');
    if (input) {
        input.click();
    }
}

function handlePrimaryPictureChange(input) {
    if (input.files && input.files[0]) {
        const formData = new FormData();
        formData.append('profile_photo', input.files[0]);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}');
        formData.append('_method', 'PUT');
        
        // Show loading state
        const button = document.querySelector('button[onclick="changePrimaryPicture()"]');
        if (button) {
            const originalText = button.textContent;
            button.disabled = true;
            button.textContent = 'Uploading...';
            
            fetch('{{ route("account.profile.update") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                }
            })
            .then(response => {
                if (response.redirected) {
                    window.location.href = response.url;
                    return;
                }
                return response.json().catch(() => ({ success: true }));
            })
            .then(data => {
                if (data && data.success === false) {
                    alert(data.message || 'Failed to update profile picture');
                    button.disabled = false;
                    button.textContent = originalText;
                } else {
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while uploading the picture');
                button.disabled = false;
                button.textContent = originalText;
            });
        }
    }
}

function removePrimaryPicture() {
    if (confirm('Are you sure you want to remove your primary profile picture?')) {
        const formData = new FormData();
        formData.append('remove_profile_photo', '1');
        formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}');
        formData.append('_method', 'PUT');
        
        fetch('{{ route("account.profile.update") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        .then(response => {
            if (response.redirected) {
                window.location.href = response.url;
                return;
            }
            return response.json().catch(() => ({ success: true }));
        })
        .then(data => {
            if (data && data.success === false) {
                alert(data.message || 'Failed to remove profile picture');
            } else {
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while removing the picture');
        });
    }
}
</script>
