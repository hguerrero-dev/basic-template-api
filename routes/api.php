<?php

use Illuminate\Support\Facades\Route;

require base_path('app/Modules/Auth/routes.php');
require base_path('app/Modules/Users/routes.php');
require base_path('app/Modules/Roles/routes.php');

Route::get('/ping', fn() => ['status' => 'ok']);

// Endpoint temporal para probar logs y alertas
// Route::get('/test-error', function () {
//     // 1. Lanzamos una alerta de error manual
//     \Log::error('🚨 [TEST] Este es un error manual enviado desde el Logger.');

//     // 2. Forzamos una excepción 500 no controlada del sistema
//     throw new \Exception('🔥 [TEST] ¡Excepción crítica simulada en la API!');
// });

Route::middleware('auth:sanctum')->group(function () {});
