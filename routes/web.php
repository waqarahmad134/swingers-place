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
    Artisan::call('migrate');
    return 'Migrations executed successfully!';
});

Route::get('/seed', function () {
    Artisan::call('db:seed', ['--force' => true]);
    return 'Database seeded successfully!';
});

// Route::get('/update-created-by', function () {
//     try {
//         // Find admin user by email
//         $admin = \App\Models\User::where('email', 'admin@gmail.com')->first();
        
//         if (!$admin) {
//             return '<h1 style="color: red;">Error: Admin user with email "admin@gmail.com" not found!</h1>';
//         }
        
//         // Update all users' created_by field to admin ID
//         // Exclude the admin itself
//         $updated = \App\Models\User::where('id', '!=', $admin->id)
//             ->update(['created_by' => $admin->id]);
        
//         return '<h1 style="color: green;">Success!</h1>
//                 <p>Updated <strong>' . $updated . '</strong> users with created_by = ' . $admin->id . ' (Admin: ' . $admin->name . ' - ' . $admin->email . ')</p>
//                 <p><a href="/">Go to Home</a></p>';
//     } catch (\Exception $e) {
//         return '<h1 style="color: red;">Error: ' . $e->getMessage() . '</h1>';
//     }
// })->name('update.created-by');

Route::get('/rotate-users-online', function () {
    try {
        // Get all users where created_by is not null
        $users = \App\Models\User::whereNotNull('created_by')
            ->where('is_active', true)
            ->orderBy('id')
            ->get();
        
        $totalUsers = $users->count();
        
        if ($totalUsers == 0) {
            return '<h1 style="color: orange;">No users found with created_by field set!</h1>
                    <p>Please run <a href="/update-created-by">/update-created-by</a> first.</p>';
        }
        
        // Calculate 30% of users to be online per hour
        $usersPerHour = (int) ceil($totalUsers * 0.30);
        
        // Get current hour (0-23) and current time
        $currentHour = (int) now()->format('H');
        $currentTime = now();
        $todayStart = $currentTime->copy()->startOfDay();
        
        // Set all users offline first (only last_seen_at, let existing system handle scheduled_offline_at)
        \App\Models\User::whereNotNull('created_by')
            ->where('is_active', true)
            ->update([
                'last_seen_at' => now()->subHours(10) // Set to offline (more than 5 minutes ago)
            ]);
        
        // Create schedule for all 24 hours using deterministic round-robin
        $userIds = $users->pluck('id')->toArray();
        $totalUserIds = count($userIds);
        $schedule = [];
        
        // For each hour (0-23), calculate which users should be online
        for ($hour = 0; $hour < 24; $hour++) {
            $onlineUserIds = [];
            
            // Use round-robin to select users for this hour
            // This ensures even distribution and rotation
            for ($i = 0; $i < $usersPerHour; $i++) {
                // Calculate index using modulo to cycle through users
                $userIndex = ($hour * $usersPerHour + $i) % $totalUserIds;
                $onlineUserIds[] = $userIds[$userIndex];
            }
            
            // Remove duplicates (in case of wrap-around)
            $onlineUserIds = array_unique($onlineUserIds);
            
            // If we have fewer users than needed due to duplicates, fill with additional users
            while (count($onlineUserIds) < $usersPerHour && count($onlineUserIds) < $totalUserIds) {
                $additionalIndex = (count($onlineUserIds) + $hour * 10) % $totalUserIds;
                if (!in_array($userIds[$additionalIndex], $onlineUserIds)) {
                    $onlineUserIds[] = $userIds[$additionalIndex];
                } else {
                    break; // Avoid infinite loop
                }
            }
            
            $hourStart = $todayStart->copy()->addHours($hour);
            $hourEnd = $hourStart->copy()->addHour();
            
            $schedule[$hour] = [
                'user_ids' => array_slice($onlineUserIds, 0, $usersPerHour),
                'start' => $hourStart,
                'end' => $hourEnd,
                'count' => min(count($onlineUserIds), $usersPerHour)
            ];
        }
        
        // Apply schedule: Set users online for current hour and schedule for remaining hours
        $currentHourData = $schedule[$currentHour];
        $currentOnlineUserIds = $currentHourData['user_ids'];
        
        // Set current hour users online (only update last_seen_at)
        // Existing activity system will handle making them offline automatically
        \App\Models\User::whereIn('id', $currentOnlineUserIds)
            ->update([
                'last_seen_at' => now() // Set to now (within 5 minutes = online)
            ]);
        
        $currentOnlineCount = count($currentOnlineUserIds);
        
        // Build schedule display table
        $scheduleHtml = '<table style="width: 100%; border-collapse: collapse; margin: 20px 0; font-size: 12px;">
            <tr style="background: #333; color: white;">
                <th style="padding: 8px; border: 1px solid #555; text-align: left;">Hour</th>
                <th style="padding: 8px; border: 1px solid #555; text-align: left;">Time Range</th>
                <th style="padding: 8px; border: 1px solid #555; text-align: center;">Users</th>
                <th style="padding: 8px; border: 1px solid #555; text-align: center;">Status</th>
            </tr>';
        
        foreach ($schedule as $hour => $data) {
            $isCurrent = $hour == $currentHour;
            $isPast = $hour < $currentHour;
            $rowStyle = $isCurrent 
                ? 'background: #2d5016; color: #90ee90; font-weight: bold;' 
                : ($isPast ? 'background: #1a1a1a; color: #666;' : 'background: #1a1a1a; color: #ccc;');
            $status = $isCurrent 
                ? '<span style="color: #90ee90;">● ACTIVE NOW</span>' 
                : ($isPast ? '<span style="color: #666;">Past</span>' : '<span style="color: #4a9eff;">Scheduled</span>');
            
            $scheduleHtml .= '<tr style="' . $rowStyle . '">
                <td style="padding: 6px; border: 1px solid #555;">' . str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00</td>
                <td style="padding: 6px; border: 1px solid #555;">' . $data['start']->format('H:i') . ' - ' . $data['end']->format('H:i') . '</td>
                <td style="padding: 6px; border: 1px solid #555; text-align: center;">' . $data['count'] . '</td>
                <td style="padding: 6px; border: 1px solid #555; text-align: center;">' . $status . '</td>
            </tr>';
        }
        
        $scheduleHtml .= '</table>';

        
        return '<h1 style="color: green;">Daily User Rotation Scheduled!</h1>
                <div style="margin: 20px 0; padding: 15px; background: #1a1a1a; border-radius: 8px;">
                    <h2 style="color: #90ee90; margin-top: 0;">Statistics:</h2>
                    <ul style="list-style: none; padding: 0; color: #ccc;">
                        <li style="margin: 8px 0;"><strong style="color: white;">Total Users:</strong> ' . $totalUsers . '</li>
                        <li style="margin: 8px 0;"><strong style="color: white;">Users Online Per Hour (30%):</strong> <span style="color: #90ee90;">' . $usersPerHour . ' users</span></li>
                        <li style="margin: 8px 0;"><strong style="color: white;">Current Hour:</strong> ' . str_pad($currentHour, 2, '0', STR_PAD_LEFT) . ':00 - ' . str_pad($currentHour + 1, 2, '0', STR_PAD_LEFT) . ':00</li>
                        <li style="margin: 8px 0; color: #90ee90;"><strong>Users Online Now:</strong> ' . $currentOnlineCount . '</li>
                        <li style="margin: 8px 0;"><strong style="color: white;">Schedule Duration:</strong> Complete 24-hour day</li>
                    </ul>
                </div>
                <div style="margin: 20px 0;">
                    <h3 style="color: white;">24-Hour Schedule:</h3>
                    ' . $scheduleHtml . '
                </div>
                <div style="margin: 20px 0; padding: 15px; background: #1a1a1a; border-radius: 8px; color: #ccc;">
                    <h3 style="color: white; margin-top: 0;">How it works:</h3>
                    <p>✅ <strong style="color: #90ee90;">30% of users</strong> (' . $usersPerHour . ' users) will be online each hour</p>
                    <p>✅ Users rotate throughout the day using round-robin distribution</p>
                    <p>✅ Schedule is calculated for the <strong style="color: white;">entire day</strong> (24 hours)</p>
                    <p>✅ Current hour is <strong style="color: #90ee90;">active now</strong> - users are online</p>
                    <p>✅ Users will automatically go offline based on existing activity system (no activity = offline)</p>
                    <p>⚠️ <em style="color: #ffa500;">Note: For automatic hourly rotation, set up a cron job to run this route every hour. The existing activity system will handle making users offline automatically.</em></p>
                </div>
                <p style="margin-top: 20px;"><a href="/" style="color: #4a9eff; text-decoration: none; margin-right: 15px;">← Go to Home</a> <a href="/rotate-users-online" style="color: #4a9eff; text-decoration: none;">🔄 Refresh Schedule</a></p>';
    } catch (\Exception $e) {
        return '<h1 style="color: red;">Error: ' . $e->getMessage() . '</h1>
                <pre style="background: #f5f5f5; padding: 10px; margin: 10px 0;">' . $e->getTraceAsString() . '</pre>';
    }
})->name('rotate.users.online');


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

