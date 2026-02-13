<?php

use App\Modules\Roles\Controllers\RoleController;
use App\Modules\Roles\Enums\SystemPermission;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('roles')->group(function () {
    Route::get('/', [RoleController::class, 'index'])
        ->middleware('can:' . SystemPermission::ViewRoles->value);

    Route::get('/{role}', [RoleController::class, 'show'])
        ->middleware('can:' . SystemPermission::ViewRoles->value);

    Route::get('/create', [RoleController::class, 'create'])
        ->middleware('can:' . SystemPermission::CreateRoles->value);

    Route::post('/', [RoleController::class, 'store'])
        ->middleware('can:' . SystemPermission::CreateRoles->value);

    Route::put('/{role}', [RoleController::class, 'update'])
        ->middleware('can:' . SystemPermission::EditRoles->value);

    Route::delete('/{role}', [RoleController::class, 'destroy'])
        ->middleware('can:' . SystemPermission::DeleteRoles->value);
});
