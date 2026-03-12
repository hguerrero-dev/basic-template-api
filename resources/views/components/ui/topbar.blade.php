<header class="h-16 flex items-center justify-between px-6 ">
   <div>
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

        <x-breadcrumbs
            :items="$breadcrumbs"
            separator="m-minus"
            separator-class="text-primary"
            class="bg-white p-3 rounded-box shadow-sm border border-base-200"
            icon-class="text-primary"
            link-item-class="text-sm font-bold"
        />


    </div>

    <div class="flex items-center gap-6">
        <div class="flex items-center gap-2">
    
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