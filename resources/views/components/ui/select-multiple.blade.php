@props([
    'options' => [],
    'placeholder' => 'Seleccionar opciones...',
])

@php
    // Normalizamos las opciones para que siempre sean un array de objetos con `value` y `label`
    $normalizedOptions = collect($options)->map(function($option, $key) {
        if (is_array($option)) {
            return [
                'value' => $option['value'] ?? $option['id'] ?? $option['name'] ?? $key, 
                'label' => $option['label'] ?? $option['name'] ?? $option['value'] ?? $key
            ];
        } elseif (is_object($option)) {
            return [
                'value' => $option->value ?? $option->id ?? $option->name ?? $key, 
                'label' => $option->label ?? $option->name ?? $option->value ?? $key
            ];
        }
        return ['value' => $option, 'label' => $option];
    })->values()->toArray();
@endphp

<div 
    x-data="selectMultiple(@entangle($attributes->wire('model')), {{ \Illuminate\Support\Js::from($normalizedOptions) }})"
    class="relative mt-1"
    @click.outside="open = false"
>
    <!-- Contenedor principal de los chips y el buscador -->
    <div 
        @click="open = true; $refs.searchInput.focus()" 
        class="min-h-10.5 px-3 py-2 flex flex-wrap gap-2 items-center border border-gray-300 rounded-md bg-white cursor-text focus-within:ring-1 focus-within:ring-indigo-500 focus-within:border-indigo-500 shadow-sm sm:text-sm"
    >
        <template x-for="item in selected" :key="item">
            <span class="inline-flex items-center px-2 py-1 rounded text-sm font-medium bg-indigo-100 text-indigo-800">
                <span x-text="getLabel(item)"></span>
                <button type="button" @click.stop="remove(item)" class="ml-1 inline-flex text-indigo-400 hover:text-indigo-600 focus:outline-none">
                    &times;
                </button>
            </span>
        </template>
        
        <input 
            x-ref="searchInput"
            type="text" 
            x-model="search" 
            @keydown.backspace="if(search === '' && selected.length > 0) { selected.pop() }"
            class="flex-1 min-w-30 bg-transparent border-none p-0 focus:ring-0 text-sm placeholder-gray-400" 
            :placeholder="selected.length === 0 ? '{{ $placeholder }}' : ''"
        />
    </div>

    <!-- Menú desplegable estilo Tailwind -->
    <div 
        x-show="open" 
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute z-10 w-full mt-1 bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm"
        style="display: none;"
    >
        <template x-for="option in filteredOptions" :key="option.value">
            <div 
                @click="toggle(option.value)"
                class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-gray-100"
                :class="{'bg-indigo-50': selected.includes(option.value)}"
            >
                <span 
                    class="block truncate" 
                    :class="{'font-semibold text-indigo-600': selected.includes(option.value), 'font-normal': !selected.includes(option.value)}" 
                    x-text="option.label"
                ></span>
                
                <!-- Icono de check para los items seleccionados -->
                <span x-show="selected.includes(option.value)" class="absolute inset-y-0 right-0 flex items-center pr-4 text-indigo-600">
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </span>
            </div>
        </template>
        
        <div x-show="filteredOptions.length === 0" class="py-2 px-3 text-sm text-gray-500" x-cloak>
            No se encontraron opciones.
        </div>
    </div>
</div>
