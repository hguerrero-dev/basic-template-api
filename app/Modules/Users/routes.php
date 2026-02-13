<?php

use App\Modules\Users\Controllers\UserController;
use App\Modules\Users\Enums\UserPermission;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('users')->group(function () {
    Route::get('/options', [UserController::class, 'getFormOptions'])
        ->middleware('can:' . UserPermission::View->value);

    Route::get('/', [UserController::class, 'index'])
        ->middleware('can:' . UserPermission::View->value);

    Route::get('/{user}', [UserController::class, 'show'])
        ->middleware('can:' . UserPermission::View->value);

    Route::post('/', [UserController::class, 'store'])
        ->middleware('can:' . UserPermission::Create->value);

    Route::put('/{user}', [UserController::class, 'update'])
        ->middleware('can:' . UserPermission::Edit->value);

    Route::delete('/{user}', [UserController::class, 'destroy'])
        ->middleware('can:' . UserPermission::Delete->value);
});
