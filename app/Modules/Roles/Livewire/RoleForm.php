<?php

namespace App\Modules\Roles\Livewire;

use App\Modules\Roles\DTOs\CreateRoleDTO;
use App\Modules\Roles\DTOs\UpdateRoleDTO;
use App\Modules\Roles\Enums\RolePermission;
use App\Modules\Roles\Services\RoleService;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;
use Mary\Traits\Toast;

class RoleForm extends Component
{
    use Toast;

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

    #[On('edit-role')]
    public function edit(int $id, RoleService $roleService)
    {
        Gate::authorize(RolePermission::Edit->value);

        $this->reset(['permissionsApi', 'permissionsWeb', 'name', 'description']);
        $this->resetValidation();
        $role = $roleService->getByOne($id);

        $this->roleId = $id;
        $this->name = $role->name;
        $this->description = $role->description ?? '';
        $this->guard = $role->guard_name;
        $this->permissionsApi = $role->permissions
            ->where('guard_name', 'api')
            ->pluck('id')
            ->map(fn($id) => (string)$id)
            ->toArray();
        $this->permissionsWeb = $role->permissions
            ->where('guard_name', 'web')
            ->pluck('id')
            ->map(fn($id) => (string)$id)
            ->toArray();
        $this->loadPermissionsCatalogs($roleService);

        $this->dispatch('open-modal', 'role-form-modal');
    }

    private function getFilteredPermissions(): array
    {
        $catalog = $this->guard === 'api' ? $this->permissionsCatalogApi : $this->permissionsCatalogWeb;
        $seleccionados = $this->guard === 'api' ? $this->permissionsApi : $this->permissionsWeb;

        $idsValidos = collect($catalog)->pluck('id')->map(fn($id) => (string)$id)->toArray();
        return array_values(array_filter($seleccionados, fn($id) => in_array((string)$id, $idsValidos)));
    }

    public function save(RoleService $roleService)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'guard' => 'required|in:api,web',
            'permissionsApi' => 'required_if:guard,api|array',
            'permissionsWeb' => 'required_if:guard,web|array',
        ];

        $this->validate($rules);

        $permisosFiltrados = $this->getFilteredPermissions();

        if ($this->roleId) {
            Gate::authorize(RolePermission::Edit->value);

            $dto = new UpdateRoleDTO(
                id: $this->roleId,
                name: $this->name,
                description: $this->description,
                guard_name: $this->guard,
                permissions: $permisosFiltrados
            );
            $roleService->update($dto);
        } else {
            Gate::authorize(RolePermission::Create->value);

            $dto = new CreateRoleDTO(
                name: $this->name,
                description: $this->description,
                guard_name: $this->guard,
                permissions: $permisosFiltrados
            );
            $roleService->create($dto);
        }

        $this->dispatch('role-saved');
        $this->dispatch('close-modal', 'role-form-modal');

        $this->success('Rol: ' . $this->name . ' ' . ($this->roleId ? 'editado' : 'creado') . ' exitosamente.', css: 'alert-success');

        $this->reset(['roleId', 'permissionsApi', 'permissionsWeb', 'name', 'description']);
    }

    public function render()
    {
        return view('roles::role-form');
    }
}
