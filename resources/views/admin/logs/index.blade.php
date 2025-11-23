@extends('layouts.admin')

@section('title', 'Logs - Admin Panel')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <h1 class="text-3xl font-extrabold text-secondary">Application Logs</h1>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-100 border border-green-400 text-green-700 px-4 py-3">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 rounded-lg bg-red-100 border border-red-400 text-red-700 px-4 py-3">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 h-[calc(100vh-12rem)]">
        {{-- Log Files List --}}
        <div class="lg:col-span-1 flex flex-col">
            <div class="rounded-lg border border-gray-200 bg-white shadow-sm text-gray-900 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 flex flex-col h-full">
                <div class="border-b border-gray-200 px-4 py-3 dark:border-gray-700 shrink-0">
                    <h2 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Log Files</h2>
                </div>
                <div class="divide-y divide-gray-200 dark:divide-gray-700 flex-1 overflow-y-auto">
                    @forelse ($logFiles as $logFile)
                        <div class="px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 {{ $selectedFile === $logFile['name'] ? 'bg-primary/10 border-l-4 border-primary' : '' }}">
                            <a href="{{ route('admin.logs.index', ['file' => $logFile['name']]) }}" class="block">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                                            {{ $logFile['name'] }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            {{ $logFile['size_formatted'] }} • {{ $logFile['modified_formatted'] }}
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @empty
                        <div class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                            No log files found
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Log Content --}}
        <div class="lg:col-span-1 flex flex-col">
            <div class="rounded-lg border border-gray-200 bg-white shadow-sm text-gray-900 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 flex flex-col h-full">
                <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700 shrink-0">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                {{ $selectedFile }}
                            </h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                Viewing log file content
                            </p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('admin.logs.download', $selectedFile) }}" class="inline-flex items-center gap-2 rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white transition-colors hover:bg-blue-700">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                Download
                            </a>
                            <form action="{{ route('admin.logs.clear') }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to clear this log file? This action cannot be undone.');">
                                @csrf
                                <input type="hidden" name="file" value="{{ $selectedFile }}">
                                <button type="submit" class="inline-flex items-center gap-2 rounded-md bg-yellow-600 px-3 py-2 text-sm font-semibold text-white transition-colors hover:bg-yellow-700">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Clear
                                </button>
                            </form>
                            @if($selectedFile !== 'laravel.log')
                                <form action="{{ route('admin.logs.delete') }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this log file? This action cannot be undone.');">
                                    @csrf
                                    <input type="hidden" name="file" value="{{ $selectedFile }}">
                                    <button type="submit" class="inline-flex items-center gap-2 rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white transition-colors hover:bg-red-700">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Delete
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Filters --}}
                <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 shrink-0">
                    <form method="GET" action="{{ route('admin.logs.index') }}" class="space-y-3">
                        <input type="hidden" name="file" value="{{ $selectedFile }}">
                        <div class="flex flex-col sm:flex-row gap-3">
                            <div class="flex-1">
                                <input 
                                    type="text" 
                                    name="search" 
                                    value="{{ $search }}" 
                                    placeholder="Search in logs..." 
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm"
                                >
                            </div>
                            <div class="w-full sm:w-40">
                                <select 
                                    name="level" 
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm"
                                >
                                    <option value="">All Levels</option>
                                    <option value="emergency" {{ $level === 'emergency' ? 'selected' : '' }}>Emergency</option>
                                    <option value="alert" {{ $level === 'alert' ? 'selected' : '' }}>Alert</option>
                                    <option value="critical" {{ $level === 'critical' ? 'selected' : '' }}>Critical</option>
                                    <option value="error" {{ $level === 'error' ? 'selected' : '' }}>Error</option>
                                    <option value="warning" {{ $level === 'warning' ? 'selected' : '' }}>Warning</option>
                                    <option value="notice" {{ $level === 'notice' ? 'selected' : '' }}>Notice</option>
                                    <option value="info" {{ $level === 'info' ? 'selected' : '' }}>Info</option>
                                    <option value="debug" {{ $level === 'debug' ? 'selected' : '' }}>Debug</option>
                                </select>
                            </div>
                            <div class="flex gap-2">
                                <button type="submit" class="inline-flex items-center gap-2 rounded-md bg-primary px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-primary-dark">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                    Filter
                                </button>
                                @if($search || $level)
                                    <a href="{{ route('admin.logs.index', ['file' => $selectedFile]) }}" class="inline-flex items-center gap-2 rounded-md bg-gray-600 px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-gray-700">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Clear Filters
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <form action="{{ route('admin.logs.clear') }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to clear this log file? This action cannot be undone.');">
                            @csrf
                            <input type="hidden" name="file" value="{{ $selectedFile }}">
                            <button type="submit" class="inline-flex items-center gap-2 rounded-md bg-yellow-600 px-4 py-2 text-sm font-semibold text-black transition-colors hover:bg-yellow-700 shadow-sm">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Clear Log File
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Log Content Display --}}
                <div class="flex-1 overflow-y-auto p-6">
                    @if(isset($lines) && $lines->count() > 0)
                        <div class="space-y-1 font-mono text-xs">
                            @foreach($lines as $line)
                                @php
                                    $lineStr = (string)$line;
                                    $isError = stripos($lineStr, '[ERROR]') !== false || stripos($lineStr, '[CRITICAL]') !== false || stripos($lineStr, '[EMERGENCY]') !== false;
                                    $isWarning = stripos($lineStr, '[WARNING]') !== false || stripos($lineStr, '[ALERT]') !== false;
                                    $isInfo = stripos($lineStr, '[INFO]') !== false || stripos($lineStr, '[NOTICE]') !== false;
                                    $isDebug = stripos($lineStr, '[DEBUG]') !== false;
                                @endphp
                                <div class="rounded p-2 {{ $isError ? 'bg-red-50 dark:bg-red-900/20 text-red-800 dark:text-red-200' : ($isWarning ? 'bg-yellow-50 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-200' : ($isInfo ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-800 dark:text-blue-200' : ($isDebug ? 'bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300'))) }}">
                                    <pre class="whitespace-pre-wrap break-words overflow-x-auto">{{ $lineStr }}</pre>
                                </div>
                            @endforeach
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-6 sticky bottom-0 bg-white dark:bg-gray-800 pt-4 border-t border-gray-200 dark:border-gray-700">
                            {{ $lines->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No log entries</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                @if($search || $level)
                                    No entries found matching your filters.
                                @else
                                    This log file is empty.
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

