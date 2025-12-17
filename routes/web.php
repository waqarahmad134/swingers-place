<?php

use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\MessageController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Clear Cache facade value:
Route::get('/clear', function () {
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('optimize');
    $exitCode = Artisan::call('route:cache');
    $exitCode = Artisan::call('route:clear');
    $exitCode = Artisan::call('view:clear');
    $exitCode = Artisan::call('config:cache');
    $exitCode = Artisan::call('config:clear');
    return '<h1>Cache facade value cleared</h1>';
});

Route::get('/migrations', function () {
    Artisan::call('migrate', ['--force' => true]);
    return 'Migrations executed successfully!';
});

Route::get('/seed', function () {
    Artisan::call('db:seed', ['--force' => true]);
    return 'Database seeded successfully!';
});


// Home (Landing Page)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Dashboard Routes (authenticated users) - MUST be at the top before any catch-all routes
Route::middleware('auth')->get('/dashboard/members', [\App\Http\Controllers\Web\DashboardController::class, 'members'])->name('dashboard.members');
Route::middleware('auth')->get('/dashboard/search', [\App\Http\Controllers\Web\DashboardController::class, 'search'])->name('dashboard.search');

// User Profile (Public)
Route::get('/user/{username}', [HomeController::class, 'showProfile'])->name('user.profile');

// Messaging
Route::middleware('auth')->group(function () {
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/recent', [MessageController::class, 'recent'])->name('messages.recent');
    Route::get('/messages/{user}', [MessageController::class, 'show'])->name('messages.show')->whereNumber('user');
    Route::post('/messages/{user}', [MessageController::class, 'store'])->name('messages.store')->whereNumber('user');
    Route::get('/messages/{user}/poll', [MessageController::class, 'poll'])->name('messages.poll')->whereNumber('user');
    Route::post('/messages/{user}/clear', [MessageController::class, 'clearChat'])->name('messages.clear')->whereNumber('user');
    Route::post('/messages/{user}/block', [MessageController::class, 'blockUser'])->name('messages.block')->whereNumber('user');
});

// Static Pages (with database fallback)
Route::get('/about', [\App\Http\Controllers\Web\PageController::class, 'about'])->name('about');
Route::get('/contact', [\App\Http\Controllers\Web\PageController::class, 'contact'])->name('contact');
Route::post('/contact', [\App\Http\Controllers\Web\PageController::class, 'contactSubmit'])->name('contact.submit');
Route::get('/privacy', [\App\Http\Controllers\Web\PageController::class, 'privacy'])->name('privacy');
Route::get('/terms', [\App\Http\Controllers\Web\PageController::class, 'terms'])->name('terms');

// Dynamic Pages (catch-all for custom pages)
Route::get('/page/{slug}', [\App\Http\Controllers\Web\PageController::class, 'show'])->name('page.show');

// Username and Email validation (accessible to all)
Route::get('/check-username', [\App\Http\Controllers\Auth\RegisterController::class, 'checkUsername'])->name('check-username');
Route::get('/check-email', [\App\Http\Controllers\Auth\RegisterController::class, 'checkEmail'])->name('check-email');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login']);
    Route::get('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'register']);
    Route::get('/forgot-password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.store');
    Route::post('/send-otp', [\App\Http\Controllers\Auth\RegisterController::class, 'sendOTP'])->name('send-otp');
    Route::post('/verify-otp', [\App\Http\Controllers\Auth\RegisterController::class, 'verifyOTP'])->name('verify-otp');
    Route::post('/resend-otp', [\App\Http\Controllers\Auth\RegisterController::class, 'resendOTP'])->name('resend-otp');
    Route::post('/store-category', [\App\Http\Controllers\Auth\RegisterController::class, 'storeCategory'])->name('store-category');
});

