<?php

namespace App\Modules\Users\Providers;

use App\Modules\Users\Livewire\UserForm;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class UserServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../Views', 'users');

        Livewire::component('users.user-form', UserForm::class);
    }

    protected function registerRoutes(): void {}
}
