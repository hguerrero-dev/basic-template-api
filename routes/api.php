<?php

use Illuminate\Support\Facades\Route;

Route::get('/ping', fn() => ['status' => 'ok']);

Route::middleware('auth:sanctum')->group(function () {
    // Otras rutas autenticadas pueden ir aquí...
});
