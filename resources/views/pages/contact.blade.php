@extends('layouts.app')

@section('title', isset($page) ? ($page->meta_title ?? $page->title) . ' - ' . config('app.name') : 'Contact Us - ' . config('app.name'))

@section('meta_description', isset($page) ? $page->meta_description : null)

@section('content')
    <div class="mx-auto max-w-6xl">
        <!-- Page Header -->
        <div class="mb-8 text-center">
            @if(isset($page))
                <h1 class="mb-4 text-4xl font-extrabold text-secondary dark:text-primary">{{ $page->title }}</h1>
            @else
                <h1 class="mb-4 text-4xl font-extrabold text-secondary dark:text-primary">Contact Us</h1>
            @endif
            <p class="mx-auto max-w-2xl text-lg text-gray-600 dark:text-gray-400">
                We'd love to hear from you. Send us a message and we'll respond as soon as possible.
            </p>
        </div>

        @if(session('success'))
            <div class="mb-8 rounded-lg border border-green-200 bg-green-50 px-6 py-4 text-green-700 shadow-sm dark:border-green-800 dark:bg-green-900/40 dark:text-green-300">
                <div class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(isset($page) && $page->content)
            <div class="mb-10 rounded-lg border border-gray-200 bg-white p-8 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="prose prose-lg dark:prose-invert max-w-none prose-headings:text-gray-900 dark:prose-headings:text-gray-100 prose-p:text-gray-700 dark:prose-p:text-gray-300 prose-strong:text-gray-900 dark:prose-strong:text-gray-100 prose-a:text-primary dark:prose-a:text-primary prose-ul:text-gray-700 dark:prose-ul:text-gray-300 prose-ol:text-gray-700 dark:prose-ol:text-gray-300 prose-li:text-gray-700 dark:prose-li:text-gray-300 prose-code:text-gray-900 dark:prose-code:text-gray-100 prose-pre:bg-gray-100 dark:prose-pre:bg-gray-900 prose-blockquote:text-gray-700 dark:prose-blockquote:text-gray-300">
                    {!! $page->content !!}
                </div>
            </div>
        @endif

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
            <!-- Contact Information Card -->
            <div class="lg:col-span-1">
                <div class="sticky top-8 rounded-xl border border-gray-200 bg-white p-8 shadow-lg dark:border-gray-700 dark:bg-gray-800">
                    <h2 class="mb-6 text-2xl font-bold text-gray-900 dark:text-gray-100">Get in Touch</h2>
                    
                    <div class="space-y-6">
                        <!-- Email -->
                        <div class="group flex items-start gap-4 rounded-lg p-4 transition-colors hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary dark:bg-primary/20">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="mb-1 font-semibold text-gray-900 dark:text-gray-100">Email</h3>
                                <a href="mailto:info@example.com" class="block truncate text-gray-600 transition-colors hover:text-primary dark:text-gray-400 dark:hover:text-primary">
                                    info@example.com
                                </a>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="group flex items-start gap-4 rounded-lg p-4 transition-colors hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary dark:bg-primary/20">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="mb-1 font-semibold text-gray-900 dark:text-gray-100">Phone</h3>
                                <a href="tel:+923039345647" class="block text-gray-600 transition-colors hover:text-primary dark:text-gray-400 dark:hover:text-primary">
                                    0303-9345647
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 border-t border-gray-200 pt-6 dark:border-gray-700">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Our team typically responds within 24 hours during business days.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Contact Form Card -->
            <div class="lg:col-span-2">
                <div class="rounded-xl border border-gray-200 bg-white p-8 shadow-lg dark:border-gray-700 dark:bg-gray-800">
                    <div class="mb-6">
                        <h2 class="mb-2 text-2xl font-bold text-gray-900 dark:text-gray-100">Send us a Message</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Fill out the form below and we'll get back to you as soon as possible.
                        </p>
                    </div>

                    <form action="{{ route('contact.submit') }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="name" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Your Name <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="name" 
                                    name="name" 
                                    value="{{ old('name') }}" 
                                    required 
                                    class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm transition-colors focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-primary"
                                    placeholder="John Doe"
                                >
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Your Email <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email" 
                                    value="{{ old('email') }}" 
                                    required 
                                    class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm transition-colors focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-primary"
                                    placeholder="john@example.com"
                                >
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="subject" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Subject <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="subject" 
                                name="subject" 
                                value="{{ old('subject') }}" 
                                required 
                                class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm transition-colors focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-primary"
                                placeholder="How can we help you?"
                            >
                            @error('subject')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="message" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Message <span class="text-red-500">*</span>
                            </label>
                            <textarea 
                                id="message" 
                                name="message" 
                                rows="6" 
                                required 
                                class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm transition-colors focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-primary"
                                placeholder="Tell us more about your inquiry..."
                            >{{ old('message') }}</textarea>
                            @error('message')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-4">
                            <button 
                                type="submit" 
                                class="inline-flex items-center gap-2 rounded-lg bg-primary px-6 py-3 text-sm font-semibold text-white shadow-sm transition-all hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 disabled:opacity-50"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
                                Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

