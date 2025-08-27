<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PublicArticleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/api/articles/load-more', [HomeController::class, 'loadMore'])->name('articles.load-more');

// Static pages
Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

// Public article routes (đặt trước admin routes để tránh conflict)
Route::get('/{article:slug}', [PublicArticleController::class, 'show'])->name('article.show');

Route::prefix('admin')->name('admin.')->group(function () {
    // Login routes
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login'])->name('login.post');
});

// Authenticated admin routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Logout
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // Profile routes
    Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [ProfileController::class, 'changePassword'])->name('profile.password');

    // Activity logs routes (chỉ admin có permission system.view_logs)
    Route::prefix('logs')->name('logs.')->group(function () {
        Route::get('/', [ActivityLogController::class, 'index'])->name('index');
        Route::get('/export', [ActivityLogController::class, 'export'])->name('export');
        Route::post('/cleanup', [ActivityLogController::class, 'cleanup'])->name('cleanup');
    });

    // User management routes (chỉ admin)
    Route::middleware('permission:users.manage')->group(function () {
        Route::resource('users', UserController::class);
        Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
    });

    // User viewing routes (admin và editor có thể xem)
    Route::middleware('permission:users.view')->group(function () {
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');
    });

    // ============================================================================
    // ARTICLE ROUTES - ĐẶT ROUTES CỤ THỂ TRƯỚC ROUTES CÓ PARAMETER
    // ============================================================================

    // Article listing routes - ĐẶT ĐẦU TIÊN
    Route::middleware('permission:articles.view')->group(function () {
        Route::get('articles', [ArticleController::class, 'index'])->name('articles.index');

        // API route for AJAX/JSON requests
        Route::get('api/articles', [ArticleController::class, 'api'])->name('articles.api');
    });

    // Article creation routes - ĐẶT SAU index
    Route::middleware('permission:articles.create')->group(function () {
        Route::get('articles/create', [ArticleController::class, 'create'])->name('articles.create');
        Route::post('articles', [ArticleController::class, 'store'])->name('articles.store');
    });

    // Article editing routes - ĐẶT TRƯỚC routes có {article} khác
    Route::middleware('permission:articles.edit')->group(function () {
        Route::get('articles/{article}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
        Route::put('articles/{article}', [ArticleController::class, 'update'])->name('articles.update');
        Route::patch('articles/{article}', [ArticleController::class, 'update'])->name('articles.patch');
    });

    // Article workflow management routes (publishing, archiving, featuring)
    Route::middleware('permission:articles.publish')->group(function () {
        Route::post('articles/{article}/publish', [ArticleController::class, 'publish'])->name('articles.publish');
        Route::post('articles/{article}/unpublish', [ArticleController::class, 'unpublish'])->name('articles.unpublish');
    });

    // Article general management routes (archiving, featuring - available for editors)
    Route::middleware('permission:articles.edit')->group(function () {
        Route::post('articles/{article}/archive', [ArticleController::class, 'archive'])->name('articles.archive');
        Route::post('articles/{article}/toggle-featured', [ArticleController::class, 'toggleFeatured'])->name('articles.toggle-featured');
    });

    // Article deletion routes
    Route::middleware('permission:articles.delete')->group(function () {
        Route::delete('articles/{article}', [ArticleController::class, 'destroy'])->name('articles.destroy');
    });

    // Article viewing routes - ĐẶT CUỐI CÙNG vì có {article} parameter
    Route::middleware('permission:articles.view')->group(function () {
        Route::get('articles/{article}', [ArticleController::class, 'show'])->name('articles.show');
    });

    // Alternative resource route approach (commented out as we use explicit routes above)
    /*
    Route::resource('articles', ArticleController::class)->parameters([
        'articles' => 'article:slug' // Use slug instead of id for SEO-friendly URLs
    ]);
    */
});

// ============================================================================
// PUBLIC ARTICLE ROUTE - ĐẶT CUỐI CÙNG ĐỂ TRÁNH CONFLICT
// ============================================================================
// Route này sẽ match tất cả các URL không match với routes trên
Route::get('/{article:slug}', [PublicArticleController::class, 'show'])->name('article.show');
