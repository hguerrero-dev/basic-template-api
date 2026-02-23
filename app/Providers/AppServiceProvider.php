<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerModulesRoutes();
    }

    public function registerModulesRoutes(): void
    {
        $modulesPath = app_path('Modules');
        if (File::exists($modulesPath)) {
            $modules = File::directories($modulesPath);

            Route::prefix('api/v1')
                ->middleware('api')
                ->group(function () use ($modules) {
                    foreach ($modules as $module) {
                        $routesPath = $module . '/routes.php';
                        if (File::exists($routesPath)) {
                            require $routesPath;
                        }
                    }
                });
        }
    }
}
