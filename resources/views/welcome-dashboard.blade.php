<x-layouts.app>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 flex justify-between items-center">
            <h1 class="text-2xl font-bold">Bienvenido, {{ auth()->user()->name }} 👋</h1>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6"> 
        <x-stat
            title="Messages"
            value="44"
            icon="o-envelope"
            tooltip="Hello"
            color="text-primary" />

        <x-stat
            title="Sales"
            description="This month"
            value="22.124"
            icon="o-arrow-trending-up"
            tooltip-bottom="There" />

        <x-stat
            title="Lost"
            description="This month"
            value="34"
            icon="o-arrow-trending-down"
            tooltip-left="Ops!" />

        <x-stat
            title="Sales"
            description="This month"
            value="22.124"
            icon="o-arrow-trending-down"
            class="text-orange-500"
            color="text-pink-500"
            tooltip-right="Gosh!" />
    </div>
</x-layouts.app>
