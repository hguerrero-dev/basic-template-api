<?php

namespace App\Modules\Users\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use App\Modules\Users\Services\UserService;

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

    public function render(UserService $userService)
    {
        // Hacemos uso tu servicio existente, pasando la búsqueda y perPage (ej. 10)
        $users = $userService->getAll($this->search, 10);

        return view('users::user-list', [
            'users' => $users
        ]);
    }
}
