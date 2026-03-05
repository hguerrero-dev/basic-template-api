<div>
    <!-- Encabezado y buscador -->
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
        <h2 class="text-2xl font-bold text-gray-800">Gestión de Usuarios</h2>
        
        <div class="flex gap-2 w-full sm:w-auto">
            <input 
                type="text" 
                wire:model.live.debounce.100ms="search" 
                placeholder="Buscar usuario..." 
                class="w-full sm:w-80 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 px-4 py-2 border"
            >
            @can(\App\Modules\Users\Enums\UserPermission::Create->value)
            <button 
                wire:click="$dispatch('create-user')"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium shadow-sm transition-colors"
            >
                + Nuevo
            </button>
            @endcan
        </div>
    </div>

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
                <x-ui.th>Roles</x-ui.th>
                <x-ui.th>Estado</x-ui.th>
                <x-ui.th class="text-right">Acciones</x-ui.th>
            </x-slot:headers>

            @forelse($users as $user)
                <tr class="hover:bg-gray-50 transition-colors">
                    <x-ui.td class="font-medium text-gray-900">
                        {{ $user->name ?? $user->username }}
                    </x-ui.td>

                    <x-ui.td>
                        {{ $user->username }}
                    </x-ui.td>

                    <x-ui.td>
                        {{ $user->email }}
                    </x-ui.td>

                    <x-ui.td> 
                       @php
                           $colors = [
                               'bg-blue-100 text-blue-800',
                               'bg-purple-100 text-purple-800',
                               'bg-pink-100 text-pink-800',
                               'bg-indigo-100 text-indigo-800',
                               'bg-teal-100 text-teal-800',
                               'bg-cyan-100 text-cyan-800',
                               'bg-orange-100 text-orange-800',
                           ];
                       @endphp
                       @foreach($user->roles as $role)
                           @php
                               $colorClass = $colors[abs(crc32($role->name)) % count($colors)];
                           @endphp
                           <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $colorClass }} mr-1">
                               {{ $role->name }}
                           </span>
                       @endforeach
                    </x-ui.td>
                    
                    <x-ui.td>
                        @php
                            $statusValue = $user->status instanceof \App\Modules\Users\Enums\UserStatus ? $user->status->value : $user->status;
                            $statusClass = match($statusValue) {
                                'active' => 'bg-green-100 text-green-800',
                                'inactive' => 'bg-red-100 text-red-800',
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'banned' => 'bg-gray-100 text-gray-800',
                                default => 'bg-gray-100 text-gray-800'
                            };
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                            {{ ucfirst($statusValue) }}
                        </span>
                    </x-ui.td>

                    <x-ui.td class="text-right font-medium">
                        @can(\App\Modules\Users\Enums\UserPermission::Edit->value)
                        <button wire:click="$dispatch('edit-user', { id: '{{ $user->id }}' })" class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</button>
                        @endcan

                        @can(\App\Modules\Users\Enums\UserPermission::Delete->value)
                        <button 
                            x-on:click="$dispatch('open-confirm', { 
                                title: 'Eliminar Usuario', 
                                message: '¿Estás seguro de que deseas eliminar a {{ $user->name ?? $user->username }}?', 
                                event: 'delete-user', 
                                params: { id: '{{ $user->id }}' } 
                            })"
                            class="text-red-600 hover:text-red-900"
                        >
                            Eliminar
                        </button>
                        @endcan
                    </x-ui.td>
                </tr>
            @empty
                <tr>
                    <x-ui.td colspan="6" class="py-10 text-center text-gray-500">
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
