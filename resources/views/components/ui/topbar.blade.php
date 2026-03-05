<header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 shadow-sm">
    <div>
        <span class="text-gray-500 text-sm font-medium">Panel de Control / <span class="text-gray-900">Inicio</span></span>
    </div>

    <div class="flex items-center gap-6">
        <div class="flex items-center gap-2">
            <!-- Un ícono de perfil genérico al lado del nombre -->
            <div class="bg-indigo-100 text-indigo-700 w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm">
                {{ substr(auth()->user()->username ?? 'A', 0, 1) }}
            </div>
            <span class="text-sm font-medium text-gray-700">{{ auth()->user()->username ?? 'Administrador' }}</span>
        </div>
        
        <!-- Línea divisoria sutil -->
        <div class="h-6 w-px bg-gray-300"></div>

        <!-- Componente Livewire de Logout -->
        <livewire:auth.logout />
    </div>
</header>