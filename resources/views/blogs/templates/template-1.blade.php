{{-- Blog Template 1: Default/Centered Layout --}}
<div class="mx-auto max-w-4xl">
    @if($blog->featured_image)
        <div class="mb-8">
            <img src="{{ asset($blog->featured_image) }}" alt="{{ $blog->title }}" class="w-full h-96 object-cover rounded-lg shadow-lg">
        </div>
    @endif

    <div class="mb-6">
        <h1 class="text-4xl font-extrabold text-secondary dark:text-primary mb-4">{{ $blog->title }}</h1>
        
        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 dark:text-gray-400 mb-4">
            <div class="flex items-center gap-2">
                <i class="ri-user-line"></i>
                <span>{{ $blog->author->name ?? 'Unknown' }}</span>
            </div>
            <div class="flex items-center gap-2">
                <i class="ri-calendar-line"></i>
                <span>{{ $blog->created_at->format('F d, Y') }}</span>
            </div>
            @if($blog->categories->count() > 0)
                <div class="flex items-center gap-2">
                    <i class="ri-folder-line"></i>
                    <div class="flex flex-wrap gap-1">
                        @foreach($blog->categories as $category)
                            <span class="inline-flex rounded-full bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900/40 dark:text-blue-300">{{ $category->name }}</span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        @if($blog->excerpt)
            <p class="text-lg text-gray-700 dark:text-gray-300 italic mb-6">{{ $blog->excerpt }}</p>
        @endif
    </div>

    <div class="rounded-lg border border-gray-200 bg-white p-8 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <div class="prose prose-lg dark:prose-invert max-w-none prose-headings:text-gray-900 dark:prose-headings:text-gray-100 prose-p:text-gray-700 dark:prose-p:text-gray-300 prose-strong:text-gray-900 dark:prose-strong:text-gray-100 prose-a:text-primary dark:prose-a:text-primary prose-ul:text-gray-700 dark:prose-ul:text-gray-300 prose-ol:text-gray-700 dark:prose-ol:text-gray-300 prose-li:text-gray-700 dark:prose-li:text-gray-300 prose-code:text-gray-900 dark:prose-code:text-gray-100 prose-pre:bg-gray-100 dark:prose-pre:bg-gray-900 prose-blockquote:text-gray-700 dark:prose-blockquote:text-gray-300">
            {!! $blog->content !!}
        </div>
    </div>

    @if($blog->tags->count() > 0)
        <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
            <div class="flex flex-wrap items-center gap-2">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Tags:</span>
                @foreach($blog->tags as $tag)
                    <span class="inline-flex rounded-full bg-purple-100 px-3 py-1 text-xs font-medium text-purple-800 dark:bg-purple-900/40 dark:text-purple-300">{{ $tag->name }}</span>
                @endforeach
            </div>
        </div>
    @endif
</div>

