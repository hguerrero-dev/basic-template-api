<div>
    <!-- Encabezado y buscador -->
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
        <h2 class="text-2xl font-bold text-gray-800">Gestión de Usuarios</h2>
        
        <div class="flex gap-2 w-full sm:w-auto">
            <input 
                type="text" 
                wire:model.live.debounce.300ms="search" 
                placeholder="Buscar usuario..." 
                class="w-full sm:w-80 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 px-4 py-2 border"
            >
            <button 
                wire:click="$dispatch('create-user')"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium shadow-sm transition-colors"
            >
                + Nuevo
            </button>
        </div>
    </div>

    <!-- Implementando tu nuevo componente global de Tabla -->
    <div wire:target="search, nextPage, previousPage, gotoPage" wire:loading.class="opacity-50 pointer-events-none transition-opacity duration-200" class="relative">
        
        <!-- Un pequeño spinner de carga centrado -->
        <div wire:target="search, nextPage, previousPage, gotoPage" wire:loading class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-10">
            <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>

        <x-ui.table>
            <x-slot:headers>
            <x-ui.th>Nombre completo</x-ui.th>
            <x-ui.th>Nombre de usuario</x-ui.th>
            <x-ui.th>Email</x-ui.th>
            <x-ui.th>Estado</x-ui.th>
            <x-ui.th class="text-right">Acciones</x-ui.th>
        </x-slot:headers>

        @forelse($users as $user)
            <tr class="hover:bg-gray-50 transition-colors">
                <x-ui.td class="font-medium text-gray-900">
                    {{ is_array($user) ? ($user['name'] ?? $user['username']) : ($user->name ?? $user->username) }}
                </x-ui.td>

                <x-ui.td>
                    {{ is_array($user) ? $user['username'] : $user->username }}
                </x-ui.td>

                <x-ui.td>
                    {{ is_array($user) ? $user['email'] : $user->email }}
                </x-ui.td>
                
                <x-ui.td class="text-right font-medium">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Activo
                    </span>
                </x-ui.td>

                <x-ui.td class="text-right font-medium">
                    <button wire:click="$dispatch('edit-user', { id: '{{ is_array($user) ? $user['id'] : $user->id }}' })" class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</button>
                    <button 
                        x-on:click="$dispatch('open-confirm', { 
                            title: 'Eliminar Usuario', 
                            message: '¿Estás seguro de que deseas eliminar a {{ is_array($user) ? ($user['name'] ?? $user['username']) : ($user->name ?? $user->username) }}?', 
                            event: 'delete-user', 
                            params: { id: '{{ is_array($user) ? $user['id'] : $user->id }}' } 
                        })"
                        class="text-red-600 hover:text-red-900"
                    >
                        Eliminar
                    </button>
                </x-ui.td>
            </tr>
        @empty
            <tr>
                <x-ui.td colspan="3" class="py-10 text-center text-gray-500">
                    No se encontraron usuarios.
                </x-ui.td>
            </tr>
        @endforelse

        <!-- Paginación -->
        @if($users->hasPages())
            <x-slot:pagination>
                {{ $users->links() }}
            </x-slot:pagination>
        @endif
    </x-ui.table>
    </div>

    <!-- Componente Modal para crear / editar usuario -->
    <livewire:users.user-form />

</div>