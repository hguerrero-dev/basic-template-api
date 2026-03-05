<x-ui.modal name="user-form-modal" title="{{ $userId ? 'Editar Usuario' : 'Crear Nuevo Usuario' }}">
    <form wire:submit.prevent="save">
        <div class="space-y-4">
            
            <!-- Campo Nombre -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Nombre</label>
                <input type="text" wire:model="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border px-3 py-2">
                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <!-- Campo Username -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" wire:model="username" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border px-3 py-2">
                    @error('username') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Campo Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                    <input type="email" wire:model="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border px-3 py-2">
                    @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Campo Contraseña -->
            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Contraseña 
                    @if($userId) <span class="text-gray-400 font-normal">(Dejar en blanco para no cambiar)</span> @endif
                </label>
                <input type="password" wire:model="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border px-3 py-2">
                @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

        </div>

        <div class="flex justify-end gap-3 mt-6">
            <button type="button" x-on:click="show = false" class="px-4 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-md transition-colors text-sm font-medium shadow-sm">
                Cancelar
            </button>
            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md transition-colors text-sm font-medium shadow-sm flex items-center gap-2">
                <span wire:loading.remove wire:target="save">Guardar</span>
                <span wire:loading wire:target="save">Cargando...</span>
            </button>
        </div>
    </form>
</x-ui.modal>