<?php

use App\Http\Controllers\Api\V1\FilmController;
use App\Http\Controllers\Api\V1\PersonController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/films', [FilmController::class, 'index']);
    Route::get('/films/{id}', [FilmController::class, 'show']);
    
    Route::get('/people', [PersonController::class, 'index']);
    Route::get('/people/{id}', [PersonController::class, 'show']);
}); 