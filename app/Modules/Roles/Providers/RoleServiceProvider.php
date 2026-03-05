<?php

namespace App\Modules\Roles\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class RoleServiceProvider extends ServiceProvider
{

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../Views', 'roles');
    }

    protected function registerRoutes(): void {}
}
