<div {{ $attributes->merge(['class' => 'flex flex-col bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-200']) }}>
    
    <!-- Zona Visual / Encabezado superior (Opcional) -->
    @if(isset($visual))
        <div class="bg-gray-50 flex items-center justify-center p-6 border-b border-gray-100 relative min-h-35">
            {{ $visual }}

            <!-- Etiqueta o Badge (Opcional) -->
            @if(isset($badge))
                <div class="absolute bottom-3 right-3">
                    {{ $badge }}
                </div>
            @endif
        </div>
    @endif

    <div class="p-5 flex-1 flex flex-col">
        <!-- Información del Autor o Meta (Opcional) -->
        @if(isset($meta))
            <div class="flex items-center space-x-2 text-xs text-gray-500 mb-3">
                {{ $meta }}
            </div>
        @endif
        
        <!-- Título principal -->
        @if(isset($title))
            <h3 class="text-lg font-semibold text-gray-900 mb-2 leading-tight">
                {{ $title }}
            </h3>
        @endif

        <!-- Contenido / Descripción -->
        <div class="text-sm text-gray-600 flex-1 mb-4">
            <!-- line-clamp-3 recorta el texto a 3 líneas y agrega puntos suspensivos si es muy largo -->
            <p class="line-clamp-3">
                {{ $slot }}
            </p>
        </div>

        <!-- Pie de la tarjeta (Estadísticas, acciones, fechas) -->
        @if(isset($footer))
            <div class="flex items-center justify-between text-xs text-gray-500 pt-4 border-t border-gray-100">
                {{ $footer }}
            </div>
        @endif
    </div>
</div>