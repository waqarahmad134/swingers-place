<?php

use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\MessageController;
use Illuminate\Support\Facades\Route;

// Home (Landing Page)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Dashboard Routes (authenticated users) - MUST be at the top before any catch-all routes
Route::middleware('auth')->get('/dashboard/members', [\App\Http\Controllers\Web\DashboardController::class, 'members'])->name('dashboard.members');

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
    Route::get('/reset-password/{token}', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.store');
});

Route::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Profile Type Selection (accessible to guests - BEFORE registration)
Route::prefix('onboarding')->name('onboarding.')->group(function () {
    Route::get('/profile-type', [\App\Http\Controllers\Web\OnboardingController::class, 'profileType'])->name('profile-type');
    Route::post('/profile-type', [\App\Http\Controllers\Web\OnboardingController::class, 'storeProfileType'])->name('profile-type.store');
});

// Onboarding Routes (authenticated users only - for existing users who want to complete steps)
Route::middleware('auth')->prefix('onboarding')->name('onboarding.')->group(function () {
    Route::get('/step1', [\App\Http\Controllers\Web\OnboardingController::class, 'step1'])->name('step1');
    Route::post('/step1', [\App\Http\Controllers\Web\OnboardingController::class, 'storeStep1'])->name('step1.store');
    
    Route::get('/step2', [\App\Http\Controllers\Web\OnboardingController::class, 'step2'])->name('step2');
    Route::post('/step2', [\App\Http\Controllers\Web\OnboardingController::class, 'storeStep2'])->name('step2.store');
    
    Route::get('/step3', [\App\Http\Controllers\Web\OnboardingController::class, 'step3'])->name('step3');
    Route::post('/step3', [\App\Http\Controllers\Web\OnboardingController::class, 'storeStep3'])->name('step3.store');
    
    Route::get('/step4', [\App\Http\Controllers\Web\OnboardingController::class, 'step4'])->name('step4');
    Route::post('/step4', [\App\Http\Controllers\Web\OnboardingController::class, 'storeStep4'])->name('step4.store');
    
    Route::get('/step5', [\App\Http\Controllers\Web\OnboardingController::class, 'step5'])->name('step5');
    Route::post('/step5', [\App\Http\Controllers\Web\OnboardingController::class, 'storeStep5'])->name('step5.store');
    
    Route::get('/step6', [\App\Http\Controllers\Web\OnboardingController::class, 'step6'])->name('step6');
    Route::post('/step6', [\App\Http\Controllers\Web\OnboardingController::class, 'storeStep6'])->name('step6.store');
    
    Route::get('/step7', [\App\Http\Controllers\Web\OnboardingController::class, 'step7'])->name('step7');
    Route::post('/step7', [\App\Http\Controllers\Web\OnboardingController::class, 'storeStep7'])->name('step7.store');
    
    Route::get('/step8', [\App\Http\Controllers\Web\OnboardingController::class, 'step8'])->name('step8');
    Route::post('/step8', [\App\Http\Controllers\Web\OnboardingController::class, 'storeStep8'])->name('step8.store');
    
    Route::get('/step9', [\App\Http\Controllers\Web\OnboardingController::class, 'step9'])->name('step9');
    Route::post('/step9', [\App\Http\Controllers\Web\OnboardingController::class, 'storeStep9'])->name('step9.store');
    
    Route::get('/complete', [\App\Http\Controllers\Web\OnboardingController::class, 'complete'])->name('complete');
    Route::post('/skip/{step}', [\App\Http\Controllers\Web\OnboardingController::class, 'skip'])->name('skip');
});

// Account Routes (authenticated users)
Route::middleware('auth')->prefix('account')->name('account.')->group(function () {
    Route::get('/profile', [\App\Http\Controllers\Web\ProfileController::class, 'index'])->name('profile');
    Route::get('/profile/edit', [\App\Http\Controllers\Web\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\Web\ProfileController::class, 'update'])->name('profile.update');
});

