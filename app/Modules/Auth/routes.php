<?php

use App\Modules\Auth\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [LoginController::class, 'authenticate'])->name('login');
