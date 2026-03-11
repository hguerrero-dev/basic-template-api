<?php

namespace App\Modules\Roles\Livewire;

use App\Modules\Roles\Services\RoleService;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Exception;
use Illuminate\Support\Facades\Gate;
use App\Modules\Roles\Enums\RolePermission;
use Mary\Traits\Toast;

class RoleList extends Component
{
    use WithPagination, Toast;

    public string $guard = 'api';

    protected $paginationTheme = 'tailwind';

    public $search = '';

    public function openRoleForm()
    {
        $this->dispatch('create-role');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    #[On('role-saved')]
    public function refreshTable() {}

    public function confirmDelete(int $id, string $name)
    {
        Gate::authorize(RolePermission::Delete->value);

        $this->dispatch('open-confirm-modal', [
            'title' => 'Eliminar Rol',
            'message' => '¿Deseas eliminar este rol (' . $name . ') de forma permanente?',
            'event' => 'confirm-delete-role',
            'params' => $id
        ]);
    }

    #[On('confirm-delete-role')]
    public function deleteRole(int $id)
    {
        Gate::authorize(RolePermission::Delete->value);

        try {
            $roleService = app(RoleService::class);
            $role = $roleService->getByOne($id);
            $roleService->delete($role);

            $this->success('Rol eliminado exitosamente.', css: 'alert-success');
        } catch (Exception $e) {
            $this->error($e->getMessage() ?: 'No se pudo eliminar el rol.', css: 'alert-error');
        }
    }

    public function render(RoleService $roleService)
    {
        $roles = $roleService->getAll($this->search, 10);
        return view('roles::role-list', compact('roles'));
    }
}