// Likes and Dislikes
Route::middleware('auth')->group(function () {
    Route::post('/users/{user}/like', [\App\Http\Controllers\Web\LikeController::class, 'toggleLike'])->name('users.like')->whereNumber('user');
    Route::post('/users/{user}/dislike', [\App\Http\Controllers\Web\LikeController::class, 'toggleDislike'])->name('users.dislike')->whereNumber('user');
});

// ============================================================================
// PAGE MANAGEMENT ROUTES
// ============================================================================
// These routes handle dynamic page management system where admins can create
// unlimited pages with custom slugs, SEO settings, and templates.
// Pages are stored in the 'pages' table and can be accessed via direct slug URLs.

// Static Page Routes (with database fallback)
// These routes provide named routes for common pages while still using database content
Route::get('/about', [\App\Http\Controllers\Web\PageController::class, 'about'])->name('about');
Route::get('/contact', [\App\Http\Controllers\Web\PageController::class, 'contact'])->name('contact');
Route::post('/contact', [\App\Http\Controllers\Web\PageController::class, 'contactSubmit'])->name('contact.submit');
Route::get('/privacy', [\App\Http\Controllers\Web\PageController::class, 'privacy'])->name('privacy');
Route::get('/terms', [\App\Http\Controllers\Web\PageController::class, 'terms'])->name('terms');

