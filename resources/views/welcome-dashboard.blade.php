<x-layouts.app>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 flex justify-between items-center">
            <h1 class="text-2xl font-bold">Bienvenido, {{ auth()->user()->name }} 👋</h1>
        </div>
    </div>
</x-layouts.app>