// Admin Routes (restricted to admins)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Users
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::get('/users/{user}/data', [\App\Http\Controllers\Admin\UserController::class, 'getData'])->name('users.data');
    Route::post('/users/{user}/verify', [\App\Http\Controllers\Admin\UserController::class, 'verify'])->name('users.verify');
    Route::post('/users/{user}/toggle-status', [\App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::get('/users/create/select-profile-type', [\App\Http\Controllers\Admin\UserController::class, 'selectProfileType'])->name('users.select-profile-type');
    Route::post('/users/create/select-profile-type', [\App\Http\Controllers\Admin\UserController::class, 'storeProfileType'])->name('users.store-profile-type');
    
    // Admin Onboarding (for creating users)
    Route::prefix('users/onboarding')->name('users.onboarding.')->group(function () {
        Route::get('/profile-type', [\App\Http\Controllers\Admin\AdminOnboardingController::class, 'profileType'])->name('profile-type');
        Route::post('/profile-type', [\App\Http\Controllers\Admin\AdminOnboardingController::class, 'storeProfileType'])->name('profile-type.store');
        Route::get('/step1', [\App\Http\Controllers\Admin\AdminOnboardingController::class, 'step1'])->name('step1');
        Route::post('/step1', [\App\Http\Controllers\Admin\AdminOnboardingController::class, 'storeStep1'])->name('step1.store');
        Route::get('/step2', [\App\Http\Controllers\Admin\AdminOnboardingController::class, 'step2'])->name('step2');
        Route::post('/step2', [\App\Http\Controllers\Admin\AdminOnboardingController::class, 'storeStep2'])->name('step2.store');
        Route::get('/step3', [\App\Http\Controllers\Admin\AdminOnboardingController::class, 'step3'])->name('step3');
        Route::post('/step3', [\App\Http\Controllers\Admin\AdminOnboardingController::class, 'storeStep3'])->name('step3.store');
        Route::get('/step4', [\App\Http\Controllers\Admin\AdminOnboardingController::class, 'step4'])->name('step4');
        Route::post('/step4', [\App\Http\Controllers\Admin\AdminOnboardingController::class, 'storeStep4'])->name('step4.store');
        Route::get('/step5', [\App\Http\Controllers\Admin\AdminOnboardingController::class, 'step5'])->name('step5');
        Route::post('/step5', [\App\Http\Controllers\Admin\AdminOnboardingController::class, 'storeStep5'])->name('step5.store');
        Route::get('/step6', [\App\Http\Controllers\Admin\AdminOnboardingController::class, 'step6'])->name('step6');
        Route::post('/step6', [\App\Http\Controllers\Admin\AdminOnboardingController::class, 'storeStep6'])->name('step6.store');
        Route::get('/step7', [\App\Http\Controllers\Admin\AdminOnboardingController::class, 'step7'])->name('step7');
        Route::post('/step7', [\App\Http\Controllers\Admin\AdminOnboardingController::class, 'storeStep7'])->name('step7.store');
        Route::get('/step8', [\App\Http\Controllers\Admin\AdminOnboardingController::class, 'step8'])->name('step8');
        Route::post('/step8', [\App\Http\Controllers\Admin\AdminOnboardingController::class, 'storeStep8'])->name('step8.store');
        Route::get('/step9', [\App\Http\Controllers\Admin\AdminOnboardingController::class, 'step9'])->name('step9');
        Route::post('/step9', [\App\Http\Controllers\Admin\AdminOnboardingController::class, 'storeStep9'])->name('step9.store');
        Route::get('/complete', [\App\Http\Controllers\Admin\AdminOnboardingController::class, 'complete'])->name('complete');
        Route::post('/skip/{step}', [\App\Http\Controllers\Admin\AdminOnboardingController::class, 'skip'])->name('skip');
    });

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
