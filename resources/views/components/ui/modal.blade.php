@props(['name', 'title' => ''])

<div 
    x-data="{ show: false }"
    x-show="show"
    x-on:open-modal.window="if ($event.detail === '{{ $name }}' || (Array.isArray($event.detail) && $event.detail[0] === '{{ $name }}') || ($event.detail && $event.detail.name === '{{ $name }}')) show = true"
    x-on:close-modal.window="if ($event.detail === '{{ $name }}' || (Array.isArray($event.detail) && $event.detail[0] === '{{ $name }}') || ($event.detail && $event.detail.name === '{{ $name }}')) show = false"
    @keydown.escape.window="show = false"
    style="display: none;"
    class="fixed inset-0 z-50 overflow-y-auto"
>
    <!-- Fondo oscuro (Overlay) -->
    <div 
        x-show="show" 
        x-transition.opacity
        class="fixed inset-0 bg-gray-900 opacity-50" 
        @click="show = false"
    ></div>

    <!-- Contenido del Modal -->
    <div 
        x-show="show" 
        x-transition
        class="flex min-h-full items-center justify-center p-4"
    >
        <div class="relative w-full max-w-2xl bg-white rounded-xl shadow-lg p-6">
            @if($title)
                <div class="flex justify-between items-center mb-4 pb-3 border-b border-gray-100">
                    <h3 class="text-xl font-bold text-gray-800">{{ $title }}</h3>
                    <button @click="show = false" class="text-gray-400 hover:text-red-500 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            @endif
            
            <!-- Aquí irá el formulario dinámico -->
            <div>
                {{ $slot }}
            </div>
        </div>
    </div>
</div>