<div>
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
        <h2 class="text-2xl font-bold text-gray-800">Auditoría de Actividades</h2>
        
        <div class="flex gap-2 w-full sm:w-auto">
            <input 
                type="text" 
                wire:model.live.debounce.100ms="search" 
                placeholder="Buscar actividad..." 
                class="w-full sm:w-80 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 px-4 py-2 border"
            >
        </div>
    </div>

    <div wire:target="search, nextPage, previousPage, gotoPage" wire:loading.class="opacity-50 pointer-events-none transition-opacity duration-200" class="relative w-full overflow-x-auto">
        <div wire:target="search, nextPage, previousPage, gotoPage" wire:loading class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-10">
                <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.046 1.875 5.693 4.579 6.91a8.034 8.034 0 01-3.96-1.619z"></path>
                </svg>
            </div>

            <x-ui.table>
                <x-slot:headers>
                    <x-ui.th>Usuario</x-ui.th>
                    <x-ui.th>Acción</x-ui.th>
                    <x-ui.th>Fecha y hora</x-ui.th>
                    <x-ui.th>Modelo auditado</x-ui.th>
                </x-slot:headers>

                @forelse($audits as $audit)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <x-ui.td class="font-medium text-gray-900">
                            {{ $audit->user?->name ?? 'Desconocido' }}
                        </x-ui.td>

                        <x-ui.td>
                            {{ $audit->event }}
                        </x-ui.td>

                        <x-ui.td>
                            {{ $audit->created_at->format('d/m/Y H:i:s') }}
                        </x-ui.td>

                        <x-ui.td>
                            @if($audit->auditable)
                                {{ class_basename($audit->auditable_type) }}: {{ $audit->auditable->name ?? $audit->auditable->title ?? $audit->auditable_id }}
                            @else
                                {{ class_basename($audit->auditable_type) }}: {{ $audit->auditable_id }} (Eliminado)
                            @endif
                        </x-ui.td>
                        
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-gray-500">
                            No se encontraron actividades.
                        </td>
                    </tr>
                @endforelse

                {{-- Paginacion --}}
                @if($audits->hasPages())
                    <x-slot:pagination>
                        {{ $audits->links() }}
                    </x-slot:pagination>   
                @endif
            </x-ui.table>
    </div>
</div>