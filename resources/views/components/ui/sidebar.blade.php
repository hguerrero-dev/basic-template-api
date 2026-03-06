<aside class="w-full sm:w-64 bg-slate-800 text-white flex flex-col min-h-screen">
    <div class="h-16 flex items-center justify-center border-b border-slate-700">
        <h1 class="text-xl font-bold font-mono tracking-wider">{{ env('APP_NAME', 'API ADMIN') }}</h1>
    </div>

    <nav class="flex-1 px-4 py-6 space-y-2">
        <!-- Dashboard general -->
        <a href="/" class="flex items-center gap-3 px-3 py-2 rounded transition-colors {{ request()->is('dashboard') ? 'bg-slate-700 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-700' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            Dashboard
        </a>

        <!-- Módulo de Usuarios -->
        <div class="pt-4 pb-1">
            <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Administración</p>
        </div>
        @can(\App\Modules\Users\Enums\UserPermission::View->value)
        <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-3 py-2 rounded text-slate-300 hover:text-white hover:bg-slate-700 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            Usuarios
        </a>
        @endcan

        <!-- Módulo de Roles -->
        @can(\App\Modules\Roles\Enums\RolePermission::View->value)
        <a href="{{ route('roles.index') }}" class="flex items-center gap-3 px-3 py-2 rounded text-slate-300 hover:text-white hover:bg-slate-700 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            Roles y Permisos
        </a>
        @endcan

        <!-- Auditoría -->
        @can(\App\Modules\Audit\Enums\AuditPermission::View->value)
        <a href="{{ route('audit.index') }}" class="flex items-center gap-3 px-3 py-2 rounded text-slate-300 hover:text-white hover:bg-slate-700 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
            Auditoría
        </a>
        @endcan
    </nav>
</aside>