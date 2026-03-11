<div class="min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white p-8 rounded-xl shadow-lg border border-gray-100">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Crear Cuenta</h2>
        
        <form wire:submit="registerUser" class="space-y-5" x-data="{ showPassword: false }">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nombre</label>
                <input type="text" wire:model="name" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500 transition-colors">
                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                <input type="email" wire:model="email" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500 transition-colors">
                @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                <input x-bind:type="showPassword ? 'text' : 'password'" id="password" wire:model="password" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border">
                @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Confirmar Contraseña</label>
                <input x-bind:type="showPassword ? 'text' : 'password'" wire:model="password_confirmation" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500 transition-colors">
            </div>

            <!-- Mostrar contraseña -->
            <div class="flex items-center mt-3">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" x-model="showPassword" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-4 w-4">
                    <span class="ml-2 text-sm text-gray-600">Mostrar contraseña</span>
                </label>
            </div>

            <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                Registrarme
            </button>
            
        </form>

        <div class="mt-6 text-center">
            <a href="/login" wire:navigate class="text-sm font-medium text-indigo-600 hover:text-indigo-500 transition-colors">
                ¿Ya tienes una cuenta? Inicia sesión
            </a>
        </div>
    </div>
</div>