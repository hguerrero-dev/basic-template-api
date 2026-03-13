<div class="relative flex items-center mr-2">

    @php
        $array = range(1, 50);
    @endphp
    
    <x-dropdown>
        <x-slot:trigger>
            <div class="relative">
                <x-heroicon-o-bell class="w-6 h-6 text-gray-500 hover:text-primary cursor-pointer" />
                <!-- Indicador rojo (Ping) -->
                <span class="absolute top-0 right-0 translate-x-1/3 -translate-y-1/3 block w-1.5 h-1.5 rounded-full bg-red-500 ring-2 ring-white">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                </span>
            </div>
        </x-slot:trigger>
        
        <div class="max-h-80 min-w-65 max-w-xs overflow-y-auto">
            @foreach ($array as $item)
                <x-menu-item>
                    <span class="block truncate">Notificación {{ $item }} con texto largo de ejemplo para probar el corte</span>
                </x-menu-item>
            @endforeach
        </div>
    </x-dropdown>

</div>