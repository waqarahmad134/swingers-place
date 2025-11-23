@extends('layouts.admin')

@section('title', 'General Settings - Admin Panel')

@section('content')
    <h1 class="mb-6 text-3xl font-extrabold text-secondary">General Settings</h1>

    <div class="max-w-3xl rounded-lg border border-gray-200 bg-white p-6 shadow-sm text-gray-900 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
        <form action="{{ route('admin.settings.general.update') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="site_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Site Name</label>
                <input type="text" id="site_name" name="site_name" value="{{ old('site_name', $settings['site_name']) }}" class="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-primary focus:outline-none focus:ring-primary dark:border-gray-600 dark:bg-gray-700 dark:text-white" required>
                @error('site_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="logo_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Logo URL</label>
                <input type="text" id="logo_url" name="logo_url" value="{{ old('logo_url', $settings['logo_url']) }}" class="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-primary focus:outline-none focus:ring-primary dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                @error('logo_url')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Leave empty to use default SVG logo.</p>
            </div>

            <div>
                <label for="meta_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Meta Description</label>
                <textarea id="meta_description" name="meta_description" rows="3" class="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-primary focus:outline-none focus:ring-primary dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('meta_description', $settings['meta_description']) }}</textarea>
                @error('meta_description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <fieldset class="space-y-4 border-t pt-4 dark:border-gray-700">
                <legend class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Application Environment</legend>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="app_env" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Environment</label>
                        <select id="app_env" name="app_env" class="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:border-primary focus:outline-none focus:ring-primary dark:border-gray-600 dark:bg-gray-700 dark:text-white" required>
                            <option value="local" {{ old('app_env', $settings['app_env']) === 'local' ? 'selected' : '' }}>Local</option>
                            <option value="staging" {{ old('app_env', $settings['app_env']) === 'staging' ? 'selected' : '' }}>Staging</option>
                            <option value="production" {{ old('app_env', $settings['app_env']) === 'production' ? 'selected' : '' }}>Production</option>
                            <option value="testing" {{ old('app_env', $settings['app_env']) === 'testing' ? 'selected' : '' }}>Testing</option>
                        </select>
                        @error('app_env')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Current environment mode for the application.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Debug Mode</label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="app_debug" value="1" {{ old('app_debug', $settings['app_debug']) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Enable Debug Mode</span>
                        </label>
                        @error('app_debug')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Show detailed error messages. Disable in production!</p>
                    </div>
                </div>
            </fieldset>

            <fieldset class="space-y-3 border-t pt-4 dark:border-gray-700">
                <legend class="text-sm font-medium text-gray-700 dark:text-gray-300">Feature Toggles</legend>
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="maintenance_mode" value="1" {{ old('maintenance_mode', $settings['maintenance_mode']) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary">
                    <span class="text-sm text-gray-700 dark:text-gray-300">Enable Maintenance Mode</span>
                </label>
            </fieldset>

            <div class="border-t pt-4 dark:border-gray-700">
                <button type="submit" class="rounded-md bg-primary px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-secondary">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
@endsection
