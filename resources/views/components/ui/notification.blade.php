<div class="relative flex items-center mr-2"> 
    <!-- Botón de campana que llama directamente al método Livewire -->
    <button wire:click="openNotification" class="p-2 rounded-full hover:bg-gray-100 text-gray-500 hover:text-gray-700 transition-colors focus:outline-none">
        
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>
        
        <!-- Ping rojo estático por ahora -->
        <span class="absolute top-1.5 right-1.5 block w-2.5 h-2.5 rounded-full bg-red-500 ring-2 ring-white">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
        </span>
    </button>

    <!-- Usamos el componente de Modal global y alimentamos el cuerpo con los datos de Livewire -->
    <x-ui.modal name="notification-modal" title="Notificación">
        <div class="space-y-4">
            <p class="text-gray-900">{{ $message }}</p>
            <div class="flex justify-end mt-6">
                <!-- Se deja un mínimo de alpine (x-on:click) porque el modal global x-ui.modal depende del estado 'show' para cerrarse, lo cual está bien y ahorra peticiones al servidor -->
                <button type="button" x-on:click="show = false" class="px-4 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-md transition-colors text-sm font-medium shadow-sm">
                    Cerrar
                </button>
            </div>
        </div>
    </x-ui.modal>
</div>