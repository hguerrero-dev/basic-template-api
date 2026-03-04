<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Spatie\Health\Checks\Checks\CacheCheck;
use Spatie\Health\Checks\Checks\DatabaseCheck;
use Spatie\Health\Checks\Checks\PingCheck;
use Spatie\Health\Checks\Checks\RedisCheck;
use Spatie\Health\Checks\Checks\UsedDiskSpaceCheck;
use Spatie\Health\Facades\Health;

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

        Health::checks([
            DatabaseCheck::new(),
            RedisCheck::new(),
            CacheCheck::new(),

            UsedDiskSpaceCheck::new()
                ->warnWhenUsedSpaceIsAbovePercentage(70)
                ->failWhenUsedSpaceIsAbovePercentage(90),

            PingCheck::new()
                ->url(config('filesystems.disks.minio.endpoint') . '/minio/health/live')
                ->name('Minio Storage Health'),
        ]);
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
