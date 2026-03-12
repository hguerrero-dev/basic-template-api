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
                <x-textarea 
                    label="Descripción del rol" 
                    wire:model="description" 
                    placeholder="Escribe brevemente el propósito de este rol..."
                    hint="Max 1000 chars" 
                    rows="5" 
                    inline
                />
            </div>

             <div class="space-y-4">
                <!-- Select de Permisos -->
                <div class="mb-2">
                    <label class="block text-sm font-medium text-gray-700">Guard del Rol</label>
                </div>

                <div x-data="{ localGuard: @entangle('guard') }" class="block text-sm font-medium text-gray-700">
                    <select wire:model="guard" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border px-3 py-2">
                        <option value="api">
                            API
                        </option>
                        <option value="web" hover:bg-blue-900>
                            WEB
                        </option>
                    </select>

                     <!-- Card de información para API -->
                    <div x-show="localGuard === 'api'" x-transition class="mt-3 p-3 bg-indigo-50 border border-indigo-200 rounded-md shadow-sm flex items-start">
                        <svg class="h-5 w-5 text-indigo-400 mr-2 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-xs text-indigo-700 font-normal mt-0.5">
                            <strong class="font-semibold">Contexto API:</strong> Este acceso se utiliza para rutas autenticadas mediante tokens (como Laravel Sanctum). Ideal para aplicaciones frontend (React/Vue) o móviles.
                        </p>
                    </div>

                    <!-- Card de información para WEB -->
                    <div x-show="localGuard === 'web'" x-transition class="mt-3 p-3 bg-emerald-50 border border-emerald-200 rounded-md shadow-sm flex items-start" style="display: none;">
                        <svg class="h-5 w-5 text-emerald-400 mr-2 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-xs text-emerald-700 font-normal mt-0.5">
                            <strong class="font-semibold">Contexto WEB:</strong> Este acceso se utiliza para la navegación tradicional basada en cookies de sesión. Ideal para vistas renderizadas por servidor como esta.
                        </p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Permisos del Rol</label>
                    <div x-data="{ guard: @entangle('guard') }">
                        <div x-show="guard === 'api'">
                            <x-choices
                                wire:model="permissionsApi"
                                :options="$permissionsCatalogApi"
                                placeholder="Busca y selecciona permisos API..."
                                multiple
                                searchable
                                no-result-text="¡Ops! Nada aquí ..."
                                no-progress
                            />
                        </div>
                        <div x-show="guard === 'web'">
                            <x-choices
                                wire:model="permissionsWeb"
                                :options="$permissionsCatalogWeb"
                                placeholder="Busca y selecciona permisos WEB..."
                                multiple
                                searchable
                                no-result-text="¡Ops! Nada aquí ..."
                                no-progress
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