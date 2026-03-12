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
        
        <x-ui.card-grid>
            @forelse($roles as $role)
                <x-ui.card>
                    <x-slot:title>
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            {{ $role->name . ' - ' . $role->guard_name }}
                        </div>
                    </x-slot:title>
    
                    <div class="mt-2 flex flex-wrap gap-1">
                        @foreach($role->permissions as $permission)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $permission->name }}
                            </span>
                        @endforeach
                        
                        @if($role->permissions->isEmpty())
                            <span class="text-gray-400 text-sm italic">Sin permisos asignados</span>
                        @endif
                    </div>


                    <div class="mt-4"> 
                        <p class="text-sm text-gray-500 mt-2">{{ $role->description ?? 'Sin descripción' }}</p>
                    </div>
    
                    <x-slot:footer>
                        <div class="flex justify-end w-full gap-3">
                            @can(\App\Modules\Roles\Enums\RolePermission::Edit->value)
                            <button
                                wire:click="$dispatch('edit-role', { id: {{ $role->id }} })"
                                class="text-indigo-600 hover:text-indigo-900 text-sm font-medium transition-colors"
                            >
                                Editar
                            </button>
                            @endcan
    
                            @can(\App\Modules\Roles\Enums\RolePermission::Delete->value)
                            <x-button
                                label="Eliminar"
                                class="btn-ghost btn-sm text-error font-medium"
                                wire:click="confirmDelete({{ $role->id }}, '{{ $role->name }}')"
                            />
                            @endcan
                        </div>
                    </x-slot:footer>
                </x-ui.card>
            @empty
                <div class="col-span-1 sm:col-span-2 lg:col-span-3 xl:col-span-4 text-center text-gray-500 py-12 bg-white rounded-xl border border-gray-200 border-dashed">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <p class="text-lg font-medium text-gray-900">No se encontraron roles</p>
                    <p class="text-sm text-gray-500 mt-1">Ajusta tu búsqueda o crea un nuevo rol.</p>
                </div>
            @endforelse
        </x-ui.card-grid>
    
        @if($roles->hasPages())
            <div class="mt-6">
                {{ $roles->links() }}
            </div>
        @endif
    </div>
    
    <!-- Componente Modal para crear/editar roles -->
    <livewire:roles.role-form />

    <div wire:loading wire:target="confirmDelete">
        <x-ui.loader-modal />
    </div>
</div>