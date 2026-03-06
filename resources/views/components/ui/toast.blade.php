<div
       x-data="{
        show: false,
        message: '',
        type: 'success',
        timeout: null,
        init() {
            // 1. Escuchar eventos desde Livewire
            window.addEventListener('notify', event => {
                let data = event.detail[0] || event.detail;
                
                this.message = data.message;
                this.type = data.type || 'success';
                this.show = true;

                clearTimeout(this.timeout);
                this.timeout = setTimeout(() => { this.show = false }, 3000);
            });

            // 2. Escuchar mensajes de sesión (Flash) de controladores normales
            @if (session()->has('success'))
                this.type = 'success';
                this.message = '{{ session('success') }}';
                this.show = true;
                this.timeout = setTimeout(() => { this.show = false }, 3000);
            @endif

            @if (session()->has('error'))
                this.type = 'error';
                this.message = '{{ session('error') }}';
                this.show = true;
                this.timeout = setTimeout(() => { this.show = false }, 5000); // 5 seg para errores
            @endif
        }
    }"
    x-show="show"
    style="display: none;"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform translate-x-8"
    x-transition:enter-end="opacity-100 transform translate-x-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0 transform translate-x-8 opacity-0"
    class="fixed bottom-5 right-5 z-50 flex items-center w-full max-w-xs p-4 space-x-3 text-sm rounded-lg shadow-lg border"
    :class="{
        'text-green-800 bg-green-50 border-green-200': type === 'success',
        'text-red-800 bg-red-50 border-red-200': type === 'error',
        'text-blue-800 bg-blue-50 border-blue-200': type === 'info',
        'text-yellow-800 bg-yellow-50 border-yellow-200': type === 'warning'
    }"
    role="alert"
>
    <!-- Icono (Opcional, cambia según el tipo) -->
    <div class="inline-flex items-center justify-center shrink-0 w-8 h-8 rounded-lg"
        :class="{
            'bg-green-100 text-green-500': type === 'success',
            'bg-red-100 text-red-500': type === 'error',
            'bg-blue-100 text-blue-500': type === 'info',
            'bg-yellow-100 text-yellow-500': type === 'warning'
        }">
        <svg x-show="type === 'success'" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
        <svg x-show="type === 'error'" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
        <svg x-show="type === 'info'" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
        <svg x-show="type === 'warning'" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
    </div>

    <!-- Mensaje -->
    <div class="ml-3 text-sm font-normal" x-text="message"></div>
    
    <!-- Botón Cerrar -->
    <button type="button" @click="show = false" class="ml-auto -mx-1.5 -my-1.5 rounded-lg focus:ring-2 focus:ring-gray-400 p-1.5 inline-flex h-8 w-8 hover:bg-gray-200"
        :class="{ 'text-green-500 hover:bg-green-100': type === 'success', 'text-red-500 hover:bg-red-100': type === 'error', 'text-blue-500 hover:bg-blue-100': type === 'info', 'text-yellow-500 hover:bg-yellow-100': type === 'warning' }">
        <span class="sr-only">Cerrar</span>
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
    </button>
</div>