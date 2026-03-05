<div class="min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white p-8 border border-gray-200 rounded-lg shadow-sm">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Iniciar Sesión</h2>

    <!-- Formulario para usar lógica de PHP -->
    <form wire:submit="authenticate" class="space-y-4">
        <div>
            <label for="identifier" class="block text-sm font-medium text-gray-700">Correo Electrónico o Usuario</label>
            <input type="text" id="identifier" wire:model="identifier" 
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border" >
            @error('identifier') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
            <input type="password" id="password" wire:model="password" 
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border">
            @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <button type="submit" 
            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
            Ingresar
        </button>
    </form>

        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                ¿No tienes cuenta?
                <!-- Enlace modo Livewire SPA -->
                <a href="/register" wire:navigate class="font-medium text-indigo-600 hover:text-indigo-500">
                    Regístrate aquí
                </a>
            </p>
        </div>
    </div>
</div>  