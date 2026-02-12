<?php

namespace App\Modules\Users\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
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
