<header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 shadow-sm">
    <div>
        <span class="text-gray-500 text-sm font-medium">Panel de Control / <span class="text-gray-900">Inicio</span></span>
    </div>

    <div class="flex items-center gap-6">
        <div class="flex items-center gap-2">
            {{-- Notification icon --}}
            <div class="relative flex items-center mr-2"> 
                <button class="p-2 rounded-full hover:bg-gray-100 text-gray-500 hover:text-gray-700 transition-colors focus:outline-none">
                    <!-- Icono de campana (outline) -->
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    
                    <!-- Indicador rojo (Ping) -->
                    <span class="absolute top-1.5 right-1.5 block w-2.5 h-2.5 rounded-full bg-red-500 ring-2 ring-white">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                    </span>
                </button>
            </div>

            <!-- Un ícono de perfil genérico al lado del nombre -->
            @if(auth()->user()->avatar)
                <img src="{{ str_starts_with(auth()->user()->avatar, 'http') ? auth()->user()->avatar : Storage::url(auth()->user()->avatar) }}" alt="{{ auth()->user()->username }}" class="h-8 w-8 min-w-8 rounded-full object-cover border border-gray-200 shadow-sm">
            @else
                <div class="h-8 w-8 min-w-8 shrink-0 bg-indigo-100 text-indigo-700 rounded-full flex items-center justify-center font-bold text-sm uppercase shadow-sm overflow-hidden">
                    <span>{{ strtoupper(substr(trim(auth()->user()->username ?? 'A'), 0, 1)) }}</span>
                </div>
            @endif
            
            <span class="text-sm font-medium text-gray-700">{{ auth()->user()->username ?? 'Administrador' }}</span>
        </div>
        
        <!-- Línea divisoria sutil -->
        <div class="h-6 w-px bg-gray-300"></div>

        <!-- Componente Livewire de Logout -->
        <livewire:auth.logout />
    </div>
</header>