<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        @php
            // Get albums for the user
            $albums = $user->albums ?? collect();
        @endphp

        <!-- ALBUM Header Section -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white uppercase">ALBUMS</h2>
                </div>
                @if($isOwnProfile)
                    <button 
                        type="button"
                        onclick="showCreateAlbumModal()"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-6 py-3 rounded-lg transition-colors flex items-center gap-2"
                    >
                        <i class="ri-add-line text-lg"></i>
                        Create Album
                    </button>
                @endif
            </div>
        </div>

        <!-- Albums Grid -->
        @if($albums->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($albums as $album)
                    <div class="relative group bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden cursor-pointer hover:shadow-xl transition-all" onclick="openAlbum({{ $album->id }})">
                        <!-- Album Cover Image -->
                        <div class="relative aspect-square bg-gray-900">
                            @php
                                $coverImage = $album->images->first();
                            @endphp
                            @if($coverImage)
                                <img 
                                    src="{{ asset('storage/' . $coverImage->image_path) }}" 
                                    alt="{{ $album->name }}"
                                    class="w-full h-full object-cover"
                                />
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-purple-500 to-pink-500">
                                    <i class="ri-folder-image-line text-6xl text-white opacity-50"></i>
                                </div>
                            @endif
                            
                            <!-- Private Badge -->
                            @if($album->is_private)
                                <div class="absolute top-2 right-2 bg-yellow-500 text-white rounded-full p-2">
                                    <i class="ri-lock-line text-sm"></i>
                                </div>
                            @endif
                            
                            <!-- Image Count Badge -->
                            @if($album->image_count > 0)
                                <div class="absolute bottom-2 right-2 bg-black/70 text-white text-xs font-semibold px-2 py-1 rounded-lg">
                                    {{ $album->image_count }} {{ $album->image_count === 1 ? 'photo' : 'photos' }}
                                </div>
                            @endif
                        </div>
                        
                        <!-- Album Info -->
                        <div class="p-4">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">{{ $album->name }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                @if($album->is_private)
                                    <i class="ri-lock-line"></i> Private
                                @else
                                    <i class="ri-global-line"></i> Public
                                @endif
                            </p>
                        </div>
                        
                        @if($isOwnProfile)
                            <!-- Delete Button -->
                            <button 
                                type="button"
                                onclick="event.stopPropagation(); deleteAlbum({{ $album->id }}, '{{ $album->name }}')"
                                class="absolute top-2 left-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-2 opacity-0 group-hover:opacity-100 transition-opacity"
                                title="Delete album"
                            >
                                <i class="ri-delete-bin-line text-sm"></i>
                            </button>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-12 text-center min-h-[400px] flex flex-col items-center justify-center">
                <i class="ri-folder-image-line text-6xl text-gray-400 dark:text-gray-500 mb-4"></i>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">No Albums Yet</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm mb-6">
                    @if($isOwnProfile)
                        Tap or click the Create Album button to create your first album!
                    @else
                        This user hasn't created any albums yet.
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>

<!-- Create Album Modal -->
<div id="createAlbumModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4" onclick="closeCreateAlbumModal()">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl max-w-md w-full p-6" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Create Album</h3>
            <button 
                type="button"
                onclick="closeCreateAlbumModal()"
                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
            >
                <i class="ri-close-line text-2xl"></i>
            </button>
        </div>
        
        <form id="createAlbumForm" onsubmit="createAlbum(event)">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Album Name</label>
                    <input 
                        type="text" 
                        id="albumName" 
                        name="name" 
                        required
                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Enter album name"
                    />
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Password (Optional)
                        <span class="text-xs text-gray-500 dark:text-gray-400">Leave empty for public album</span>
                    </label>
                    <input 
                        type="password" 
                        id="albumPassword" 
                        name="password" 
                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Enter password for private album"
                    />
                </div>
            </div>
            
            <div class="mt-6 flex gap-3">
                <button 
                    type="button"
                    onclick="closeCreateAlbumModal()"
                    class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-semibold py-3 px-6 rounded-xl transition-colors"
                >
                    Cancel
                </button>
                <button 
                    type="submit"
                    class="flex-1 bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-6 rounded-xl transition-colors"
                >
                    Create
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showCreateAlbumModal() {
    const modal = document.getElementById('createAlbumModal');
    if (modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
}

function closeCreateAlbumModal() {
    const modal = document.getElementById('createAlbumModal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
        // Reset form
        document.getElementById('createAlbumForm').reset();
    }
}

function createAlbum(event) {
    event.preventDefault();
    
    const formData = new FormData();
    formData.append('name', document.getElementById('albumName').value);
    const password = document.getElementById('albumPassword').value;
    if (password) {
        formData.append('password', password);
    }
    formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}');
    
    const submitButton = event.target.querySelector('button[type="submit"]');
    const originalText = submitButton.textContent;
    submitButton.disabled = true;
    submitButton.textContent = 'Creating...';
    
    fetch('{{ route("albums.create") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeCreateAlbumModal();
            // Store tab state and reload
            sessionStorage.setItem('activeProfileTab', 'album');
            // Small delay to ensure sessionStorage is set
            setTimeout(() => {
                location.reload();
            }, 100);
        } else {
            alert(data.message || 'Failed to create album');
            submitButton.disabled = false;
            submitButton.textContent = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while creating the album');
        submitButton.disabled = false;
        submitButton.textContent = originalText;
    });
}

function openAlbum(albumId) {
    // Navigate to album detail page
    window.location.href = '{{ route("albums.show", ":id") }}'.replace(':id', albumId);
}

function deleteAlbum(albumId, albumName) {
    if (confirm(`Are you sure you want to delete the album "${albumName}"? This will delete all images in the album.`)) {
        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}');
        formData.append('_method', 'DELETE');
        
        fetch('{{ route("albums.destroy", ":id") }}'.replace(':id', albumId), {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Store tab state and reload
                sessionStorage.setItem('activeProfileTab', 'album');
                // Small delay to ensure sessionStorage is set
                setTimeout(() => {
                    location.reload();
                }, 100);
            } else {
                alert(data.message || 'Failed to delete album');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the album');
        });
    }
}
</script>

