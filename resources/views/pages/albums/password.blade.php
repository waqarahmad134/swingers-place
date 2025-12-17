@extends('layouts.app')

@section('title', $album->name . ' - Private Album - ' . config('app.name'))

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 flex items-center justify-center py-8">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 p-8">
            <div class="text-center mb-6">
                <div class="mx-auto w-16 h-16 bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center mb-4">
                    <i class="ri-lock-line text-3xl text-yellow-600 dark:text-yellow-400"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Private Album</h2>
                <p class="text-gray-600 dark:text-gray-400">This album is password protected</p>
                <p class="text-sm text-gray-500 dark:text-gray-500 mt-2">{{ $album->name }}</p>
            </div>
            
            <form method="POST" action="{{ route('albums.verify-password', $album->id) }}">
                @csrf
                
                @if($errors->has('password'))
                    <div class="mb-4 p-3 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-lg">
                        <p class="text-sm text-red-600 dark:text-red-400">{{ $errors->first('password') }}</p>
                    </div>
                @endif
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Enter Password</label>
                    <input 
                        type="password" 
                        name="password" 
                        required
                        autofocus
                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                        placeholder="Enter album password"
                    />
                </div>
                
                <button 
                    type="submit"
                    class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-3 px-6 rounded-xl transition-colors"
                >
                    Access Album
                </button>
                
                <div class="mt-4 text-center">
                    <a href="{{ route('account.profile') }}#album" class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                        <i class="ri-arrow-left-line"></i> Back to Albums
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

