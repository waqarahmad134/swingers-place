<?php

use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\MessageController;
use Illuminate\Support\Facades\Route;

// Home (Landing Page)
Route::get('/', [HomeController::class, 'index'])->name('home');

// User Profile (Public)
Route::get('/user/{id}', [HomeController::class, 'showProfile'])->name('user.profile');

// Messaging
Route::middleware('auth')->group(function () {
    Route::get('/messages/recent', [MessageController::class, 'recent'])->name('messages.recent');
    Route::get('/messages/{user}', [MessageController::class, 'show'])->name('messages.show')->whereNumber('user');
    Route::post('/messages/{user}', [MessageController::class, 'store'])->name('messages.store')->whereNumber('user');
    Route::get('/messages/{user}/poll', [MessageController::class, 'poll'])->name('messages.poll')->whereNumber('user');
});

// Static Pages (with database fallback)
Route::get('/about', [\App\Http\Controllers\Web\PageController::class, 'about'])->name('about');
Route::get('/contact', [\App\Http\Controllers\Web\PageController::class, 'contact'])->name('contact');
Route::post('/contact', [\App\Http\Controllers\Web\PageController::class, 'contactSubmit'])->name('contact.submit');
Route::get('/privacy', [\App\Http\Controllers\Web\PageController::class, 'privacy'])->name('privacy');
Route::get('/terms', [\App\Http\Controllers\Web\PageController::class, 'terms'])->name('terms');

// Dynamic Pages (catch-all for custom pages)
Route::get('/page/{slug}', [\App\Http\Controllers\Web\PageController::class, 'show'])->name('page.show');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login']);
    Route::get('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'register']);
    Route::get('/forgot-password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
});

Route::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Account Routes (authenticated users)
Route::middleware('auth')->prefix('account')->name('account.')->group(function () {
    Route::get('/profile', [\App\Http\Controllers\Web\ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [\App\Http\Controllers\Web\ProfileController::class, 'update'])->name('profile.update');
});

// Admin Routes (restricted to admins)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Users
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);

    // Slides Management
    Route::resource('slides', \App\Http\Controllers\Admin\SlideController::class);

    // Pages Management
    Route::resource('pages', \App\Http\Controllers\Admin\PageController::class);

    // Media Library
    Route::get('/media', [\App\Http\Controllers\Admin\MediaController::class, 'index'])->name('media.index');
    Route::post('/media', [\App\Http\Controllers\Admin\MediaController::class, 'store'])->name('media.store');
    Route::get('/media/all', [\App\Http\Controllers\Admin\MediaController::class, 'getAll'])->name('media.all');
    Route::get('/media/{filename}', [\App\Http\Controllers\Admin\MediaController::class, 'show'])->name('media.show')->where('filename', '[^/]+');
    Route::put('/media/{filename}', [\App\Http\Controllers\Admin\MediaController::class, 'update'])->name('media.update')->where('filename', '[^/]+');
    Route::delete('/media/{filename}', [\App\Http\Controllers\Admin\MediaController::class, 'destroy'])->name('media.destroy')->where('filename', '[^/]+');

    // Database Management
    Route::get('/database', [\App\Http\Controllers\Admin\DatabaseController::class, 'index'])->name('database.index');
    Route::get('/database/download', [\App\Http\Controllers\Admin\DatabaseController::class, 'download'])->name('database.download');
    Route::get('/database/clear', [\App\Http\Controllers\Admin\DatabaseController::class, 'clear'])->name('database.clear');
    Route::post('/database/clear', [\App\Http\Controllers\Admin\DatabaseController::class, 'clear'])->name('database.clear.store');
    Route::get('/database/{table}', [\App\Http\Controllers\Admin\DatabaseController::class, 'show'])->name('database.show')->where('table', '[a-z0-9_]+');

    // Backup Management
    Route::get('/backup', [\App\Http\Controllers\Admin\BackupController::class, 'index'])->name('backup.index');
    Route::post('/backup/files', [\App\Http\Controllers\Admin\BackupController::class, 'createFilesBackup'])->name('backup.files.create');
    Route::get('/backup/download/complete', [\App\Http\Controllers\Admin\BackupController::class, 'downloadCompleteProject'])->name('backup.download.complete');
    Route::get('/backup/download/{filename}', [\App\Http\Controllers\Admin\BackupController::class, 'downloadFilesBackup'])->name('backup.download.files')->where('filename', '[a-z0-9._-]+');
    Route::delete('/backup/{filename}', [\App\Http\Controllers\Admin\BackupController::class, 'deleteBackup'])->name('backup.delete')->where('filename', '[a-z0-9._-]+');

    // .htaccess Management
    Route::get('/htaccess', [\App\Http\Controllers\Admin\HtaccessController::class, 'index'])->name('htaccess.index');
    Route::put('/htaccess', [\App\Http\Controllers\Admin\HtaccessController::class, 'update'])->name('htaccess.update');
    Route::post('/htaccess/reset', [\App\Http\Controllers\Admin\HtaccessController::class, 'reset'])->name('htaccess.reset');

    // Logs Management
    Route::get('/logs', [\App\Http\Controllers\Admin\LogController::class, 'index'])->name('logs.index');
    Route::get('/logs/download/{filename}', [\App\Http\Controllers\Admin\LogController::class, 'download'])->name('logs.download')->where('filename', '[a-z0-9._-]+');
    Route::post('/logs/clear', [\App\Http\Controllers\Admin\LogController::class, 'clear'])->name('logs.clear');
    Route::post('/logs/delete', [\App\Http\Controllers\Admin\LogController::class, 'delete'])->name('logs.delete');

    // Payment Gateways
    Route::get('/payment-gateways', [\App\Http\Controllers\Admin\PaymentGatewayController::class, 'index'])->name('payment-gateways.index');
    Route::put('/payment-gateways/{gateway}', [\App\Http\Controllers\Admin\PaymentGatewayController::class, 'update'])->name('payment-gateways.update')->where('gateway', '[a-z_]+');

    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/general', [\App\Http\Controllers\Admin\SettingsController::class, 'general'])->name('general');
        Route::post('/general', [\App\Http\Controllers\Admin\SettingsController::class, 'updateGeneral'])->name('general.update');
        Route::get('/robots', [\App\Http\Controllers\Admin\SettingsController::class, 'robots'])->name('robots');
        Route::post('/robots', [\App\Http\Controllers\Admin\SettingsController::class, 'updateRobots'])->name('robots.update');
    });
});

// API Routes
Route::get('/api/health', function () {
    return response()->json(['status' => 'ok']);
});
