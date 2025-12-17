<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        @php
            // Get videos from profile
            $videos = [];
            
            if ($profile && $profile->videos) {
                $videos = is_array($profile->videos) 
                    ? $profile->videos 
                    : json_decode($profile->videos, true) ?? [];
            }
        @endphp

        <!-- VIDEOS Header Section -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white uppercase">VIDEOS</h2>
                </div>
                @if($isOwnProfile)
                    <button 
                        type="button"
                        onclick="document.getElementById('videoInput').click()"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-6 py-3 rounded-lg transition-colors flex items-center gap-2"
                    >
                        <i class="ri-add-line text-lg"></i>
                        Add Video
                    </button>
                @endif
            </div>
        </div>

        <!-- Videos Grid -->
        @if(!empty($videos))
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($videos as $index => $video)
                    <div class="relative group bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="relative aspect-video bg-gray-900">
                            <video 
                                src="{{ asset('storage/' . $video) }}" 
                                class="w-full h-full object-cover"
                                controls
                                preload="metadata"
                            >
                                Your browser does not support the video tag.
                            </video>
                            @if($isOwnProfile)
                                <button 
                                    type="button"
                                    onclick="removeVideo('{{ $video }}', this)"
                                    class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-2 opacity-0 group-hover:opacity-100 transition-opacity"
                                    title="Remove video"
                                >
                                    <i class="ri-delete-bin-line text-sm"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-12 text-center min-h-[400px] flex flex-col items-center justify-center">
                <i class="ri-video-line text-6xl text-gray-400 dark:text-gray-500 mb-4"></i>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">No Videos Yet</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm mb-6">
                    @if($isOwnProfile)
                        Tap or click the Add Video button to upload your first video!
                    @else
                        This user hasn't uploaded any videos yet.
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>

<!-- Hidden file input for video uploads -->
@if($isOwnProfile)
    <input 
        type="file" 
        id="videoInput" 
        name="videos[]" 
        accept="video/*" 
        class="hidden"
        onchange="handleVideoUpload(this)"
    />
@endif

<!-- Upload Progress Modal (reuse from pictures) -->
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
function handleVideoUpload(input) {
    if (input.files && input.files.length > 0) {
        const formData = new FormData();
        
        // Append all selected video files
        Array.from(input.files).forEach((file) => {
            formData.append('videos[]', file);
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
                
                // Wait a moment to show 100%, then reload only the videos tab
                setTimeout(() => {
                    if (progressModal) {
                        progressModal.classList.add('hidden');
                        document.body.style.overflow = '';
                    }
                    
                    // Reload only the videos tab content without changing tabs
                    reloadVideosTab();
                }, 500);
            } else {
                // Error handling
                if (progressModal) {
                    progressModal.classList.add('hidden');
                    document.body.style.overflow = '';
                }
                alert('Failed to upload video. Please try again.');
            }
        });
        
        // Handle errors
        xhr.addEventListener('error', function() {
            if (progressModal) {
                progressModal.classList.add('hidden');
                document.body.style.overflow = '';
            }
            alert('An error occurred while uploading the video');
        });
        
        // Send request
        xhr.open('POST', '{{ route("account.profile.update") }}');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.send(formData);
    }
}

function removeVideo(videoPath, buttonElement) {
    if (confirm('Are you sure you want to delete this video?')) {
        const formData = new FormData();
        formData.append('deleted_videos[]', videoPath);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}');
        formData.append('_method', 'PUT');
        
        // Show loading state
        const videoDiv = buttonElement.closest('.relative.group');
        if (videoDiv) {
            videoDiv.style.opacity = '0.5';
            videoDiv.style.pointerEvents = 'none';
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
                alert(data.message || 'Failed to delete video');
                if (videoDiv) {
                    videoDiv.style.opacity = '1';
                    videoDiv.style.pointerEvents = 'auto';
                }
            } else {
                reloadVideosTab();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the video');
            if (videoDiv) {
                videoDiv.style.opacity = '1';
                videoDiv.style.pointerEvents = 'auto';
            }
        });
    }
}

function reloadVideosTab() {
    // Store current tab state
    sessionStorage.setItem('activeProfileTab', 'videos');
    
    // Reload the page - the tab switching script will read from sessionStorage
    location.reload();
}
</script>

