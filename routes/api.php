<?php

use App\Http\Controllers\Api\FileApiController;
use App\Http\Controllers\Api\FileCommentController;
use App\Http\Controllers\Api\FileStarController;
use App\Http\Controllers\Api\FileStatsController;
use App\Http\Controllers\Api\FileTagController;
use Illuminate\Support\Facades\Route;

Route::prefix('files')->group(function () {
    Route::get('/',          [FileApiController::class, 'index']);
    Route::get('/{token}',   [FileApiController::class, 'show']);
});

// Protected routes (require authentication)
Route::middleware(['auth'])->group(function () {
    // File statistics
    Route::get('/stats', [FileStatsController::class, 'index']);
    
    // Star/unstar files
    Route::post('/{file}/star', [FileStarController::class, 'toggle']);
    
    // Tags
    Route::prefix('tags')->group(function () {
        Route::get('/', [FileTagController::class, 'index']);
        Route::post('/', [FileTagController::class, 'store']);
        Route::post('/{tag}/attach/{file}', [FileTagController::class, 'attach']);
        Route::delete('/{tag}/detach/{file}', [FileTagController::class, 'detach']);
    });
    
    // Comments on files
    Route::prefix('{file}/comments')->group(function () {
        Route::get('/', [FileCommentController::class, 'index']);
        Route::post('/', [FileCommentController::class, 'store']);
        Route::delete('{comment}', [FileCommentController::class, 'destroy']);
    });
});
