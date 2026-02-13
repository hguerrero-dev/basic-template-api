<?php

use App\Modules\Roles\Controllers\RoleController;
use App\Modules\Roles\Enums\RolePermission;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('roles')->group(function () {
    Route::get('/', [RoleController::class, 'index'])
        ->middleware('can:' . RolePermission::View->value);

    Route::get('/create', [RoleController::class, 'create'])
        ->middleware('can:' . RolePermission::Create->value);

    Route::get('/{role}', [RoleController::class, 'show'])
        ->middleware('can:' . RolePermission::View->value);

    Route::post('/', [RoleController::class, 'store'])
        ->middleware('can:' . RolePermission::Create->value);

    Route::put('/{role}', [RoleController::class, 'update'])
        ->middleware('can:' . RolePermission::Edit->value);

    Route::delete('/{role}', [RoleController::class, 'destroy'])
        ->middleware('can:' . RolePermission::Delete->value);
});
