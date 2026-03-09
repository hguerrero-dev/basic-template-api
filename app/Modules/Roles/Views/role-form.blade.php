<x-ui.modal name="role-form-modal" title="{{ $roleId ? 'Editar Rol' : 'Crear Nuevo Rol' }}">
    <form wire:submit.prevent="save">
        <div class="space-y-4">
            
            <!-- Campo Nombre -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Nombre del Rol</label>
                <input type="text" wire:model="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border px-3 py-2">
                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Descripción del Rol</label>
                <textarea type="text" wire:model="description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border px-3 py-2" placeholder="Descripción del rol..."></textarea>
            </div>

             <div class="space-y-4">
                <!-- Select de Permisos -->
                <div class="mb-2">
                    <label class="block text-sm font-medium text-gray-700">Guard del Rol</label>
                </div>

                <div class="block text-sm font-medium text-gray-700">
                    <select wire:model="guard" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border px-3 py-2">
                        <option value="api">API</option>
                        <option value="web">WEB</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Permisos del Rol</label>
                    <div x-data="{ guard: @entangle('guard') }">
                        <div x-show="guard === 'api'">
                            <x-ui.select-multiple
                                wire:model="permissionsApi"
                                :options="$permissionsCatalogApi"
                                option-label="name"
                                option-value="id"
                                placeholder="Busca y selecciona permisos API..."
                            />
                        </div>
                        <div x-show="guard === 'web'">
                            <x-ui.select-multiple
                                wire:model="permissionsWeb"
                                :options="$permissionsCatalogWeb"
                                option-label="name"
                                option-value="id"
                                placeholder="Busca y selecciona permisos WEB..."
                            />
                        </div>
                    </div>
                    @error('permissionsApi') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    @error('permissionsWeb') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button type="button" x-on:click="show = false" class="px-4 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-md transition-colors text-sm font-medium shadow-sm">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    <span wire:loading.remove wire:target="save">Guardar</span>
                    <span wire:loading wire:target="save">Cargando...</span>
                </button>
            </div>

        </div>
    </form>
</x-ui.modal>