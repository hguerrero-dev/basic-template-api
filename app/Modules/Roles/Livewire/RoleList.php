<?php

namespace App\Modules\Roles\Livewire;

use App\Modules\Roles\Services\RoleService;
use Livewire\Component;

class RoleList extends Component
{
    public function render(RoleService $roleService)
    {
        $roles = $roleService->getAll();
        return view('roles::role-list', compact('roles'));
    }
}
