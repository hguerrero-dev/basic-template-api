<header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 shadow-sm">
    <div>
        <span class="text-gray-500 text-sm font-medium">Panel de Control / <span class="text-gray-900">Inicio</span></span>
    </div>

    <div class="flex items-center gap-6">
        <div class="flex items-center gap-2">
            {{-- Notification icon --}}
            <div class="relative flex items-center mr-2"> 
                {{-- Componente de Notificaciones de Livewire --}}
            <livewire:notification />
            </div>

            <!-- Un ícono de perfil genérico al lado del nombre -->
            <div x-data="{ imgError: false }" class="h-8 w-8 min-w-8 shrink-0 rounded-full flex items-center justify-center bg-indigo-100 text-indigo-700 font-bold text-sm uppercase shadow-sm border border-gray-200 overflow-hidden">
                @if(auth()->user()->avatar)
                    <!-- Intentará cargar la imagen, si falla (ej. URL rota o con restricción), disparará imgError = true -->
                    <img 
                        src="{{ str_starts_with(auth()->user()->avatar, 'http') ? auth()->user()->avatar : Storage::url(auth()->user()->avatar) }}" 
                        alt="{{ strtoupper(substr(trim(auth()->user()->name ?? auth()->user()->username ?? 'A'), 0, 1)) }}" 
                        class="h-full w-full object-cover"
                        x-show="!imgError"
                        x-on:error="imgError = true"
                    >
                    <!-- Fallback en caso de que la imagen dé error al cargar -->
                    <span x-show="imgError" style="display: none;">
                        {{ strtoupper(substr(trim(auth()->user()->name ?? auth()->user()->username ?? 'A'), 0, 1)) }}
                    </span>
                @else
                    <!-- Fallback en caso de que no haya campo avatar en la base de datos -->
                    <span>{{ strtoupper(substr(trim(auth()->user()->name ?? auth()->user()->username ?? 'A'), 0, 1)) }}</span>
                @endif
            </div>
            
            <span class="text-sm font-medium text-gray-700">{{ auth()->user()->username ?? 'Administrador' }}</span>
        </div>
        
        <!-- Línea divisoria sutil -->
        <div class="h-6 w-px bg-gray-300"></div>

        <!-- Componente Livewire de Logout -->
        <livewire:auth.logout />
    </div>
</header>