<div class="min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white p-8 border border-gray-200 rounded-lg shadow-sm">
        <x-card title="Iniciar Sesión" shadow separator>
            <!-- Formulario para usar lógica de PHP -->
            <x-form wire:submit="authenticate" no-separator>
                <div>
                    <x-input 
                        label="Correo Electrónico o Usuario" 
                        wire:model="identifier" 
                        popover="Puedes Ingresar con tu correo electrónico o tu nombre de usuario" 
                    />
                </div>

                <div>
                    
                    <x-password 
                        label="Contraseña" 
                        hint="Tu contraseña debe tener al menos 8 caracteres" 
                        wire:model="password" 
                        clearable 
                    />
                </div>

                <x-button
                    class="btn-primary w-full"
                    label="Ingresar" 
                    type="submit" 
                    spiner="save"
                />

                <div wire:loading wire:target="authenticate">
                    <x-ui.loader-modal />
                </div>
            </x-form>
        </x-card>

        <x-card>
            <p class="text-sm text-gray-600">
                ¿No tienes cuenta?
                <!-- Enlace modo Livewire SPA -->
                <a href="/register" wire:navigate class="font-medium text-indigo-600 hover:text-indigo-500">
                    Regístrate aquí
                </a>
            </p>
        </x-card>

    </div>
</div>  