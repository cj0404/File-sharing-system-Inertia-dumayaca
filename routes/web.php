<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Public landing page
Route::get('/', fn() => inertia('Landing'))->name('home');

// Public share link (route protection handled in controller)
Route::get('/share/{token}', [FileController::class, 'share'])->name('files.share');

// Authenticated routes (Middleware: auth + verified)
Route::middleware(['auth', 'verified'])->group(function () {

    Route::resource('files', FileController::class);
    Route::get('/files/{file}/download', [FileController::class, 'download'])
        ->name('files.download');

    // Dashboard redirect
    Route::get('/dashboard', fn() => redirect()->route('files.index'))
        ->name('dashboard');

    // Breeze profile routes
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
