<?php

use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('api/auth')->group(function () {
    Route::post('login', [LoginController::class, 'login']);
    Route::post('logout', [LoginController::class, 'logout'])->middleware('auth');
});

Route::prefix('api/admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index']);
    Route::get('profile', [ProfileController::class, 'show']);
    Route::put('profile', [ProfileController::class, 'update']);
    Route::put('profile/password', [ProfileController::class, 'changePassword']);

    // Article listing and viewing (require articles.view permission)
    Route::middleware('permission:articles.view')->group(function () {
        Route::get('articles', [ArticleController::class, 'index']);
        Route::get('articles/{article}', [ArticleController::class, 'show']);
    });

    // Article creation (require articles.create permission)
    Route::middleware('permission:articles.create')->group(function () {
        Route::get('articles/create', [ArticleController::class, 'create']); // For form data
        Route::post('articles', [ArticleController::class, 'store']);
    });

    // Article editing (require articles.edit permission)
    Route::middleware('permission:articles.edit')->group(function () {
        Route::get('articles/{article}/edit', [ArticleController::class, 'edit']); // For form data
        Route::put('articles/{article}', [ArticleController::class, 'update']);
        Route::patch('articles/{article}', [ArticleController::class, 'update']);
    });

    // Article deletion (require articles.delete permission)
    Route::middleware('permission:articles.delete')->group(function () {
        Route::delete('articles/{article}', [ArticleController::class, 'destroy']);
    });

    // Article publishing workflow (require articles.publish permission)
    Route::middleware('permission:articles.publish')->group(function () {
        Route::post('articles/{article}/publish', [ArticleController::class, 'publish']);
        Route::post('articles/{article}/unpublish', [ArticleController::class, 'unpublish']);
    });

    // Article management actions (require articles.edit permission)
    Route::middleware('permission:articles.edit')->group(function () {
        Route::post('articles/{article}/archive', [ArticleController::class, 'archive']);
        Route::post('articles/{article}/toggle-featured', [ArticleController::class, 'toggleFeatured']);
    });

    // Bulk actions for articles (require articles.edit permission)
    Route::middleware('permission:articles.edit')->group(function () {
        Route::post('articles/bulk/publish', [ArticleController::class, 'bulkPublish']);
        Route::post('articles/bulk/archive', [ArticleController::class, 'bulkArchive']);
        Route::post('articles/bulk/delete', [ArticleController::class, 'bulkDelete']);
        Route::post('articles/bulk/toggle-featured', [ArticleController::class, 'bulkToggleFeatured']);
    });
});
