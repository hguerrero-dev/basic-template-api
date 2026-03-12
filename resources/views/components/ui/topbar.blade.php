<header class="h-16 flex items-center justify-between px-6 ">
    @php
        $breadcrumbs = [
            [
                'link' => route('dashboard'),
                'icon' => 's-home',
                'label' => 'Inicio',
            ],
        ];

        if (request()->routeIs('users.*')) {
            $breadcrumbs[] = [
                'label' => 'Usuarios',
                'icon' => 'o-user',
            ];
        }

        elseif (request()->routeIs('roles.*')) {
            $breadcrumbs[] = [
                'label' => 'Roles',
                'icon' => 'o-key',
            ];
        }

        elseif (request()->routeIs('audit.*')) {
            $breadcrumbs[] = [
                'label' => 'Auditoría',
                'icon' => 'o-clipboard-document-list',
            ];
        }
        
    @endphp
    
    <div class="flex items-center gap-4">
        <x-breadcrumbs
            :items="$breadcrumbs"
            separator="m-minus"
            separator-class="text-primary"
            class="bg-white p-3 rounded-box shadow-sm border border-base-200"
            icon-class="text-primary"
            link-item-class="text-sm font-bold"
        />
        {{-- Nombre de la sección actual --}}
        @php
            $section = $breadcrumbs[count($breadcrumbs)-1]['label'] ?? '';
        @endphp
        <span class="text-base-content/60 text-sm font-semibold">{{ $section }}</span>
    </div>

    {{-- Centro: Menú principal --}}
    <nav class="flex gap-2">
        <a href="{{ route('dashboard') }}" class="btn btn-ghost btn-sm rounded-full text-base-content/80 hover:bg-primary/10 transition">Inicio</a>
        <a href="{{ route('users.index') }}" class="btn btn-ghost btn-sm rounded-full text-base-content/80 hover:bg-primary/10 transition">Usuarios</a>
        <a href="{{ route('roles.index') }}" class="btn btn-ghost btn-sm rounded-full text-base-content/80 hover:bg-primary/10 transition">Roles</a>
        <a href="{{ route('audit.index') }}" class="btn btn-ghost btn-sm rounded-full text-base-content/80 hover:bg-primary/10 transition">Auditoría</a>
        {{-- Agrega/quita opciones según tu flujo --}}
    </nav>

    <div class="flex items-center gap-6">
        <div class="bg-white p-3 rounded-box shadow-sm border border-base-200 flex items-center gap-2">
    
            <div class="relative flex items-center mr-2">
                <livewire:notification />
            </div>

            @if(auth()->user()->avatar)
                <x-avatar :image="auth()->user()->avatar" :title="auth()->user()->username" />
            @else
                <x-avatar :title="auth()->user()->username">
                    {{ strtoupper(substr(trim(auth()->user()->name ?? auth()->user()->username ?? 'A'), 0, 1)) }}
                </x-avatar>
            @endif
        </div>
    </div>
</header>