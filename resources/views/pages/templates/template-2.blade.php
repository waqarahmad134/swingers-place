{{-- Template 2: Full Width with Background --}}
<div class="w-full">
    <div class="bg-gradient-to-r from-primary/10 to-secondary/10 dark:from-primary/20 dark:to-secondary/20 py-12 px-4 sm:px-6 lg:px-8 mb-8">
        <div class="mx-auto max-w-5xl">
            <h1 class="text-5xl font-bold text-gray-900 dark:text-white mb-4">{{ $page->title }}</h1>
        </div>
    </div>

    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-10 border border-gray-200 dark:border-gray-700">
            <div class="prose prose-lg dark:prose-invert max-w-none prose-headings:text-gray-900 dark:prose-headings:text-gray-100 prose-p:text-gray-700 dark:prose-p:text-gray-300 prose-strong:text-gray-900 dark:prose-strong:text-gray-100 prose-a:text-primary dark:prose-a:text-primary prose-ul:text-gray-700 dark:prose-ul:text-gray-300 prose-ol:text-gray-700 dark:prose-ol:text-gray-300 prose-li:text-gray-700 dark:prose-li:text-gray-300 prose-code:text-gray-900 dark:prose-code:text-gray-100 prose-pre:bg-gray-100 dark:prose-pre:bg-gray-900 prose-blockquote:text-gray-700 dark:prose-blockquote:text-gray-300">
                {!! $page->content !!}
            </div>
        </div>
    </div>
</div>

