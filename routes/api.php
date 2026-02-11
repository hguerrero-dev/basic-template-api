<?php

use App\Modules\Auth\Controllers\LoginController;
use App\Modules\Users\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [LoginController::class, 'authenticate'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    // Grupo de rutas de Usuarios
    Route::prefix('users')->group(function () {

        // Listar usuarios (Solo si tiene permiso 'ver usuarios')
        Route::get('/', [UserController::class, 'index'])
            ->middleware('can:ver usuarios'); // Middleware de Spatie

        // Crear usuario (Solo si tiene permiso 'crear usuarios')
        Route::post('/', [UserController::class, 'store'])
            ->middleware('can:crear usuarios');
    });
});
