<?php

use App\Http\Controllers\Api\FileApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('files')->group(function () {
    Route::get('/',          [FileApiController::class, 'index']);
    Route::get('/{token}',   [FileApiController::class, 'show']);
});
