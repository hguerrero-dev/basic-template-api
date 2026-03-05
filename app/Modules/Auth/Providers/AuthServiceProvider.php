<?php

namespace App\Modules\Auth\Providers;

use App\Modules\Auth\Livewire\Login;
use App\Modules\Auth\Livewire\Logout;
use App\Modules\Auth\Livewire\Register;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // 1. Cargar la carpeta de vistas y asignarle el namespace "auth"
        $this->loadViewsFrom(base_path('app/Modules/Auth/Views'), 'auth');

        // 2. Registrar el componente Livewire
        Livewire::component('auth.login', Login::class);
        Livewire::component('auth.register', Register::class);
        Livewire::component('auth.logout', Logout::class);
    }

    protected function registerRoutes(): void {}
}
