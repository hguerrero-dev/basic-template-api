<?php

use App\Modules\Auth\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:5,1'])->prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum', 'throttle:100,1')->post('logout', [AuthController::class, 'logout']);
});
