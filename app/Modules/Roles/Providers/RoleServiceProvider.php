<?php

namespace App\Modules\Roles\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class RoleServiceProvider extends ServiceProvider
{

    public function boot(): void
    {
        $this->registerRoutes();
    }

    protected function registerRoutes(): void
    {
        Route::prefix('api')
            ->middleware('api')
            ->group(function () {
                $this->loadRoutesFrom(__DIR__ . '/../routes.php');
            });
    }
}
