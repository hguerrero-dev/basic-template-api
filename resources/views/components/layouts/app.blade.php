<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? env('APP_NAME', 'Admin Panel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
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
    @livewireScripts
</body>
</html>