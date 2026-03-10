<!-- Envolvemos con tu Modal UI y lo vinculamos a la propiedad de Livewire "show" -->
<div>
    <x-ui.modal name="audit-details-modal" title="Detalles de auditoría">
        @if($selectedAudit)
            <div class="space-y-4">
                <div>
                    <h3 class="text-sm font-medium text-gray-700">Usuario:</h3>
                    <p class="text-gray-900">{{ $selectedAudit->user?->name ?? 'Desconocido' }}</p>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-700">Acción:</h3>
                    <p class="text-gray-900">{{ $selectedAudit->event }}</p>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-700">Fecha y hora:</h3>
                    <p class="text-gray-900">{{ $selectedAudit->created_at->format('d/m/Y H:i:s') }}</p>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-700">Modelo auditado:</h3>
                    <p class="text-gray-900">{{ class_basename($selectedAudit->auditable_type) }}</p>
                </div>

                <div> 
                    <h3 class="text-sm font-medium text-gray-700">IP :</h3>
                    <p class="text-gray-900">{{ $selectedAudit->ip_address }}</p>
                </div>

                <div> 
                    <h3 class="text-sm font-medium text-gray-700">URL :</h3>
                    <p class="text-gray-900">{{ $selectedAudit->url }}</p>
                </div>

                @if($selectedAudit->old_values || $selectedAudit->new_values)
                    <div class="mt-4 space-y-4 bg-gray-50 rounded">
                        <h3 class="text-sm font-medium text-gray-700">Modificaciones:</h3>
                        @if($selectedAudit->old_values)
                            <div class="mb-2">
                                <h4 class="text-xs font-semibold text-red-600">Valores anteriores:</h4>
                                <pre class="bg-red-50 p-2 rounded text-xs text-red-800 overflow-x-auto">{{ json_encode($selectedAudit->old_values, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        @endif

                        @if($selectedAudit->new_values)
                            <div>
                                <h4 class="text-xs font-semibold text-green-600">Valores nuevos:</h4>
                                <pre class="bg-green-50 p-2 rounded text-xs text-green-800 overflow-x-auto">{{ json_encode($selectedAudit->new_values, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        @endif
                    </div>
                @endif
                
                <div class="flex justify-end mt-6">
                    <!-- Botón para cerrar modificando el estado "show" de Alpine (dentro de x-ui.modal) -->
                    <button type="button" x-on:click="show = false" class="px-4 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-md transition-colors text-sm font-medium shadow-sm">
                        Cerrar
                    </button>
                </div>
            </div>
        @else
            <!-- Mientras carga -->
            <div class="flex justify-center p-4">
                <svg class="animate-spin h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.046 1.875 5.693 4.579 6.91a8.034 8.034 0 01-3.96-1.619z"></path>
                </svg>
            </div>
        @endif
    </x-ui.modal>
</div>