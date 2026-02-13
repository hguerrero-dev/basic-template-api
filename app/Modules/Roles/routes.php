<?php

use App\Modules\Roles\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('roles')->group(function () {
    Route::get('/', [RoleController::class, 'index']);

    Route::post('/', [RoleController::class, 'store'])
        ->middleware('can:crear roles');
});