// Dynamic Page Route (Alternative format: /page/{slug})
// Example: domain.com/page/waqar, domain.com/page/custom-page
Route::get('/page/{slug}', [\App\Http\Controllers\Web\PageController::class, 'show'])->name('page.show');

// ============================================================================
// BLOG ROUTES
// ============================================================================
// Blog listing and single post routes
Route::get('/blog', [\App\Http\Controllers\Web\BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [\App\Http\Controllers\Web\BlogController::class, 'show'])->name('blog.show');
Route::get('/blog/category/{slug}', [\App\Http\Controllers\Web\BlogController::class, 'category'])->name('blog.category');
Route::get('/blog/tag/{slug}', [\App\Http\Controllers\Web\BlogController::class, 'tag'])->name('blog.tag');

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

// Editor Routes (restricted to editors - limited access)
Route::middleware(['auth', 'editor'])->prefix('editor')->name('editor.')->group(function () {
    // Dashboard
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Users - Editors can manage users
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

    // Pages Management - Editors can manage pages
    Route::resource('pages', \App\Http\Controllers\Admin\PageController::class);

    // Blog Management - Editors can manage blogs
    Route::resource('blog', \App\Http\Controllers\Admin\BlogController::class);
    
    // Categories Management - Editors can manage categories
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    
    // Tags Management - Editors can manage tags
    Route::resource('tags', \App\Http\Controllers\Admin\TagController::class);
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

    // Blog Management
    Route::resource('blog', \App\Http\Controllers\Admin\BlogController::class);
    
    // Categories Management
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    
    // Tags Management
    Route::resource('tags', \App\Http\Controllers\Admin\TagController::class);

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
        Route::get('/website', [\App\Http\Controllers\Admin\SettingsController::class, 'website'])->name('website');
        Route::post('/website', [\App\Http\Controllers\Admin\SettingsController::class, 'updateWebsite'])->name('website.update');
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

// ============================================================================
// DYNAMIC PAGE ROUTE (Direct Slug Access)
// ============================================================================
// This route enables direct slug access for pages created in the admin panel.
// Example: domain.com/waqar, domain.com/custom-page, domain.com/terms
// 
// IMPORTANT: This route MUST be placed at the very end of the routes file
// to avoid conflicts with other routes. It acts as a catch-all for page slugs.
//
// How it works:
// 1. Checks if the slug is in the reserved routes list (if yes, returns 404)
// 2. Queries the database for an active page with matching slug
// 3. If found, displays the page using the selected template
// 4. If not found, returns 404
//
// Reserved routes are protected to prevent conflicts with system routes.
Route::get('/{slug}', function ($slug) {
    // List of reserved routes that should not be treated as page slugs
    $reservedRoutes = [
        'login', 'register', 'logout', 'dashboard', 'messages', 'account', 
        'albums', 'api', 'admin', 'editor', 'clear', 'migrations', 'seed', 
        'rotate-users-online', 'check-username', 'check-email', 'forgot-password', 
        'reset-password', 'send-otp', 'verify-otp', 'resend-otp', 'store-category', 
        'about', 'contact', 'privacy', 'terms', 'page', 'user', 'deploy'
    ];
    
    // If slug matches a reserved route, return 404
    if (in_array($slug, $reservedRoutes)) {
        abort(404);
    }
    
    // Check if a page exists with this slug in the database
    $page = \App\Models\Page::where('slug', $slug)
        ->where('is_active', true)
        ->first();
    
    // If page found, display it using the PageController
    if ($page) {
        return app(\App\Http\Controllers\Web\PageController::class)->show($slug);
    }
    
    // If no page found, return 404
    abort(404);
})->where('slug', '[a-z0-9-]+'); // Only allow alphanumeric and hyphens in slugs
