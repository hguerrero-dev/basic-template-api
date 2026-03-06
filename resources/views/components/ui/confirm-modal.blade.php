<div 
    x-data="{ show: false, title: '', message: '', actionEvent: '', params: null }"
    x-on:open-confirm.window="
        let data = Array.isArray($event.detail) ? $event.detail[0] : $event.detail;
        title = data.title;
        message = data.message;
        actionEvent = data.event;
        params = data.params;
        show = true;
    "
    x-show="show"
    style="display: none;"
    class="fixed inset-0 z-100 overflow-y-auto"
>
    <!-- Overlay oscuro -->
    <div x-show="show" class="fixed inset-0 bg-gray-900 opacity-50" @click="show = false"></div>

    <!-- Contenedor centrado -->
    <div x-show="show" x-transition class="flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-md bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-2" x-text="title"></h3>
            <p class="text-sm text-gray-500 mb-6" x-text="message"></p>
            
            <div class="flex justify-end gap-3">
                <button @click="show = false" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md text-sm font-medium transition-colors">
                    Cancelar
                </button>
                <!-- Al confirmar, dispara el evento solicitado hacia Livewire y cierra el modal -->
                <button @click="Livewire.dispatch(actionEvent, params !== null ? params : {}); show = false" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md text-sm font-medium transition-colors shadow-sm">
                    Confirmar
                </button>
            </div>
        </div>
    </div>
</div>