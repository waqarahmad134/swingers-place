@extends(Auth::check() && Auth::user()->is_editor ? 'layouts.editor' : 'layouts.admin')

@section('title', 'Edit User - Admin Panel')

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-600 hover:text-primary dark:text-gray-400">
            ← Back to Users
        </a>
    </div>

    <h1 class="mb-6 text-3xl font-extrabold text-secondary">Edit User: {{ $user->name }}</h1>

    <div class="max-w-5xl rounded-lg border border-gray-200 bg-white p-6 shadow-sm text-gray-900 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
        <form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 border-b pb-2 dark:border-gray-700">Profile Image</h2>
                <div class="flex items-start gap-6">
                    <div class="shrink-0">
                        @if($user->profile_image)
                            <img src="{{ asset('storage/' . $user->profile_image) }}" alt="{{ $user->name }}" class="h-32 w-32 rounded-full object-cover border-2 border-gray-300 dark:border-gray-600">
                        @else
                            <div class="flex h-32 w-32 items-center justify-center rounded-full bg-gray-200 text-gray-500 dark:bg-gray-700 dark:text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <label for="profile_image" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Profile Image</label>
                        <input type="file" id="profile_image" name="profile_image" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp" class="mt-1 block w-full text-sm text-gray-900 file:mr-4 file:rounded-md file:border-0 file:bg-primary file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-primary-dark dark:text-gray-300">
                        @error('profile_image')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Max size: 5MB. Supported formats: JPEG, PNG, GIF, WebP</p>
                        @if($user->profile_image)
                            <label class="mt-2 flex items-center gap-2">
                                <input type="checkbox" name="remove_profile_image" value="1" class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary">
                                <span class="text-sm text-gray-700 dark:text-gray-300">Remove current image</span>
                            </label>
                        @endif
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 border-b pb-2 dark:border-gray-700">Basic Information</h2>
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name <span class="text-red-600">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required class="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-primary focus:outline-none focus:ring-primary dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email <span class="text-red-600">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required class="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-primary focus:outline-none focus:ring-primary dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone</label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" class="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-primary focus:outline-none focus:ring-primary dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Gender</label>
                        <select id="gender" name="gender" class="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-primary focus:outline-none focus:ring-primary dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender', $user->gender) === 'other' ? 'selected' : '' }}>Other</option>
                            <option value="prefer_not_to_say" {{ old('gender', $user->gender) === 'prefer_not_to_say' ? 'selected' : '' }}>Prefer not to say</option>
                        </select>
                        @error('gender')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="profile_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Profile Type</label>
                        <select id="profile_type" name="profile_type" class="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-primary focus:outline-none focus:ring-primary dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            <option value="normal" {{ old('profile_type', $user->profile_type ?? 'normal') === 'normal' ? 'selected' : '' }}>Normal</option>
                            <option value="business" {{ old('profile_type', $user->profile_type ?? 'normal') === 'business' ? 'selected' : '' }}>Business</option>
                        </select>
                        @error('profile_type')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Normal profiles are visible on the homepage</p>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 border-b pb-2 dark:border-gray-700">Business Information</h2>
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
                        <label for="company" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Company</label>
                        <input type="text" id="company" name="company" value="{{ old('company', $user->company) }}" class="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-primary focus:outline-none focus:ring-primary dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        @error('company')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="website_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Website URL</label>
                        <input type="url" id="website_url" name="website_url" value="{{ old('website_url', $user->website_url) }}" placeholder="https://example.com" class="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-primary focus:outline-none focus:ring-primary dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        @error('website_url')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Address</label>
                        <textarea id="address" name="address" rows="3" class="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-primary focus:outline-none focus:ring-primary dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('address', $user->address) }}</textarea>
                        @error('address')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="business_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Business Address</label>
                        <textarea id="business_address" name="business_address" rows="3" class="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-primary focus:outline-none focus:ring-primary dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('business_address', $user->business_address) }}</textarea>
                        @error('business_address')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 border-b pb-2 dark:border-gray-700">Security & Access</h2>
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
                        <label for="ssn" class="block text-sm font-medium text-gray-700 dark:text-gray-300">SSN</label>
                        <input type="text" id="ssn" name="ssn" value="{{ old('ssn', $user->ssn) }}" class="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-primary focus:outline-none focus:ring-primary dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        @error('ssn')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Keep this information secure and private</p>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">New Password</label>
                        <input type="password" id="password" name="password" class="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-primary focus:outline-none focus:ring-primary dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Leave empty to keep current password</p>
            </div>

            <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirm New Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-primary focus:outline-none focus:ring-primary dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>
                </div>
            </div>

            <fieldset class="space-y-3 border-t pt-4 dark:border-gray-700">
                <legend class="text-sm font-semibold text-gray-900 dark:text-gray-100">User Settings</legend>
                <div class="flex flex-wrap gap-6">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="is_admin" value="1" {{ old('is_admin', $user->is_admin) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary">
                        <span class="text-sm text-gray-700 dark:text-gray-300">Admin (can access admin panel)</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active ?? true) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary">
                        <span class="text-sm text-gray-700 dark:text-gray-300">Active (user can login)</span>
                </label>
                </div>
            </fieldset>

            <div class="flex gap-3 border-t pt-4 dark:border-gray-700">
                <button type="submit" class="rounded-md bg-primary px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-secondary">
                    Update User
                </button>
                <a href="{{ route('admin.users.index') }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition-colors hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        // Profile image preview
        document.getElementById('profile_image')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.querySelector('.shrink-0 img, .shrink-0 div');
                    if (preview) {
                        if (preview.tagName === 'IMG') {
                            preview.src = e.target.result;
                        } else {
                            // Replace div with img
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.className = 'h-32 w-32 rounded-full object-cover border-2 border-gray-300 dark:border-gray-600';
                            img.alt = '{{ $user->name }}';
                            preview.parentNode.replaceChild(img, preview);
                        }
                    }
                };
                reader.readAsDataURL(file);
            }
        });

    </script>
    @endpush
@endsection

