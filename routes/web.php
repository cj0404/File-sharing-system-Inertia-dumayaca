<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Public landing page
Route::get('/', fn() => inertia('Landing'))->name('home');

// Public files page
Route::get('/public', fn() => inertia('Files/Public', ['files' => \App\Models\File::where('is_public', true)->latest()->get()]))->name('files.public');

// Public share link (route protection handled in controller)
Route::get('/share/{token}', [FileController::class, 'share'])->name('files.share');



Route::middleware(['auth', 'verified'])->group(function () {

    Route::resource('files', FileController::class);
    Route::get('/files/{file}/download', [FileController::class, 'download'])
        ->name('files.download');

    // New features

    Route::get('/files-starred', fn() => inertia('Files/Favorites', ['files' => auth()->user()->files()->where('starred', true)->get()]))
        ->name('files.starred');

    Route::get('/tags', fn() => inertia('Tags'))
        ->name('tags.index');

    // Dashboard
    Route::get('/dashboard', fn() => inertia('Dashboard'))
        ->name('dashboard');

    // Breeze profile routes
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
