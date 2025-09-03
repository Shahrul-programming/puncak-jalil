<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Controllers
use App\Http\Controllers\SiteSettingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ForumPostController;
use App\Http\Controllers\ForumReplyController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\NotificationController;

// Models
use App\Models\Shop;
use App\Models\Review;
use App\Models\Event;
use App\Models\ForumPost;
use App\Models\Report;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ✅ Route admin (guna middleware auth + IsAdminMiddleware)
Route::middleware(['auth', \App\Http\Middleware\IsAdminMiddleware::class])->group(function () {
    Route::get('/admin/dashboard', [App\Http\Controllers\DashboardAdminController::class, 'index'])
        ->name('admin.dashboard');
    
    // System Settings Routes
    Route::get('/admin/settings', [App\Http\Controllers\Admin\SiteSettingController::class, 'index'])
        ->name('admin.settings.index');
    Route::post('/admin/settings', [App\Http\Controllers\Admin\SiteSettingController::class, 'update'])
        ->name('admin.settings.update');
    Route::post('/admin/settings/reset', [App\Http\Controllers\Admin\SiteSettingController::class, 'reset'])
        ->name('admin.settings.reset');
    
    // User Management Routes
    Route::resource('/admin/users', App\Http\Controllers\Admin\UserController::class, [
        'as' => 'admin'
    ]);
    Route::patch('/admin/users/{user}/toggle-status', [App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])
        ->name('admin.users.toggle-status');
    Route::post('/admin/users/{user}/impersonate', [App\Http\Controllers\Admin\UserController::class, 'impersonate'])
        ->name('admin.users.impersonate');
});

// ✅ Route utama (homepage)
Route::get('/', function () {
    return view('welcome');
});

// ✅ Route dashboard
Route::get('/dashboard', function () {
    $user = Auth::user();
    $shopCount   = Shop::where('user_id', $user->id)->count();
    $reviewCount = Review::where('user_id', $user->id)->count();
    $eventCount  = Event::where('user_id', $user->id)->count();
    $forumCount  = ForumPost::where('user_id', $user->id)->count();
    $reportCount = Report::where('user_id', $user->id)->count();

    return view('dashboard', compact(
        'user', 'shopCount', 'reviewCount', 'eventCount', 'forumCount', 'reportCount'
    ));
})->middleware(['auth', 'verified'])->name('dashboard');

// ✅ Route dengan auth
Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Impersonation
    Route::post('/admin/stop-impersonating', [App\Http\Controllers\Admin\UserController::class, 'stopImpersonating'])
        ->name('admin.users.stop-impersonating');

    // Resource routes
    Route::resource('shops', ShopController::class);
    Route::resource('promotions', PromotionController::class);
    Route::resource('events', EventController::class);
    Route::resource('forum-posts', ForumPostController::class);
    Route::resource('reports', ReportController::class);
    
    // Review routes
    Route::post('reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::get('reviews/{review}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
    Route::put('reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    
    // Notification routes
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
    Route::get('notifications/recent', [NotificationController::class, 'getRecent'])->name('notifications.recent');
    Route::patch('notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::patch('notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::delete('notifications/clear-all', [NotificationController::class, 'clearAll'])->name('notifications.clear-all');
    
    // Forum replies (nested under forum posts)
    Route::post('forum-posts/{forumPost}/replies', [ForumReplyController::class, 'store'])->name('forum-replies.store');
    Route::get('forum-replies/{forumReply}/edit', [ForumReplyController::class, 'edit'])->name('forum-replies.edit');
    Route::put('forum-replies/{forumReply}', [ForumReplyController::class, 'update'])->name('forum-replies.update');
    Route::delete('forum-replies/{forumReply}', [ForumReplyController::class, 'destroy'])->name('forum-replies.destroy');
    
    // Admin forum functions
    Route::middleware('admin')->group(function () {
        Route::patch('forum-posts/{forumPost}/pin', [ForumPostController::class, 'pin'])->name('forum-posts.pin');
        Route::patch('forum-posts/{forumPost}/lock', [ForumPostController::class, 'lock'])->name('forum-posts.lock');
        
        // Admin report functions
        Route::get('admin/reports', [ReportController::class, 'adminIndex'])->name('reports.admin');
        Route::patch('reports/{report}/status', [ReportController::class, 'updateStatus'])->name('reports.update-status');
    });
});

// ✅ Auth scaffolding (Laravel Breeze / Jetstream / Fortify)
require __DIR__.'/auth.php';

// Public promotions page (accessible to all)
Route::get('/promotions/public', [PromotionController::class, 'publicIndex'])->name('promotions.public');
