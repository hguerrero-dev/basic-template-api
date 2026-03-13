<x-ui.modal name="user-form-modal" title="{{ $userId ? 'Editar Usuario' : 'Crear Nuevo Usuario' }}">
    <form wire:submit.prevent="save">
        <div class="space-y-4">
            
            <!-- Campo Nombre -->
            <div>
                <x-input 
                    wire:model="name" 
                    placeholder="Nombre completo" 
                    icon="o-user"
                    hint="Escribe el nombre completo del usuario"
                />
            </div>

            <!-- Campo Email -->
            <div>
                <div class="flex gap-2 relative mt-1">
                    <div class="flex w-full items-center gap-2">
                        <x-input
                            wire:model="email" 
                            placeholder="Correo electrónico" 
                            icon="o-envelope" 
                            class="flex-1"
                        />
                        @if(!$userId || ($userId && !$email))
                            <button 
                                type="button"
                                wire:click="generateEmail" 
                                class="flex items-center gap-1 px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md text-sm font-medium transition-colors shadow-sm whitespace-nowrap"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                Generar email
                            </button>
                        @endif
                    </div>
                </div>
            </div>

             <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <!-- Select de Rol -->
                <div>
                    <x-choices
                        label="Selecciona roles"
                        wire:model="roles"
                        :options="$this->catalogs['roles']"
                        placeholder="Busca y selecciona roles..."
                        multiple
                        searchable
                        no-result-text="¡Ops! Nada aquí ..."
                        no-progress
                        data-choices
                    />
                </div>

                <!-- Select de Estado -->
                <div>
                    <x-choices-offline
                        label="Estado de la cuenta"
                        wire:model="status"
                        :options="$this->catalogs['estados']"
                        placeholder="Search ..."
                        single
                        clearable
                        searchable 
                    />
                </div>
            </div>

            <!-- Contenedor general de Password -->
            <div class="space-y-4 pt-2 border-t border-gray-100">
                
                <!-- Encabezado y Botón Generar -->
                <div class="flex justify-between items-center {{ $userId ? 'opacity-50 pointer-events-none' : '' }}">
                    <label class="block text-sm font-semibold text-gray-800">
                        Credenciales de Acceso
                    </label>
                    <button 
                        type="button"
                        wire:click="generatePassword" 
                        class="flex items-center gap-1 px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md text-xs font-medium transition-colors"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        Generar password
                    </button>
                </div>    

                <!-- Inputs de Password -->
                <div x-data="{ show: false }">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <!-- Campo Contraseña -->
                        <div>
                            <input :type="show ? 'text' : 'password'" class="input w-full" placeholder="Contraseña" wire:model="password" />
                        </div>
                        <!-- Campo Confirmación -->
                        <div>
                            <input :type="show ? 'text' : 'password'" class="input w-full" placeholder="Confirmar Contraseña" wire:model="password_confirmation" />
                        </div>
                    </div>
                    <div class="mt-2 flex items-center gap-2">
                        <input type="checkbox" id="show-password" x-model="show" class="form-checkbox" />
                        <label for="show-password" class="text-sm text-gray-600 cursor-pointer">Mostrar contraseña</label>
                    </div>
                </div>
                
            </div>

            <!-- Modal de confirmación para Super_admin -->
            <div 
                x-data="{ showConfirm: @entangle('pendingSuperAdmin') }"
                x-show="showConfirm"
                style="display: none;"
                class="fixed inset-0 z-100 overflow-y-auto"
            >
                <div x-show="showConfirm" class="fixed inset-0 bg-gray-900 opacity-50"></div>
                <div x-show="showConfirm" x-transition class="flex min-h-full items-center justify-center p-4">
                    <div class="relative w-full max-w-md bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Confirmar selección</h3>
                        <p class="text-sm text-gray-500 mb-6">
                            ¿Estás seguro que deseas asignar el rol super administrador a este usuario?
                        </p>
                        <div class="flex justify-end gap-3">
                            <button 
                                @click="$wire.pendingSuperAdmin = false" 
                                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md text-sm font-medium transition-colors">
                                Cancelar
                            </button>
                            <button 
                                @click="$wire.confirmSuperAdmin(); showConfirm = false" 
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md text-sm font-medium transition-colors shadow-sm">
                                Confirmar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="flex justify-end gap-3 mt-6">
          <x-button 
                label="Cancelar" 
                x-on:click="show = false" 
                type="button" 
                class="btn btn-secondary" 
                class="px-4 py-2 bg-gray-200 text-gray-700 hover:bg-gray-300 rounded-md transition-colors text-sm font-medium shadow-sm"
            />
            <x-button 
                label="Guardar" 
                type="submit" 
                class="btn btn-primary" 
                spinner="save" 
            />
        </div>
    </form>
</x-ui.modal>