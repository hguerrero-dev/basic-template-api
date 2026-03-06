<?php

use App\Modules\Audit\Enums\AuditPermission;
use App\Modules\Audit\Livewire\AuditList;
use Illuminate\Support\Facades\Route;
use App\Modules\Auth\Livewire\Login;
use App\Modules\Auth\Livewire\Register;
use App\Modules\Roles\Enums\RolePermission;
use App\Modules\Roles\Livewire\RoleList;
use App\Modules\Users\Enums\UserPermission;
use App\Modules\Users\Livewire\UserList;

// Cuando ya inicie sesión, entrará aquí (Dashboard temporal)
Route::middleware('auth:web')->group(function () {
    Route::get('/', function () {
        return view('welcome-dashboard');
    })->name('dashboard');

    Route::get('/users', UserList::class)
        ->name('users.index')
        ->middleware('can:' . UserPermission::View->value);

    Route::get('/roles', RoleList::class)
        ->name('roles.index')
        ->middleware('can:' . RolePermission::View->value);

    Route::get('/audit', AuditList::class)
        ->name('audit.index')
        ->middleware('can:' . AuditPermission::View->value);
});

// Rutas de invitados (Login y Registro)
Route::middleware('guest:web')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
});
