{{-- Template 3: Two Column Layout with Sidebar --}}
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Main Content --}}
        <div class="lg:col-span-2">
            <div class="mb-6">
                <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">{{ $page->title }}</h1>
                <div class="h-1 w-20 bg-primary rounded"></div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-8 border border-gray-200 dark:border-gray-700">
                <div class="prose prose-lg dark:prose-invert max-w-none prose-headings:text-gray-900 dark:prose-headings:text-gray-100 prose-p:text-gray-700 dark:prose-p:text-gray-300 prose-strong:text-gray-900 dark:prose-strong:text-gray-100 prose-a:text-primary dark:prose-a:text-primary prose-ul:text-gray-700 dark:prose-ul:text-gray-300 prose-ol:text-gray-700 dark:prose-ol:text-gray-300 prose-li:text-gray-700 dark:prose-li:text-gray-300 prose-code:text-gray-900 dark:prose-code:text-gray-100 prose-pre:bg-gray-100 dark:prose-pre:bg-gray-900 prose-blockquote:text-gray-700 dark:prose-blockquote:text-gray-300">
                    {!! $page->content !!}
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700 sticky top-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Page Information</h3>
                <div class="space-y-3 text-sm text-gray-600 dark:text-gray-400">
                    <div>
                        <span class="font-medium text-gray-900 dark:text-gray-100">Last Updated:</span>
                        <p>{{ $page->updated_at->format('F d, Y') }}</p>
                    </div>
                    @if($page->meta_description)
                    <div>
                        <span class="font-medium text-gray-900 dark:text-gray-100">Description:</span>
                        <p>{{ $page->meta_description }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

