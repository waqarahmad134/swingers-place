@extends('layouts.app')

@section('title', $album->name . ' - Album - ' . config('app.name'))

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Album Header -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <a href="{{ route('account.profile') }}#album" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                            <i class="ri-arrow-left-line text-xl"></i>
                        </a>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $album->name }}</h1>
                        @if($album->is_private)
                            <span class="bg-yellow-500 text-white text-xs font-semibold px-2 py-1 rounded-full flex items-center gap-1">
                                <i class="ri-lock-line"></i> Private
                            </span>
                        @else
                            <span class="bg-green-500 text-white text-xs font-semibold px-2 py-1 rounded-full flex items-center gap-1">
                                <i class="ri-global-line"></i> Public
                            </span>
                        @endif
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $album->image_count }} {{ $album->image_count === 1 ? 'photo' : 'photos' }}
                    </p>
                </div>
                @if($isOwnAlbum)
                    <button 
                        type="button"
                        onclick="document.getElementById('albumImageInput').click()"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-6 py-3 rounded-lg transition-colors flex items-center gap-2"
                    >
                        <i class="ri-add-line text-lg"></i>
                        Add Images
                    </button>
                @endif
            </div>
        </div>

        <!-- Images Grid -->
        @if($album->images->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                @foreach($album->images as $image)
                    <div class="relative group bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <img 
                            src="{{ asset('storage/' . $image->image_path) }}" 
                            alt="Album image"
                            class="w-full h-64 object-cover cursor-pointer image-modal-trigger"
                            data-src="{{ asset('storage/' . $image->image_path) }}"
                        />
                        @if($isOwnAlbum)
                            <button 
                                type="button"
                                onclick="deleteImage({{ $album->id }}, {{ $image->id }}, this)"
                                class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-2 opacity-0 group-hover:opacity-100 transition-opacity"
                                title="Remove image"
                            >
                                <i class="ri-delete-bin-line text-sm"></i>
                            </button>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-12 text-center min-h-[400px] flex flex-col items-center justify-center">
                <i class="ri-image-line text-6xl text-gray-400 dark:text-gray-500 mb-4"></i>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">No Images Yet</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm mb-6">
                    @if($isOwnAlbum)
                        Tap or click the Add Images button to upload photos to this album!
                    @else
                        This album doesn't have any images yet.
                    @endif
                </p>
            </div>
        @endif

        <!-- Hidden file input for image uploads -->
        @if($isOwnAlbum)
            <input 
                type="file" 
                id="albumImageInput" 
                name="images[]" 
                accept="image/jpeg,image/jpg,image/png,image/gif,image/webp" 
                multiple
                class="hidden"
                onchange="handleAlbumImageUpload(this, {{ $album->id }})"
            />
        @endif
    </div>
</div>

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

function handleAlbumImageUpload(input, albumId) {
    if (input.files && input.files.length > 0) {
        const formData = new FormData();
        
        // Append all selected files
        Array.from(input.files).forEach((file) => {
            formData.append('images[]', file);
        });
        
        formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}');
        
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
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    // Update progress to 100%
                    if (progressBar) {
                        progressBar.style.width = '100%';
                    }
                    if (progressText) {
                        progressText.textContent = '100%';
                    }
                    
                    // Wait a moment to show 100%, then reload
                    setTimeout(() => {
                        if (progressModal) {
                            progressModal.classList.add('hidden');
                            document.body.style.overflow = '';
                        }
                        location.reload();
                    }, 500);
                } else {
                    if (progressModal) {
                        progressModal.classList.add('hidden');
                        document.body.style.overflow = '';
                    }
                    alert(response.message || 'Failed to upload images');
                }
            } else {
                if (progressModal) {
                    progressModal.classList.add('hidden');
                    document.body.style.overflow = '';
                }
                alert('Failed to upload images. Please try again.');
            }
        });
        
        // Handle errors
        xhr.addEventListener('error', function() {
            if (progressModal) {
                progressModal.classList.add('hidden');
                document.body.style.overflow = '';
            }
            alert('An error occurred while uploading the images');
        });
        
        // Send request
        xhr.open('POST', '{{ route("albums.upload-images", ":id") }}'.replace(':id', albumId));
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.send(formData);
    }
}

function deleteImage(albumId, imageId, buttonElement) {
    if (confirm('Are you sure you want to delete this image?')) {
        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}');
        formData.append('_method', 'DELETE');
        
        // Show loading state
        const imageDiv = buttonElement.closest('.relative.group');
        if (imageDiv) {
            imageDiv.style.opacity = '0.5';
            imageDiv.style.pointerEvents = 'none';
        }
        
        fetch('{{ route("albums.delete-image", [":albumId", ":imageId"]) }}'.replace(':albumId', albumId).replace(':imageId', imageId), {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Failed to delete image');
                if (imageDiv) {
                    imageDiv.style.opacity = '1';
                    imageDiv.style.pointerEvents = 'auto';
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the image');
            if (imageDiv) {
                imageDiv.style.opacity = '1';
                imageDiv.style.pointerEvents = 'auto';
            }
        });
    }
}
</script>
@endsection

