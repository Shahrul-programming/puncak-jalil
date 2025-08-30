<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Controllers
use App\Http\Controllers\SiteSettingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ForumPostController;
use App\Http\Controllers\ForumReplyController;
use App\Http\Controllers\ReportController;

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

// ✅ Route admin (guna middleware auth + admin)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/site-setting', [SiteSettingController::class, 'edit'])
        ->name('admin.site-setting.edit');
    Route::post('/admin/site-setting', [SiteSettingController::class, 'update'])
        ->name('admin.site-setting.update');
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

    // Resource routes
    Route::resource('shops', ShopController::class);
    Route::resource('promotions', PromotionController::class);
    Route::resource('events', EventController::class);
    Route::resource('forum-posts', ForumPostController::class);
    Route::resource('forum-replies', ForumReplyController::class);
    Route::resource('reports', ReportController::class);
});

// ✅ Auth scaffolding (Laravel Breeze / Jetstream / Fortify)
require __DIR__.'/auth.php';
