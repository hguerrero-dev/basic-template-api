<?php

use App\Modules\Users\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('users')->group(function () {
    Route::get('/options', [UserController::class, 'getFormOptions'])
        ->middleware('can:ver usuarios');

    Route::get('/', [UserController::class, 'index'])
        ->middleware('can:ver usuarios');

    Route::post('/', [UserController::class, 'store'])
        ->middleware('can:crear usuarios');
});
