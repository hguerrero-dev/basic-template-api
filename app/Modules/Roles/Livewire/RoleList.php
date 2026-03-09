<?php

namespace App\Modules\Roles\Livewire;

use App\Modules\Roles\Services\RoleService;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Exception;
use Illuminate\Support\Facades\Gate;
use App\Modules\Roles\Enums\RolePermission;

class RoleList extends Component
{
    use WithPagination;

    public string $guard = 'api';

    protected $paginationTheme = 'tailwind';

    public $search = '';

    public function openRoleForm()
    {
        $this->dispatch('create-role');
    }

    // Elimina el método updatedGuard, ya no es necesario

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($id, $name)
    {
        Gate::authorize(RolePermission::Delete->value);

        $this->dispatch('open-confirm', [
            'title' => 'Eliminar Rol',
            'message' => '¿Estás seguro de que deseas eliminar el rol: ' . $name . '?',
            'event' => 'delete-role',
            'params' => ['id' => $id]
        ]);
    }

    #[On('role-saved')]
    public function refreshTable() {}

    #[On('delete-role')]
    public function deleteRole(int $id)
    {
        Gate::authorize(RolePermission::Delete->value);

        try {
            $roleService = app(RoleService::class);
            $role = $roleService->getByOne($id);
            $roleService->delete($role);

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Rol eliminado exitosamente.'
            ]);
        } catch (Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => $e->getMessage() ?: 'No se pudo eliminar el rol.'
            ]);
        }
    }

    public function render(RoleService $roleService)
    {
        $roles = $roleService->getAll($this->search, 10);
        return view('roles::role-list', compact('roles'));
    }
}
