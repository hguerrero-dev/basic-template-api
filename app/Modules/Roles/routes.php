<?php

use App\Modules\Roles\Controllers\RoleController;
use App\Modules\Roles\Enums\SystemPermission;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('roles')->group(function () {
    Route::get('/', [RoleController::class, 'index'])
        ->middleware('can:' . SystemPermission::ViewRoles->value);

    Route::post('/', [RoleController::class, 'store'])
        ->middleware('can:' . SystemPermission::CreateRoles->value);
});
