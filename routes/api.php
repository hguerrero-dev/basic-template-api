<?php

use Illuminate\Support\Facades\Route;

require base_path('app/Modules/Auth/routes.php');
require base_path('app/Modules/Users/routes.php');
require base_path('app/Modules/Roles/routes.php');

Route::get('/ping', fn() => ['status' => 'ok']);

Route::middleware('auth:sanctum')->group(function () {});
