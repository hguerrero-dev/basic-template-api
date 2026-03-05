<div>
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
        <h2 class="text-2xl font-bold text-gray-800">Gestión de Roles y Permisos</h2>

        <div class="flex gap-2 w-full sm:w-auto">
            <input
                type="text"
                wire:model.live.debounce.100ms="search"
                placeholder="Buscar rol..."
                class="w-full sm:w-80 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 px-4 py-2 border"
            >
            @can(\App\Modules\Roles\Enums\RolePermission::Create->value)
            <button
                wire:click="$dispatch('create-role')"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium shadow-sm transition-colors"
            >
                + Nuevo
            </button>
            @endcan
        </div>
    </div>

    <div wire:target="search, nextPage, previousPage, gotoPage" wire:loading.class="opacity-50 pointer-events-none transition-opacity duration-200" class="relative">
        <div wire:target="search, nextPage, previousPage, gotoPage" wire:loading class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-10">
            <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.933V17h4v-1.709z"></path>
            </svg>
        </div>
        
        <x-ui.table>
            <x-slot:headers>
                <x-ui.th>Nombre del Rol</x-ui.th>
                <x-ui.th>Permisos</x-ui.th>
                <x-ui.th class="text-right">Acciones</x-ui.th>
            </x-slot:headers>
    
            @forelse($roles as $role)
                <tr class="hover:bg-gray-50 transition-colors">
                    <x-ui.td class="font-medium text-gray-900">
                        {{ $role->name }}
                    </x-ui.td>
    
                    <x-ui.td>
                        @foreach($role->permissions as $permission)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 mr-1 mb-1">
                                {{ $permission->name }}
                            </span>
                        @endforeach
                    </x-ui.td>
    
                    <x-ui.td class="text-right">
                        @can(\App\Modules\Roles\Enums\RolePermission::Edit->value)
                        <button
                            wire:click="$dispatch('edit-role', { id: {{ $role->id }} })"
                            class="text-indigo-600 hover:text-indigo-900 text-sm font-medium"
                        >
                            Editar
                        </button>
                        @endcan
    
                        @can(\App\Modules\Roles\Enums\RolePermission::Delete->value)
                        <button
                            wire:click="$dispatch('delete-role', { id: {{ $role->id }} })"
                            class="text-red-600 hover:text-red-900 text-sm font-medium ml-4"
                        >
                            Eliminar
                        </button>
                        @endcan
                    </x-ui.td>
                </tr>
            @empty
                <tr>
                    <x-ui.td colspan="3" class="text-center text-gray-500 py-4">
                        No se encontraron roles.
                    </x-ui.td>
                </tr>
            @endforelse
    
            @if($roles->hasPages())
                <tr>
                    <x-ui.td colspan="3" class="px-6 py-4">
                        {{ $roles->links() }}
                    </x-ui.td>
                </tr>
            @endif
        </x-ui.table>
    </div>

</div>