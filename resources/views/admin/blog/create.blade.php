@extends('layouts.admin')

@section('title', 'Create Blog Post - Admin Panel')

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.blog.index') }}" class="text-sm text-gray-600 hover:text-primary dark:text-gray-400">
            ← Back to Blog Posts
        </a>
    </div>

    <h1 class="mb-6 text-3xl font-extrabold text-secondary">Create New Blog Post</h1>

    <div class="max-w-4xl rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <form action="{{ route('admin.blog.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label for="title" class="block text-sm font-medium">Title <span class="text-red-600">*</span></label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required class="mt-1 block w-full rounded-md border border-gray-300 bg-light px-3 py-2 shadow-sm focus:border-primary focus:outline-none focus:ring-primary dark:border-gray-600 dark:bg-gray-700">
                    @error('title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="slug" class="block text-sm font-medium">Slug <small class="text-gray-500">(auto-generated if empty)</small></label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug') }}" class="mt-1 block w-full rounded-md border border-gray-300 bg-light px-3 py-2 shadow-sm focus:border-primary focus:outline-none focus:ring-primary dark:border-gray-600 dark:bg-gray-700">
                    @error('slug')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="featured_image" class="block text-sm font-medium">Featured Image</label>
                    <input type="file" id="featured_image" name="featured_image" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp" class="mt-1 block w-full rounded-md border border-gray-300 bg-light px-3 py-2 shadow-sm focus:border-primary focus:outline-none focus:ring-primary dark:border-gray-600 dark:bg-gray-700">
                    @error('featured_image')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    <p class="mt-1 text-xs text-gray-500">Upload featured image (max 5MB, formats: jpeg, jpg, png, gif, webp)</p>
                    <div id="featured_image_preview" class="mt-2 hidden">
                        <img src="" alt="Preview" class="max-w-xs rounded-md border border-gray-300">
                    </div>
                </div>

                <div>
                    <label for="template" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Blog Template</label>
                    <select id="template" name="template" class="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-primary focus:outline-none focus:ring-primary dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        <option value="1" {{ old('template', 1) == 1 ? 'selected' : '' }}>Template 1 (Default)</option>
                        <option value="2" {{ old('template') == 2 ? 'selected' : '' }}>Template 2</option>
                        <option value="3" {{ old('template') == 3 ? 'selected' : '' }}>Template 3</option>
                    </select>
                    @error('template')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Choose the blog post design template</p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 border-t pt-6 dark:border-gray-700">
                <div>
                    <label for="categories" class="block text-sm font-medium mb-2">Categories</label>
                    <div class="max-h-48 overflow-y-auto border border-gray-300 rounded-md p-3 dark:border-gray-600 dark:bg-gray-700">
                        @forelse($categories as $category)
                            <label class="flex items-center gap-2 py-1">
                                <input type="checkbox" name="categories[]" value="{{ $category->id }}" {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary">
                                <span class="text-sm">{{ $category->name }}</span>
                            </label>
                        @empty
                            <p class="text-sm text-gray-500">No categories available. <a href="{{ route('admin.categories.create') }}" class="text-primary hover:underline">Create one</a></p>
                        @endforelse
                    </div>
                    @error('categories')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    <p class="mt-1 text-xs text-gray-500">Select one or more categories</p>
                </div>

                <div>
                    <label for="tags" class="block text-sm font-medium mb-2">Tags</label>
                    <div class="max-h-48 overflow-y-auto border border-gray-300 rounded-md p-3 dark:border-gray-600 dark:bg-gray-700">
                        @forelse($tags as $tag)
                            <label class="flex items-center gap-2 py-1">
                                <input type="checkbox" name="tags[]" value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary">
                                <span class="text-sm">{{ $tag->name }}</span>
                            </label>
                        @empty
                            <p class="text-sm text-gray-500">No tags available. <a href="{{ route('admin.tags.create') }}" class="text-primary hover:underline">Create one</a></p>
                        @endforelse
                    </div>
                    @error('tags')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    <p class="mt-1 text-xs text-gray-500">Select one or more tags</p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label for="excerpt" class="block text-sm font-medium">Excerpt</label>
                    <textarea id="excerpt" name="excerpt" rows="2" class="mt-1 block w-full rounded-md border border-gray-300 bg-light px-3 py-2 shadow-sm focus:border-primary focus:outline-none focus:ring-primary dark:border-gray-600 dark:bg-gray-700">{{ old('excerpt') }}</textarea>
                    @error('excerpt')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    <p class="mt-1 text-xs text-gray-500">Short summary of the post (max 500 characters)</p>
                </div>

                <div class="sm:col-span-2">
                    <label for="content" class="block text-sm font-medium">Content <span class="text-red-600">*</span></label>
                    <textarea id="content" name="content" rows="12" required class="mt-1 block w-full rounded-md border border-gray-300 bg-light px-3 py-2 shadow-sm focus:border-primary focus:outline-none focus:ring-primary dark:border-gray-600 dark:bg-gray-700">{{ old('content') }}</textarea>
                    @error('content')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    <p class="mt-1 text-xs text-gray-500">Use the rich text editor. Click Code View (< / >) to add HTML directly.</p>
                </div>
            </div>

            <div class="border-t pt-6 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">SEO Settings</h3>
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="meta_title" class="block text-sm font-medium">Meta Title</label>
                        <input type="text" id="meta_title" name="meta_title" value="{{ old('meta_title') }}" class="mt-1 block w-full rounded-md border border-gray-300 bg-light px-3 py-2 shadow-sm focus:border-primary focus:outline-none focus:ring-primary dark:border-gray-600 dark:bg-gray-700">
                        @error('meta_title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="meta_description" class="block text-sm font-medium">Meta Description</label>
                        <textarea id="meta_description" name="meta_description" rows="2" class="mt-1 block w-full rounded-md border border-gray-300 bg-light px-3 py-2 shadow-sm focus:border-primary focus:outline-none focus:ring-primary dark:border-gray-600 dark:bg-gray-700">{{ old('meta_description') }}</textarea>
                        @error('meta_description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="meta_keywords" class="block text-sm font-medium">Meta Keywords</label>
                        <input type="text" id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords') }}" placeholder="keyword1, keyword2, keyword3" class="mt-1 block w-full rounded-md border border-gray-300 bg-light px-3 py-2 shadow-sm focus:border-primary focus:outline-none focus:ring-primary dark:border-gray-600 dark:bg-gray-700">
                        @error('meta_keywords')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        <p class="mt-1 text-xs text-gray-500">Comma-separated keywords for SEO</p>
                    </div>
                </div>
            </div>

            <div class="border-t pt-6 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Open Graph (Social Media) Settings</h3>
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
                        <label for="og_title" class="block text-sm font-medium">OG Title</label>
                        <input type="text" id="og_title" name="og_title" value="{{ old('og_title') }}" class="mt-1 block w-full rounded-md border border-gray-300 bg-light px-3 py-2 shadow-sm focus:border-primary focus:outline-none focus:ring-primary dark:border-gray-600 dark:bg-gray-700">
                        @error('og_title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="og_image" class="block text-sm font-medium">OG Image</label>
                        <input type="file" id="og_image" name="og_image" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp" class="mt-1 block w-full rounded-md border border-gray-300 bg-light px-3 py-2 shadow-sm focus:border-primary focus:outline-none focus:ring-primary dark:border-gray-600 dark:bg-gray-700">
                        @error('og_image')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        <p class="mt-1 text-xs text-gray-500">Upload OG image for social sharing (max 5MB)</p>
                        <div id="og_image_preview" class="mt-2 hidden">
                            <img src="" alt="OG Preview" class="max-w-xs rounded-md border border-gray-300">
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <label for="og_description" class="block text-sm font-medium">OG Description</label>
                    <textarea id="og_description" name="og_description" rows="2" class="mt-1 block w-full rounded-md border border-gray-300 bg-light px-3 py-2 shadow-sm focus:border-primary focus:outline-none focus:ring-primary dark:border-gray-600 dark:bg-gray-700">{{ old('og_description') }}</textarea>
                    @error('og_description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="border-t pt-6 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Twitter Card Settings</h3>
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
                        <label for="twitter_title" class="block text-sm font-medium">Twitter Title</label>
                        <input type="text" id="twitter_title" name="twitter_title" value="{{ old('twitter_title') }}" class="mt-1 block w-full rounded-md border border-gray-300 bg-light px-3 py-2 shadow-sm focus:border-primary focus:outline-none focus:ring-primary dark:border-gray-600 dark:bg-gray-700">
                        @error('twitter_title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="twitter_image" class="block text-sm font-medium">Twitter Image</label>
                        <input type="file" id="twitter_image" name="twitter_image" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp" class="mt-1 block w-full rounded-md border border-gray-300 bg-light px-3 py-2 shadow-sm focus:border-primary focus:outline-none focus:ring-primary dark:border-gray-600 dark:bg-gray-700">
                        @error('twitter_image')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        <p class="mt-1 text-xs text-gray-500">Upload Twitter card image (max 5MB)</p>
                        <div id="twitter_image_preview" class="mt-2 hidden">
                            <img src="" alt="Twitter Preview" class="max-w-xs rounded-md border border-gray-300">
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <label for="twitter_description" class="block text-sm font-medium">Twitter Description</label>
                    <textarea id="twitter_description" name="twitter_description" rows="2" class="mt-1 block w-full rounded-md border border-gray-300 bg-light px-3 py-2 shadow-sm focus:border-primary focus:outline-none focus:ring-primary dark:border-gray-600 dark:bg-gray-700">{{ old('twitter_description') }}</textarea>
                    @error('twitter_description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <fieldset class="space-y-3 border-t pt-4 dark:border-gray-700">
                <legend class="text-sm font-medium">Post Options</legend>
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="is_published" value="1" {{ old('is_published', true) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary">
                    <span class="text-sm">Published (visible on blog)</span>
                </label>
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary">
                    <span class="text-sm">Featured Post</span>
                </label>
            </fieldset>

            <div class="flex items-center justify-end gap-3 border-t border-gray-200 pt-6 dark:border-gray-700">
                <a href="{{ route('admin.blog.index') }}" class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm transition-colors hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                    Cancel
                </a>
                <button type="submit" class="bg-[#FF8FA3] hover:bg-[#FF7A91] text-white px-6 py-2.5 rounded-xl font-semibold transition-colors flex items-center gap-2 shadow-md hover:shadow-lg">
                    <i class="ri-save-line text-lg"></i>
                    <span>Create Post</span>
                </button>
            </div>
        </form>
    </div>
@endsection

@push('head')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
    <script>
        {!! file_get_contents(resource_path('js/admin-editor.js')) !!}
    </script>
    <script>
        // Image preview functionality
        document.getElementById('featured_image')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('featured_image_preview');
                    preview.querySelector('img').src = e.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        });

        document.getElementById('og_image')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('og_image_preview');
                    preview.querySelector('img').src = e.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        });

        document.getElementById('twitter_image')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('twitter_image_preview');
                    preview.querySelector('img').src = e.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
@endpush