Route::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Account Routes (authenticated users)
Route::middleware('auth')->prefix('account')->name('account.')->group(function () {
    Route::get('/profile', [\App\Http\Controllers\Web\ProfileController::class, 'index'])->name('profile');
    Route::get('/profile/edit', [\App\Http\Controllers\Web\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\Web\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [\App\Http\Controllers\Web\ProfileController::class, 'destroy'])->name('profile.delete');
});

// Album Routes (authenticated users)
Route::middleware('auth')->prefix('albums')->name('albums.')->group(function () {
    Route::post('/create', [\App\Http\Controllers\AlbumController::class, 'store'])->name('create');
    Route::get('/{id}', [\App\Http\Controllers\AlbumController::class, 'show'])->name('show');
    Route::post('/{id}/verify-password', [\App\Http\Controllers\AlbumController::class, 'verifyPassword'])->name('verify-password');
    Route::post('/{id}/upload-images', [\App\Http\Controllers\AlbumController::class, 'uploadImages'])->name('upload-images');
    Route::delete('/{id}', [\App\Http\Controllers\AlbumController::class, 'destroy'])->name('destroy');
    Route::delete('/{albumId}/images/{imageId}', [\App\Http\Controllers\AlbumController::class, 'deleteImage'])->name('delete-image');
});

// Editor Routes (restricted to editors - user management only)
Route::middleware(['auth', 'editor'])->prefix('editor')->name('editor.')->group(function () {
    // Users - Editors can only manage users
    Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [\App\Http\Controllers\Admin\UserController::class, 'create'])->name('users.create');
    Route::post('/users', [\App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [\App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/users/{user}/data', [\App\Http\Controllers\Admin\UserController::class, 'getData'])->name('users.data');
    Route::post('/users/{user}/verify', [\App\Http\Controllers\Admin\UserController::class, 'verify'])->name('users.verify');
    Route::post('/users/{user}/toggle-status', [\App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::post('/users/{user}/toggle-online-status', [\App\Http\Controllers\Admin\UserController::class, 'toggleOnlineStatus'])->name('users.toggle-online-status');
    Route::post('/users/{user}/set-scheduled-offline', [\App\Http\Controllers\Admin\UserController::class, 'setScheduledOffline'])->name('users.set-scheduled-offline');
    Route::post('/users/{user}/toggle-message-block', [\App\Http\Controllers\Admin\UserController::class, 'toggleMessageBlock'])->name('users.toggle-message-block');
});

// Admin Routes (restricted to admins)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Users
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::get('/users/{user}/data', [\App\Http\Controllers\Admin\UserController::class, 'getData'])->name('users.data');
    Route::post('/users/{user}/verify', [\App\Http\Controllers\Admin\UserController::class, 'verify'])->name('users.verify');
    Route::post('/users/{user}/toggle-status', [\App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::post('/users/{user}/toggle-online-status', [\App\Http\Controllers\Admin\UserController::class, 'toggleOnlineStatus'])->name('users.toggle-online-status');
    Route::post('/users/{user}/set-scheduled-offline', [\App\Http\Controllers\Admin\UserController::class, 'setScheduledOffline'])->name('users.set-scheduled-offline');
    Route::post('/users/{user}/toggle-message-block', [\App\Http\Controllers\Admin\UserController::class, 'toggleMessageBlock'])->name('users.toggle-message-block');

    // Verification
    Route::get('/verification', [\App\Http\Controllers\Admin\VerificationController::class, 'index'])->name('verification.index');
    Route::post('/verification/{verification}/approve', [\App\Http\Controllers\Admin\VerificationController::class, 'approve'])->name('verification.approve');
    Route::post('/verification/{verification}/reject', [\App\Http\Controllers\Admin\VerificationController::class, 'reject'])->name('verification.reject');

    // Photo Moderation
    Route::get('/photo-moderation', [\App\Http\Controllers\Admin\PhotoModerationController::class, 'index'])->name('photo-moderation.index');
    Route::post('/photo-moderation/{id}/approve', [\App\Http\Controllers\Admin\PhotoModerationController::class, 'approve'])->name('photo-moderation.approve');
    Route::post('/photo-moderation/{id}/reject', [\App\Http\Controllers\Admin\PhotoModerationController::class, 'reject'])->name('photo-moderation.reject');

    // Content Management
    Route::get('/content-management', [\App\Http\Controllers\Admin\ContentManagementController::class, 'index'])->name('content-management.index');
    Route::get('/content-management/{id}/edit', [\App\Http\Controllers\Admin\ContentManagementController::class, 'edit'])->name('content-management.edit');
    Route::get('/content-management/{id}/preview', [\App\Http\Controllers\Admin\ContentManagementController::class, 'preview'])->name('content-management.preview');

    // Registration Control
    Route::get('/registration-control', [\App\Http\Controllers\Admin\RegistrationControlController::class, 'index'])->name('registration-control.index');
    Route::put('/registration-control', [\App\Http\Controllers\Admin\RegistrationControlController::class, 'update'])->name('registration-control.update');

    // Reported Users
    Route::get('/reported-users', [\App\Http\Controllers\Admin\ReportedUsersController::class, 'index'])->name('reported-users.index');
    Route::get('/reported-users/{id}/review', [\App\Http\Controllers\Admin\ReportedUsersController::class, 'review'])->name('reported-users.review');

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
        Route::post('/password', [\App\Http\Controllers\Admin\SettingsController::class, 'updatePassword'])->name('password.update');
        Route::get('/robots', [\App\Http\Controllers\Admin\SettingsController::class, 'robots'])->name('robots');
        Route::post('/robots', [\App\Http\Controllers\Admin\SettingsController::class, 'updateRobots'])->name('robots.update');
    });

    // Deployment
    Route::get('/deployment', [\App\Http\Controllers\Admin\DeploymentController::class, 'index'])->name('deployment.index');
    Route::post('/deployment', [\App\Http\Controllers\Admin\DeploymentController::class, 'deploy'])->name('deployment.deploy');
});

// Public Deployment Route (with token authentication)
Route::post('/deploy/{token}', [\App\Http\Controllers\Admin\DeploymentController::class, 'deployPublic'])->name('deployment.public');

// API Routes
Route::get('/api/health', function () {
    return response()->json(['status' => 'ok']);
});
