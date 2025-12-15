@extends(Auth::check() && Auth::user()->is_editor ? 'layouts.editor' : 'layouts.admin')

@section('title', 'Manage Users - Admin Panel')
@section('page-title', 'User Management')

@php
    $routePrefix = Auth::check() && Auth::user()->is_editor ? 'editor' : 'admin';
@endphp

@section('content')
    <!-- Heading -->
    <div class="pt-[14px] pb-4 md:pb-8 flex justify-between items-center">
        <div>
            <h2 class="text-[#0A0A0A] text-[24px] font-medium font-['poppins']">User Management</h2>
            <p class="text-[#717182] text-xs md:text-base font-['poppins']">Manage and moderate all user accounts</p>
        </div>
        <a href="{{ route($routePrefix . '.users.create') }}" class="bg-[#FF8FA3] hover:bg-[#FF7A91] text-white px-6 py-2.5 rounded-xl font-semibold transition-colors flex items-center gap-2">
            <i class="ri-user-add-line"></i>
            <span>Create User</span>
        </a>
    </div>

    <!-- Search and Filter Bar -->
    <div class="md:w-[98%] md:gap-0 gap-3 shadow-md flex-col md:flex-row md:justify-between flex py-4 px-3 rounded-2xl border border-[#0000001A]">
        <!-- Search Bar -->
        <form action="{{ route($routePrefix . '.users.index') }}" method="GET" class="flex">
            @if($status !== 'all')
                <input type="hidden" name="status" value="{{ $status }}" />
            @endif
            <div class="bg-[#F3F3F5] w-[40px] md:w-[50px] rounded-l-xl py-3 px-3 flex items-center justify-center">
                <img class="md:w-[20px] w-[20px]" src="{{ asset('admin-assets/search.png') }}" alt="Search" />
            </div>
            <input
                type="search"
                name="search"
                value="{{ $search }}"
                placeholder="Search by name or email..."
                class="py-2 font-['Poppins'] md:text-base text-xs active:border-none rounded-r-lg md:rounded-r-2xl active:outline-none px-1 bg-[#F3F3F5] min-w-[200px] md:min-w-[500px]"
            />
        </form>

        <!-- Filter Buttons -->
        <div class="flex justify-center flex-wrap gap-2">
            <a href="{{ route($routePrefix . '.users.index', array_merge(request()->except('status'), ['status' => 'all'])) }}" class="{{ $status === 'all' ? 'bg-[#FF8FA3] text-white' : 'border border-[#0000001A]' }} text-sm md:text-base font-['Poppins'] md:py-2 md:px-5 rounded-xl min-w-[48px] py-1 px-3">
                All
            </a>
            <a href="{{ route($routePrefix . '.users.index', array_merge(request()->except('status'), ['status' => 'active'])) }}" class="{{ $status === 'active' ? 'bg-[#FF8FA3] text-white' : 'border border-[#0000001A]' }} md:px-5 py-1 text-sm md:text-base md:py-2 font-['Poppins'] min-w-[86px] rounded-xl">
                Active
            </a>
            <a href="{{ route($routePrefix . '.users.index', array_merge(request()->except('status'), ['status' => 'pending'])) }}" class="{{ $status === 'pending' ? 'bg-[#FF8FA3] text-white' : 'border border-[#0000001A]' }} md:px-5 py-1 text-sm md:text-base md:py-2 font-['Poppins'] min-w-[86px] rounded-xl">
                Pending
            </a>
            <a href="{{ route($routePrefix . '.users.index', array_merge(request()->except('status'), ['status' => 'verified'])) }}" class="{{ $status === 'verified' ? 'bg-[#FF8FA3] text-white' : 'border border-[#0000001A]' }} md:px-5 py-1 text-sm md:text-base md:py-2 font-['Poppins'] min-w-[86px] rounded-xl">
                Verified
            </a>
            <a href="{{ route($routePrefix . '.users.index', array_merge(request()->except('status'), ['status' => 'banned'])) }}" class="{{ $status === 'banned' ? 'bg-[#FF8FA3] text-white' : 'border border-[#0000001A]' }} md:px-5 py-1 text-sm md:text-base md:py-2 font-['Poppins'] min-w-[86px] rounded-xl">
                Banned
            </a>
        </div>
    </div>

    <!-- Users Table -->
    <div class="w-full bg-white rounded-md shadow-sm border border-[#0000001A] overflow-auto mt-8">
        <table class="w-full text-sm text-gray-500">
            <!-- Table Header -->
            <thead class="text-sm text-[#0A0A0A] bg-[#FFF5F7] border-[#0000001A] border-b">
                <tr>
                    <th scope="col" class="py-6 px-6 text-left font-bold">User</th>
                    <th scope="col" class="py-3 px-6 text-left font-semibold">Email</th>
                    <th scope="col" class="py-3 px-6 text-left font-semibold hidden md:table-cell">Status</th>
                    <th scope="col" class="py-3 px-6 text-center font-semibold">Online</th>
                    <th scope="col" class="py-3 px-6 text-center font-semibold">Scheduled Offline</th>
                    <th scope="col" class="py-3 px-6 text-center font-semibold">Can Message</th>
                    <th scope="col" class="py-3 px-6 text-left font-semibold hidden lg:table-cell">Joined Date</th>
                    <th scope="col" class="py-3 px-6 text-center font-semibold">Actions</th>
                </tr>
            </thead>

            <!-- Table Body -->
            <tbody>
                @forelse ($users as $user)
                    @php
                        $profile = $user->profile;
                        $location = $profile && $profile->home_location ? $profile->home_location : 'N/A';
                        $isOnline = $user->isOnline();
                        
                        // Determine user status
                        $userStatus = 'pending';
                        if (!$user->is_active) {
                            $userStatus = 'banned';
                        } elseif ($user->is_admin) {
                            $userStatus = 'verified';
                        } elseif ($user->email_verified_at) {
                            $userStatus = 'active';
                        }
                    @endphp
                    <tr class="border-b hover:bg-gray-50 text-gray-900 transition-colors duration-150">
                        <td class="py-4 px-6 font-medium whitespace-nowrap">
                            <div class="flex items-center space-x-3">
                                <!-- Avatar -->
                                @if($user->profile_image)
                                    <img src="{{ asset('storage/' . $user->profile_image) }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-full object-cover" />
                                @else
                                    <div class="w-10 h-10 bg-gradient-to-b from-[#FF8FA3] to-[#FF6F61] rounded-full flex items-center justify-center text-white text-sm font-semibold shadow-inner">
                                        {{ strtoupper(substr($user->first_name ?? $user->name, 0, 1)) }}{{ strtoupper(substr($user->last_name ?? '', 0, 1)) }}
                                    </div>
                                @endif
                                <!-- Name & Location -->
                                <div>
                                    <div class="font-semibold text-gray-900">{{ $user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $location }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">{{ $user->email }}</td>
                        <td class="py-4 px-6 hidden md:table-cell">
                            @if($userStatus === 'verified')
                                <span class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800 ring-1 ring-blue-500/30">Verified</span>
                            @elseif($userStatus === 'active')
                                <span class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 ring-1 ring-green-500/30">Active</span>
                            @elseif($userStatus === 'pending')
                                <span class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800 ring-1 ring-yellow-500/30">Pending</span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800 ring-1 ring-red-500/30">Banned</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-center">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" 
                                       class="sr-only peer online-status-toggle" 
                                       data-user-id="{{ $user->id }}"
                                       {{ $isOnline ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-green-500"></div>
                            </label>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <div class="flex flex-col gap-2 items-center">
                                <input type="datetime-local" 
                                       class="scheduled-offline-input text-xs border border-gray-300 rounded px-2 py-1 w-full max-w-[180px]" 
                                       data-user-id="{{ $user->id }}"
                                       value="{{ $user->scheduled_offline_at ? $user->scheduled_offline_at->format('Y-m-d\TH:i') : '' }}"
                                       placeholder="Set offline time">
                                @if($user->scheduled_offline_at)
                                    <button type="button" 
                                            class="clear-scheduled-offline text-xs text-red-600 hover:text-red-800 underline"
                                            data-user-id="{{ $user->id }}">
                                        Clear
                                    </button>
                                @endif
                            </div>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" 
                                       class="sr-only peer message-block-toggle" 
                                       data-user-id="{{ $user->id }}"
                                       {{ ($user->can_message ?? true) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-500"></div>
                            </label>
                        </td>
                        <td class="py-4 px-6 hidden lg:table-cell">{{ $user->created_at->format('Y-m-d') }}</td>
                        <td class="py-4 px-6 text-center">
                            <div class="group relative inline-block">
                                <button type="button" class="text-gray-500 hover:text-gray-900 p-1 rounded-full hover:bg-gray-200 transition-colors focus:outline-none" aria-expanded="false">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zm0 6a2 2 0 110-4 2 2 0 010 4zm0 6a2 2 0 110-4 2 2 0 010 4z"></path>
                                    </svg>
                                </button>

                                <!-- Dropdown -->
                                <div class="hidden group-hover:block absolute px-2 right-5 top-1 z-10 mt-2 w-48 border border-[#A1A1A1] rounded-md bg-white py-2 ring-1 ring-black ring-opacity-5 focus:outline-none">
                                    <!-- Edit Profile -->
                                    <div class="group/item">
                                        <button type="button" onclick="openEditModal({{ $user->id }})" class="w-full text-left flex gap-2 items-center px-4 py-3 border-b border-[#A1A1A1] md:text-base text-sm text-[#595959] hover:bg-[#FF7166] hover:rounded-xl transition-colors">
                                            <img src="{{ asset('admin-assets/eye-empty.png') }}" width="24" alt="" class="transition-all group-hover/item:invert group-hover/item:brightness-0" />
                                            <span class="transition-colors group-hover/item:text-white">Edit Profile</span>
                                        </button>
                                    </div>

                                    <!-- Verify Account -->
                                    <div class="group/item">
                                        <button type="button" onclick="verifyUser({{ $user->id }}, {{ $user->email_verified_at ? 'true' : 'false' }})" class="w-full text-left flex gap-2 items-center px-4 py-3 border-b border-[#A1A1A1] md:text-base text-sm text-[#595959] hover:bg-[#FF7166] hover:rounded-xl transition-colors verify-user-btn" data-user-id="{{ $user->id }}">
                                            <img src="{{ asset('admin-assets/edit.png') }}" width="24" alt="" class="transition-all group-hover/item:invert group-hover/item:brightness-0" />
                                            <span class="transition-colors group-hover/item:text-white verify-user-text">{{ $user->email_verified_at ? 'Unverify Account' : 'Verify Account' }}</span>
                                        </button>
                                    </div>

                                    <!-- Activate/Ban User -->
                                    @if ($user->id !== auth()->id())
                                        <div class="group/item">
                                            <button type="button" onclick="toggleUserStatus({{ $user->id }}, {{ $user->is_active ? 'true' : 'false' }})" class="w-full text-left flex gap-2 items-center px-4 py-3 border-b border-[#A1A1A1] md:text-base text-sm text-[#595959] hover:bg-[#FF7166] hover:rounded-xl transition-colors toggle-status-btn" data-user-id="{{ $user->id }}">
                                                <img src="{{ asset('admin-assets/edit.png') }}" width="24" alt="" class="transition-all group-hover/item:invert group-hover/item:brightness-0" />
                                                <span class="transition-colors group-hover/item:text-white toggle-status-text">{{ $user->is_active ? 'Ban User' : 'Activate User' }}</span>
                                            </button>
                                        </div>
                                    @endif

                                    <!-- Delete User -->
                                    @if ($user->id !== auth()->id())
                                        <div class="group/item">
                                            <button type="button" onclick="deleteUser({{ $user->id }})" class="w-full text-left flex gap-2 items-center px-4 py-3 border-b border-[#A1A1A1] md:text-base text-sm text-[#595959] hover:bg-[#FF7166] hover:rounded-xl transition-colors delete-user-btn" data-user-id="{{ $user->id }}">
                                                <img src="{{ asset('admin-assets/edit.png') }}" width="24" alt="" class="transition-all group-hover/item:invert group-hover/item:brightness-0" />
                                                <span class="transition-colors group-hover/item:text-white">Delete User</span>
                                            </button>
                                                <button type="submit" class="w-full text-left flex gap-2 items-center px-4 py-3 md:text-base text-sm text-[#595959] hover:bg-[#FF7166] hover:rounded-xl transition-colors">
                                                    <img src="{{ asset('admin-assets/delete.png') }}" width="24" alt="" class="transition-all group-hover/item:invert group-hover/item:brightness-0" />
                                                    <span class="transition-colors group-hover/item:text-white">Delete User</span>
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-10 px-6 text-center text-sm text-gray-500">
                            No users found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if ($users->hasPages())
        <div class="mt-6">
            {{ $users->links() }}
        </div>
    @endif

    <!-- Edit User Profile Modal -->
    <div id="editUserModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75 backdrop-blur-sm" onclick="closeEditModal()"></div>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
                <!-- Modal Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-[#0A0A0A]">Edit User Profile</h3>
                    <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="ri-close-line text-2xl"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <form id="editUserForm" method="POST" enctype="multipart/form-data" class="px-6 py-4">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_user_id" name="user_id" value="">

                    <!-- Two Column Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <!-- First Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                            <div class="relative">
                                <input type="text" id="edit_first_name" name="first_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FF8FA3]">
                                <i class="ri-pencil-line absolute right-3 top-2.5 text-gray-400"></i>
                            </div>
                        </div>

                        <!-- Last Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                            <input type="text" id="edit_last_name" name="last_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FF8FA3]">
                        </div>

                        <!-- Categories -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Categories</label>
                            <select id="edit_category" name="category" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FF8FA3]">
                                <option value="">Select Category</option>
                                <option value="couple">Couple</option>
                                <option value="single_female">Single Female</option>
                                <option value="single_male">Single Male</option>
                                <option value="bisexual">Bisexual</option>
                                <option value="transgender">Transgender</option>
                            </select>
                        </div>

                        <!-- Age -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Age</label>
                            <input type="number" id="edit_age" name="age" min="18" max="120" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FF8FA3]">
                        </div>

                        <!-- Location -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                            <div class="relative">
                                <input type="text" id="edit_location" name="location" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FF8FA3]">
                                <i class="ri-pencil-line absolute right-3 top-2.5 text-gray-400"></i>
                            </div>
                        </div>

                        <!-- Gender -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                            <select id="edit_gender" name="gender" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FF8FA3]">
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                                <option value="prefer_not_to_say">Prefer not to say</option>
                            </select>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <div class="relative">
                            <input type="email" id="edit_email" name="email" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FF8FA3]">
                            <i class="ri-pencil-line absolute right-3 top-2.5 text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Bio -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bio</label>
                        <div class="relative">
                            <textarea id="edit_bio" name="bio" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FF8FA3]"></textarea>
                            <i class="ri-pencil-line absolute right-3 top-2.5 text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Profile Picture -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Profile Picture</label>
                        <div class="flex items-center gap-4">
                            <div id="profile_picture_preview" class="w-24 h-24 rounded-lg bg-gradient-to-b from-[#FF8FA3] to-[#FF6F61] flex items-center justify-center text-white text-2xl font-bold">
                                <span id="profile_initials">E</span>
                            </div>
                            <label class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                <i class="ri-upload-cloud-line"></i>
                                <span>Change Photo</span>
                                <input type="file" id="edit_profile_image" name="profile_image" accept="image/*" class="hidden" onchange="previewImage(this, 'profile_picture_preview', 'profile_initials')">
                            </label>
                        </div>
                    </div>


                    <!-- Action Buttons -->
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                        <button type="button" onclick="closeEditModal()" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-2 bg-[#FF8FA3] text-white rounded-lg hover:bg-[#FF7A91] transition-colors">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function openEditModal(userId) {
            // Fetch user data
            const baseUrl = '{{ url("/' . $routePrefix . '/users") }}';
            fetch(`${baseUrl}/${userId}/data`)
                .then(response => response.json())
                .then(data => {
                    // Populate form fields
                    document.getElementById('edit_user_id').value = data.id;
                    document.getElementById('edit_first_name').value = data.first_name || '';
                    document.getElementById('edit_last_name').value = data.last_name || '';
                    document.getElementById('edit_category').value = data.category || '';
                    document.getElementById('edit_age').value = data.age || '';
                    document.getElementById('edit_location').value = data.location || '';
                    document.getElementById('edit_gender').value = data.gender || '';
                    document.getElementById('edit_email').value = data.email || '';
                    document.getElementById('edit_bio').value = data.bio || '';
                    
                    // Update form action
                    document.getElementById('editUserForm').action = `${baseUrl}/${userId}`;
                    
                    // Set profile picture
                    const profilePreview = document.getElementById('profile_picture_preview');
                    const profileInitials = document.getElementById('profile_initials');
                    if (data.profile_image || data.profile_photo) {
                        profilePreview.innerHTML = `<img src="${data.profile_image || data.profile_photo}" alt="Profile" class="w-full h-full rounded-lg object-cover">`;
                    } else {
                        const initials = (data.first_name ? data.first_name.charAt(0) : '') + (data.last_name ? data.last_name.charAt(0) : '') || 'U';
                        profilePreview.innerHTML = `<span class="text-white text-2xl font-bold">${initials}</span>`;
                        profileInitials.textContent = initials;
                    }
                    
                    
                    // Show modal
                    document.getElementById('editUserModal').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error fetching user data:', error);
                    alert('Failed to load user data');
                });
        }

        function closeEditModal() {
            document.getElementById('editUserModal').classList.add('hidden');
        }

        function previewImage(input, previewId, initialsId) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById(previewId);
                    preview.innerHTML = `<img src="${e.target.result}" alt="Preview" class="w-full h-full rounded-lg object-cover">`;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeEditModal();
            }
        });

        // Handle online status toggle
        document.addEventListener('DOMContentLoaded', function() {
            const toggles = document.querySelectorAll('.online-status-toggle');
            
            toggles.forEach(toggle => {
                toggle.addEventListener('change', function() {
                    const userId = this.dataset.userId;
                    const isOnline = this.checked;
                    
                    // Disable toggle during request
                    this.disabled = true;
                    
                    fetch(`/{{ $routePrefix }}/users/${userId}/toggle-online-status`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            is_online: isOnline
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update toggle state based on response
                            this.checked = data.is_online;
                            // Show success toast
                            if (window.showToast) {
                                window.showToast(data.message || (data.is_online ? 'User set to online' : 'User set to offline'), 'success');
                            }
                        } else {
                            // Revert toggle on error
                            this.checked = !isOnline;
                            if (window.showToast) {
                                window.showToast(data.message || 'Failed to update online status', 'error');
                            } else {
                                alert('Failed to update online status');
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        // Revert toggle on error
                        this.checked = !isOnline;
                        if (window.showToast) {
                            window.showToast('Failed to update online status', 'error');
                        } else {
                            alert('Failed to update online status');
                        }
                    })
                    .finally(() => {
                        // Re-enable toggle
                        this.disabled = false;
                    });
                });
            });
        });

        // Handle scheduled offline datetime input
        document.addEventListener('DOMContentLoaded', function() {
            const scheduledInputs = document.querySelectorAll('.scheduled-offline-input');
            
            scheduledInputs.forEach(input => {
                let timeout;
                
                input.addEventListener('change', function() {
                    const userId = this.dataset.userId;
                    const scheduledTime = this.value;
                    
                    clearTimeout(timeout);
                    
                    // Debounce the request
                    timeout = setTimeout(() => {
                        fetch(`/{{ $routePrefix }}/users/${userId}/set-scheduled-offline`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                scheduled_offline_at: scheduledTime || null
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Show success toast
                                if (window.showToast) {
                                    window.showToast(data.message || 'Scheduled offline time set successfully', 'success');
                                }
                                // Reload page to update online status
                                setTimeout(() => {
                                    location.reload();
                                }, 500);
                            } else {
                                if (window.showToast) {
                                    window.showToast(data.message || 'Failed to set scheduled offline time', 'error');
                                } else {
                                    alert(data.message || 'Failed to set scheduled offline time');
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            if (window.showToast) {
                                window.showToast('An error occurred. Please try again.', 'error');
                            } else {
                                alert('An error occurred. Please try again.');
                            }
                        });
                    }, 500);
                });
            });

            // Handle clear scheduled offline button
            const clearButtons = document.querySelectorAll('.clear-scheduled-offline');
            
            clearButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const userId = this.dataset.userId;
                    const input = document.querySelector(`.scheduled-offline-input[data-user-id="${userId}"]`);
                    
                    if (input) {
                        input.value = '';
                        
                        fetch(`/{{ $routePrefix }}/users/${userId}/set-scheduled-offline`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                scheduled_offline_at: null
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Show success toast
                                if (window.showToast) {
                                    window.showToast(data.message || 'Scheduled offline time cleared', 'success');
                                }
                                // Reload page to update
                                location.reload();
                            } else {
                                if (window.showToast) {
                                    window.showToast(data.message || 'Failed to clear scheduled offline time', 'error');
                                } else {
                                    alert(data.message || 'Failed to clear scheduled offline time');
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            if (window.showToast) {
                                window.showToast('An error occurred. Please try again.', 'error');
                            } else {
                                alert('An error occurred. Please try again.');
                            }
                        });
                    }
                });
            });
        });

        // Handle message block toggle
        document.addEventListener('DOMContentLoaded', function() {
            const messageToggles = document.querySelectorAll('.message-block-toggle');
            
            messageToggles.forEach(toggle => {
                toggle.addEventListener('change', function() {
                    const userId = this.dataset.userId;
                    const canMessage = this.checked;
                    
                    // Disable toggle during request
                    this.disabled = true;
                    
                    fetch(`/{{ $routePrefix }}/users/${userId}/toggle-message-block`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            can_message: canMessage
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update toggle state based on response
                            this.checked = data.can_message;
                            // Show success toast
                            if (window.showToast) {
                                window.showToast(data.message || (data.can_message ? 'User messaging enabled' : 'User messaging blocked'), 'success');
                            }
                        } else {
                            // Revert toggle on error
                            this.checked = !canMessage;
                            if (window.showToast) {
                                window.showToast(data.message || 'Failed to update message block status', 'error');
                            } else {
                                alert(data.message || 'Failed to update message block status');
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        // Revert toggle on error
                        this.checked = !canMessage;
                        if (window.showToast) {
                            window.showToast('Failed to update message block status', 'error');
                        } else {
                            alert('Failed to update message block status');
                        }
                    })
                    .finally(() => {
                        // Re-enable toggle
                        this.disabled = false;
                    });
                });
            });
        });

        // Verify/Unverify User
        function verifyUser(userId, isVerified) {
            if (!confirm(isVerified ? 'Are you sure you want to unverify this user?' : 'Are you sure you want to verify this user?')) {
                return;
            }

            const button = document.querySelector(`.verify-user-btn[data-user-id="${userId}"]`);
            const textSpan = button?.querySelector('.verify-user-text');
            
            if (button) button.disabled = true;

            fetch(`/{{ $routePrefix }}/users/${userId}/verify`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                }
            })
            .then(response => {
                if (response.ok) {
                    return response.json();
                }
                throw new Error('Network response was not ok');
            })
            .then(data => {
                if (data.success) {
                    if (window.showToast) {
                        window.showToast(data.message || (isVerified ? 'User account unverified successfully!' : 'User account verified successfully!'), 'success');
                    }
                    // Reload page to update UI
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    if (window.showToast) {
                        window.showToast(data.message || 'Failed to update verification status', 'error');
                    }
                    if (button) button.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (window.showToast) {
                    window.showToast('Failed to update verification status', 'error');
                } else {
                    alert('Failed to update verification status');
                }
                if (button) button.disabled = false;
            });
        }

        // Toggle User Status (Activate/Ban)
        function toggleUserStatus(userId, isActive) {
            if (!confirm(isActive ? 'Are you sure you want to ban this user?' : 'Are you sure you want to activate this user?')) {
                return;
            }

            const button = document.querySelector(`.toggle-status-btn[data-user-id="${userId}"]`);
            const textSpan = button?.querySelector('.toggle-status-text');
            
            if (button) button.disabled = true;

            fetch(`/{{ $routePrefix }}/users/${userId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                }
            })
            .then(response => {
                if (response.ok) {
                    return response.json();
                }
                throw new Error('Network response was not ok');
            })
            .then(data => {
                if (data.success) {
                    if (window.showToast) {
                        window.showToast(data.message || (isActive ? 'User banned successfully!' : 'User activated successfully!'), 'success');
                    }
                    // Reload page to update UI
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    if (window.showToast) {
                        window.showToast(data.message || 'Failed to update user status', 'error');
                    } else {
                        alert(data.message || 'Failed to update user status');
                    }
                    if (button) button.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (window.showToast) {
                    window.showToast('Failed to update user status', 'error');
                } else {
                    alert('Failed to update user status');
                }
                if (button) button.disabled = false;
            });
        }

        // Delete User
        function deleteUser(userId) {
            if (!confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
                return;
            }

            const button = document.querySelector(`.delete-user-btn[data-user-id="${userId}"]`);
            if (button) button.disabled = true;

            fetch(`/{{ $routePrefix }}/users/${userId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                }
            })
            .then(response => {
                if (response.ok) {
                    return response.json();
                }
                throw new Error('Network response was not ok');
            })
            .then(data => {
                if (data.success) {
                    if (window.showToast) {
                        window.showToast(data.message || 'User deleted successfully!', 'success');
                    }
                    // Reload page to update UI
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    if (window.showToast) {
                        window.showToast(data.message || 'Failed to delete user', 'error');
                    } else {
                        alert(data.message || 'Failed to delete user');
                    }
                    if (button) button.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (window.showToast) {
                    window.showToast('Failed to delete user', 'error');
                } else {
                    alert('Failed to delete user');
                }
                if (button) button.disabled = false;
            });
        }
    </script>
    @endpush
@endsection
