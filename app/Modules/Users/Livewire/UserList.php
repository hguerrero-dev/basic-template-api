<?php

namespace App\Modules\Users\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use App\Modules\Users\Services\UserService;
use Illuminate\Support\Facades\Gate;
use App\Modules\Users\Enums\UserPermission;

class UserList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';

    #[On('user-saved')]
    public function refreshTable() {}

    public function updatedSearch()
    {
        $this->resetPage();
    }

    #[On('delete-user')]
    public function deleteUser(string $id)
    {
        Gate::authorize(UserPermission::Delete->value);

        $userService = app(UserService::class);
        $userService->delete($id);
    }

    public function render(UserService $userService)
    {
        $users = $userService->getAll($this->search, 10);

        return view('users::user-list', [
            'users' => $users
        ]);
    }
}
