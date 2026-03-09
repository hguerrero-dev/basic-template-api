<?php

namespace App\Modules\Roles\Livewire;

use App\Modules\Roles\DTOs\CreateRoleDTO;
use App\Modules\Roles\Enums\RolePermission;
use App\Modules\Roles\Services\RoleService;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;

class RoleForm extends Component
{
    public ?int $roleId = null;
    public array $permissionsCatalogApi = [];
    public array $permissionsCatalogWeb = [];
    public string $guard = 'web';
    public array $permissionsApi = [];
    public array $permissionsWeb = [];
    public string $name = '';
    public string $description = '';

    public function mount(RoleService $roleService)
    {
        $this->loadPermissionsCatalogs($roleService);
    }

    public function updatedGuard($value)
    {
        if ($this->guard === 'api') {
            $this->permissionsWeb = [];
        } else {
            $this->permissionsApi = [];
        }
        $this->resetValidation();
    }

    public function loadPermissionsCatalogs(RoleService $roleService)
    {
        $groupedApi = $roleService->getAllPermissionsGrouped('api')->toArray();
        $groupedWeb = $roleService->getAllPermissionsGrouped('web')->toArray();

        $this->permissionsCatalogApi = [];
        foreach ($groupedApi as $group => $permissions) {
            foreach ($permissions as $permission) {
                $this->permissionsCatalogApi[] = [
                    'id' => $permission['id'],
                    'name' => $permission['name'] . ' (api)',
                ];
            }
        }

        $this->permissionsCatalogWeb = [];
        foreach ($groupedWeb as $group => $permissions) {
            foreach ($permissions as $permission) {
                $this->permissionsCatalogWeb[] = [
                    'id' => $permission['id'],
                    'name' => $permission['name'] . ' (web)',
                ];
            }
        }
    }

    #[On('create-role')]
    public function create(RoleService $roleService)
    {
        Gate::authorize(RolePermission::Create->value);

        $this->reset(['roleId', 'permissionsApi', 'permissionsWeb', 'name', 'description']);
        $this->resetValidation();
        $this->loadPermissionsCatalogs($roleService);

        $this->dispatch('open-modal', 'role-form-modal');
    }

    public function save(RoleService $roleService)
    {
        Gate::authorize(RolePermission::Create->value);

        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'guard' => 'required|in:api,web',
            'permissionsApi' => 'required_if:guard,api|array',
            'permissionsWeb' => 'required_if:guard,web|array',
        ]);

        // Selecciona el catálogo y los permisos según el guard
        $catalog = $this->guard === 'api' ? $this->permissionsCatalogApi : $this->permissionsCatalogWeb;
        $seleccionados = $this->guard === 'api' ? $this->permissionsApi : $this->permissionsWeb;

        // Solo IDs válidos para el guard seleccionado
        $idsValidos = collect($catalog)->pluck('id')->map(fn($id) => (string)$id)->toArray();
        $permisosFiltrados = array_values(array_filter($seleccionados, fn($id) => in_array((string)$id, $idsValidos)));

        $dto = new CreateRoleDTO(
            name: $this->name,
            guard_name: $this->guard,
            permissions: $permisosFiltrados
        );

        $roleService->create($dto);

        $this->dispatch('role-saved');
        $this->dispatch('close-modal', 'role-form-modal');
        $this->reset(['roleId', 'permissionsApi', 'permissionsWeb', 'name', 'description']);
    }

    public function render()
    {
        return view('roles::role-form');
    }
}
