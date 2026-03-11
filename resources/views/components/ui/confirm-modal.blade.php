<div>
    <x-modal wire:model="show" :title="$title" class="backdrop-blur">
        
        <div class="py-2 text-gray-600">
            {{ $message }}
        </div>

        <x-slot:actions>
            
        <x-button label="Cancelar" @click="$wire.show = false" class="btn-ghost" />
            
        <x-button 
            label="Confirmar" 
            class="btn-error text-white" 
            wire:click="confirm" 
            spinner="confirm" 
        />
        </x-slot:actions>
    </x-modal>
</div>