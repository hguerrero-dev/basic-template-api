<x-ui.modal name="user-form-modal" title="{{ $userId ? 'Editar Usuario' : 'Crear Nuevo Usuario' }}">
    <form wire:submit.prevent="save">
        <div class="space-y-4">
            
            <!-- Campo Nombre -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Nombre completo</label>
                <input type="text" wire:model="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border px-3 py-2">
                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Campo Email -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Correo electrónico</label>
                <div class="flex gap-2 relative mt-1">
                    <input type="email" wire:model="email" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border px-3 py-2">
                    
                    @if(!$userId)
                        <button 
                            type="button"
                            wire:click="generateEmail" 
                            class="flex items-center gap-1 px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md text-sm font-medium transition-colors whitespace-nowrap"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            Generar email
                        </button>
                    @endif
                </div>
                @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
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
            <div x-data="{ showPassword: false }" class="space-y-4 pt-2 border-t border-gray-100">
                
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
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 relative">
                    <!-- Campo Contraseña -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Contraseña 
                            @if($userId) <span class="text-gray-400 font-normal">(Vacío para omitir)</span> @endif
                        </label>
                        <input x-bind:type="showPassword ? 'text' : 'password'" wire:model.live="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border px-3 py-2">
                        @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                    <!-- Campo Confirmación -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Confirmar Contraseña 
                        </label>
                        <input x-bind:type="showPassword ? 'text' : 'password'" wire:model.live="password_confirmation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border px-3 py-2">
                        @error('password_confirmation') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    
                </div>
                
                <!-- Mostrar contraseña generada -->
                <div class="flex items-center justify-end mt-2">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" x-model="showPassword" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 h-4 w-4">
                        <span class="ml-2 text-sm text-gray-600">Mostrar contraseña</span>
                    </label>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('select[data-choices]').forEach(function(select) {
            if (!select.dataset.choicesInitialized) {
                new window.Choices(select, { removeItemButton: true, searchEnabled: true });
                select.dataset.choicesInitialized = true;
            }
        });
    });
</script>
@endpush