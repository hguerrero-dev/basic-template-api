<?php

namespace App\Modules\Audit\Providers;

use Illuminate\Support\ServiceProvider;

class AuditServiceProvider extends ServiceProvider
{

    public function boot(): void
    {
        // $this->loadViewsFrom(__DIR__ . '/../Views', 'audit');
    }

    protected function registerRoutes(): void {}
}
