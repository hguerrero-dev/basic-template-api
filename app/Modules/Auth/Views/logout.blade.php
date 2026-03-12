<div>
    <button
        wire:click="confirmLogout"
        class="flex items-center gap-2 text-sm text-gray-600 hover:text-red-600 font-medium transition-colors cursor-pointer"
        spinner
    >
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
        </svg>
        Cerrar Sesión
    </button>

    <!-- Loader mientras se ejecuta confirmLogout (abre el modal) -->
    <div wire:loading wire:target="confirmLogout">
        <x-ui.loader-modal />
    </div>

</div>