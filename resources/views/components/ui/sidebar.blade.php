

<aside class="h-screen w-64 bg-base-200 border-r border-base-300 rounded-r-2xl shadow-lg flex flex-col py-6 px-3">
    <!-- Logo o título -->
    <div class="flex items-center gap-2 mb-6 px-2">
        <x-heroicon-o-cube class="w-7 h-7 text-primary" />
        <span class="font-bold text-lg text-primary tracking-wide">{{ config('app.name') }}</span>
    </div>

    <!-- Usuario -->
    <div class="flex items-center gap-3 mb-8 px-2">
        <div>
            <div class="font-semibold text-base-content">{{ Auth::user()?->name ?? 'Usuario' }}</div>
            <div class="text-xs text-base-content/60">{{ Auth::user()?->email ?? '' }}</div>
        </div>
    </div>

    <!-- Menú principal -->
    <nav class="flex-1 flex flex-col gap-6">
        <div>
            <div class="uppercase text-xs text-base-content/50 font-bold px-2 mb-2 tracking-widest">Principal</div>
            <x-menu class="bg-transparent border-0 shadow-none">
                <x-menu-item 
                    title="Dashboard" 
                    icon="o-home" 
                    link="{{ route('dashboard') }}" 
                    wire:navigate
                    class="text-base-content/90 hover:bg-primary/10 rounded-lg transition-all"
                    icon-classes="text-primary"
                    :active="request()->routeIs('dashboard')"
                />
            </x-menu>
        </div>

        <div>
            <div class="uppercase text-xs text-base-content/50 font-bold px-2 mb-2 tracking-widest">Gestión</div>
            <x-menu class="bg-transparent border-0 shadow-none">
                @can(\App\Modules\Users\Enums\UserPermission::View->value)
                    <x-menu-item 
                        title="Usuarios" 
                        icon="o-user" 
                        link="{{ route('users.index') }}" 
                        wire:navigate
                        class="text-base-content/90 hover:bg-primary/10 rounded-lg transition-all"
                        :active="request()->routeIs('users.*')"
                    />
                @endcan

                @can(\App\Modules\Roles\Enums\RolePermission::View->value)
                    <x-menu-item 
                        title="Roles y Permisos" 
                        icon="o-key" 
                        link="{{ route('roles.index') }}" 
                        wire:navigate
                        class="text-base-content/90 hover:bg-primary/10 rounded-lg transition-all"
                        :active="request()->routeIs('roles.*')"
                    />
                @endcan

                @can(\App\Modules\Audit\Enums\AuditPermission::View->value)
                    <x-menu-item 
                        title="Auditoría" 
                        icon="o-clipboard-document-list" 
                        link="{{ route('audit.index') }}" 
                        wire:navigate
                        class="text-base-content/90 hover:bg-primary/10 rounded-lg transition-all"
                        :active="request()->routeIs('audit.*')"
                    />
                @endcan

                <x-menu-item 
                    title="Catálogos" 
                    icon="o-archive-box"
                    link="#" 
                    wire:navigate
                    class="text-base-content/90 hover:bg-primary/10 rounded-lg transition-all"
                />
            </x-menu>
        </div>


    </nav>

    <!-- Settings separado abajo -->
    <div class="mt-6 mb-2">
        <x-menu class="border border-base-content/10">
            <x-menu-sub title="Settings" icon="o-cog-6-tooth" icon-classes="text-primary">
                <x-menu-item title="Profile" icon="o-user" />
                {{-- <x-menu-item title="Archives" icon="o-archive-box" /> --}}
                <x-menu-separator />
                <div class="px-2 py-1">
                    <livewire:auth.logout />
                </div>
            </x-menu-sub>
        </x-menu>
    </div>

    <!-- Footer -->
    <div class="mt-4 text-xs text-base-content/60 px-2">
        &copy; {{ date('Y') }} {{ config('app.name') }}
    </div>
</aside>