<?php

namespace App\Modules\Roles\Providers;

use App\Modules\Roles\Livewire\RoleForm;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class RoleServiceProvider extends ServiceProvider
{

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../Views', 'roles');

        Livewire::component('roles.role-form', RoleForm::class);
    }

    protected function registerRoutes(): void {}
}
