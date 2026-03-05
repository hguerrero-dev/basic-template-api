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
    <x-ui.table>
        <x-slot:headers>
            <x-ui.th>Usuario</x-ui.th>
            <x-ui.th>Email</x-ui.th>
            <x-ui.th class="text-right">Acciones</x-ui.th>
        </x-slot:headers>

        @forelse($users as $user)
            <tr class="hover:bg-gray-50 transition-colors">
                <x-ui.td class="font-medium text-gray-900">
                    {{ $user->name ?? $user->username }}
                </x-ui.td>
                
                <x-ui.td>
                    {{ $user->email }}
                </x-ui.td>
                
                <x-ui.td class="text-right font-medium">
                    <button wire:click="$dispatch('edit-user', { id: '{{ $user->id }}' })" class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</button>
                    <button class="text-red-600 hover:text-red-900">Eliminar</button>
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

    <!-- Componente Modal para crear / editar usuario -->
    <livewire:users.user-form />

</div>