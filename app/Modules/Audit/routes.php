<?php

use App\Modules\Audit\Controllers\AuditController;
use App\Modules\Audit\Enums\AuditPermission;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('audits')->group(function () {

    Route::get('/', [AuditController::class, 'index'])
        ->middleware('can:' . AuditPermission::View->value);

    Route::get('/{id}', [AuditController::class, 'show'])
        ->middleware('can:' . AuditPermission::View->value);
});
