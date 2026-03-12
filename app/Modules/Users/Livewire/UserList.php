<?php

namespace App\Modules\Users\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use App\Modules\Users\Services\UserService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use App\Modules\Users\Enums\UserPermission;
use Mary\Traits\Toast;

class UserList extends Component
{
    use WithPagination, Toast;

    protected $paginationTheme = 'tailwind';

    public $search = '';

    #[On('user-saved')]
    public function refreshTable() {}

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($id, $name)
    {
        Gate::authorize(UserPermission::Delete->value);

        $this->dispatch('open-confirm-modal', [
            'title' => 'Eliminar Usuario',
            'message' => '¿Deseas eliminar este usuario (' . $name . ') de forma permanente?',
            'event' => 'confirm-delete-user',
            'params' => $id
        ]);
    }

    #[On('confirm-delete-user')]
    public function deleteUser($id)
    {
        Gate::authorize(UserPermission::Delete->value);

        try {
            $userService = app(UserService::class);
            $user = $userService->getByOne($id); // Verificar que el usuario existe antes de intentar eliminarlo
            $userService->delete($id);

            $this->success(
                title: 'Usuario eliminado exitosamente.',
                description: 'El usuario: ' . $user->name . ' ha sido eliminado.',
                position: 'top-right',
                css: 'alert-success',
                timeout: 3000,
            );
        } catch (\Exception $e) {
            Log::error('Error al eliminar usuario: ' . $e->getMessage());

            $this->error(
                title: 'Error',
                description: 'No se pudo eliminar el usuario. Intente más tarde.',
                position: 'top-right',
                css: 'alert-error',
                timeout: 3000,
                redirectTo: null,
                noProgress: false,
            );
        }
    }

    public function render(UserService $userService)
    {
        $users = $userService->getAll($this->search, 10);

        return view('users::user-list', [
            'users' => $users
        ]);
    }
}
