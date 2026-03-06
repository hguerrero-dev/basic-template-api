<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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

            window.dispatchEvent(new CustomEvent('notify', {
                detail: {
                    type: 'error',
                    message: errorMessage
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
    <x-ui.confirm-modal />
    
    <!-- Componente global Toast -->
    <x-ui.toast />
    @livewireScripts
</body>
</html>