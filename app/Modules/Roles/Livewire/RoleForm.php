<?php

namespace App\Modules\Roles\Livewire;

use App\Modules\Roles\Enums\RolePermission;
use App\Modules\Roles\Services\RoleService;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;

class RoleForm extends Component
{
    public ?int $roleId = null;
    public array $permissionsCatalog = [];
    public string $guard = 'api';

    public function mount(RoleService $roleService)
    {
        $this->loadPermissionsCatalog($roleService);
    }

    public function updatedGuard($value)
    {
        $this->loadPermissionsCatalog(app(\App\Modules\Roles\Services\RoleService::class));
    }

    public function loadPermissionsCatalog(RoleService $roleService)
    {
        $grouped = $roleService->getAllPermissionsGrouped($this->guard)->toArray();
        $this->permissionsCatalog = [];
        foreach ($grouped as $group => $permissions) {
            foreach ($permissions as $permission) {
                $this->permissionsCatalog[] = [
                    'id' => $permission['id'],
                    'name' => $permission['name'] . ' (' . $permission['guard_name'] . ')',
                ];
            }
        }
    }

    #[On('create-role')]
    public function create(RoleService $roleService)
    {
        Gate::authorize(RolePermission::Create->value);

        $this->reset();
        $this->resetValidation();
        $this->loadPermissionsCatalog($roleService);

        $this->dispatch('open-modal', 'role-form-modal');
    }

    public function render()
    {
        return view('roles::role-form');
    }
}
