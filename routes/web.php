<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Auth\Livewire\Login;
use App\Modules\Auth\Livewire\Register;

// Cuando ya inicie sesión, entrará aquí (Dashboard temporal)
Route::middleware('auth:web')->group(function () {
    Route::get('/', function () {
        return "¡Iniciaste sesión con éxito! Bienvenido: " . auth()->user()->name;
    })->name('dashboard');
});

// Rutas de invitados (Login y Registro)
Route::middleware('guest:web')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
});
