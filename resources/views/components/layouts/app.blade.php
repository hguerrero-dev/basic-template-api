<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? env('APP_NAME', 'Admin Panel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
{{-- Script para manejar errores de Livewire --}}
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.hook('request.error', ({ status, preventDefault }) => {
            let errorMessage = 'Ocurrió un error inesperado. Intente nuevamente.';
            
            if (status === 419) errorMessage = 'Su sesión ha expirado, por favor recargue la página.';
            if (status === 403) errorMessage = 'No tiene permisos para realizar esta acción.';
            if (status === 500) errorMessage = 'Error interno del servidor.';

            window.dispatchEvent(new CustomEvent('mary-toast', {
                detail: {
                    toast: {
                        type: 'error',
                        title: 'Error',
                        description: errorMessage,
                        timeout: 3000,
                        icon: '<svg class="w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>',
                        css: 'alert-error'
                    }
                }
            }));
            
            preventDefault(); 
        });
    });
</script>

<body class="bg-gray-100 font-sans antialiased text-gray-900">
    @auth
        <div class="min-h-screen flex flex-col sm:flex-row">
            
            <!-- Sidebar Global -->
            <x-ui.sidebar />

            <!-- Contenedor Principal -->
            <div class="flex-1 flex flex-col">
                
                <!-- Navbar Superior Global -->
                <x-ui.topbar />

                <!-- Contenido Dinámico de la Vista (Livewire) -->
                <main class="flex-1 p-6">
                    {{ $slot }}
                </main>
                
            </div>
        </div>
    @endauth
    @guest
        <!-- VISTA PARA INVITADOS (Login / Register) -->
        {{ $slot }}
    @endguest

    <!-- Modal Global -->
    <livewire:confirm-modal />
    
    <!-- Componente global Toast -->
    <x-toast />
    @livewireScripts
</body>
</html>