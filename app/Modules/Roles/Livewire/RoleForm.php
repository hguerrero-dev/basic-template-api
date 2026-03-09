<?php

namespace App\Modules\Roles\Livewire;

use App\Modules\Roles\Enums\RolePermission;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;

class RoleForm extends Component
{
    public ?int $roleId = null;

    #[On('create-role')]
    public function create()
    {
        Gate::authorize(RolePermission::Create->value);

        $this->reset();
        $this->resetValidation();

        $this->dispatch('open-modal', 'role-form-modal');
    }

    public function render()
    {
        return view('roles::role-form');
    }
}
