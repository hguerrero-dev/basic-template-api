<?php

use App\Modules\Users\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('users')->group(function () {
    // Listar usuarios (Solo si tiene permiso 'ver usuarios')
    Route::get('/', [UserController::class, 'index'])
        ->middleware('can:ver usuarios');

    // Crear usuario (Solo si tiene permiso 'crear usuarios')
    Route::post('/', [UserController::class, 'store'])
        ->middleware('can:crear usuarios');
});
