<?php

namespace App\Modules\Users\Livewire;

use App\Modules\Roles\Enums\SystemRole;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Computed;
use App\Modules\Users\Models\User;
use App\Modules\Users\Services\UserService;
use App\Modules\Users\DTOs\CreateUserDTO;
use App\Modules\Users\DTOs\UpdateUserDTO;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;
use App\Modules\Users\Enums\UserPermission;
use App\Modules\Users\Enums\UserStatus;

class UserForm extends Component
{
    public ?string $userId = null;

    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public array $roles = [];
    public array $previousRoles = [];
    public $pendingSuperAdmin = false;
    public string $status = 'active';

    #[Computed]
    public function catalogs()
    {
        return app(UserService::class)->getFormOptions();
    }

    public function updatedRoles($value)
    {
        // Detecta si se agregó super_admin
        if (
            in_array(SystemRole::SuperAdmin, $this->roles) &&
            !in_array(SystemRole::SuperAdmin, $this->previousRoles) &&
            !$this->pendingSuperAdmin
        ) {
            // Quita el rol temporalmente
            $this->roles = array_diff($this->roles, [SystemRole::SuperAdmin]);
            $this->pendingSuperAdmin = true;

            // Lanza el modal global
            $this->dispatch('confirm', [
                'title' => 'Confirmar selección',
                'message' => '¿Estás seguro que deseas asignar el rol super administrador a este usuario?',
                'onConfirm' => 'confirmSuperAdmin'
            ]);
        }

        // Actualiza el array anterior
        $this->previousRoles = $this->roles;
    }

    public function confirmSuperAdmin()
    {
        $this->roles[] = SystemRole::SuperAdmin;
        $this->pendingSuperAdmin = false;
    }

    #[On('create-user')]
    public function create()
    {
        Gate::authorize(UserPermission::Create->value);

        $this->reset();
        $this->resetValidation();

        $this->roles = [];
        $this->previousRoles = $this->roles;
        $this->dispatch('open-modal', 'user-form-modal');
    }

    #[On('edit-user')]
    public function edit(string $id)
    {
        Gate::authorize(UserPermission::Edit->value);

        $this->reset();
        $this->resetValidation();

        $this->userId = $id;
        $user = User::findOrFail($id);

        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = '';
        $this->password_confirmation = '';

        $this->status = $user->status?->value ?? 'active';

        $this->roles = $user->roles->pluck('name')->toArray();
        $this->previousRoles = $this->roles;

        $this->dispatch('open-modal', 'user-form-modal');
    }

    public function save()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->userId)],
            'roles' => 'required|array|min:1',
            'roles.*' => 'string|exists:roles,name',
            'status' => ['required', Rule::enum(UserStatus::class)]
        ];

        if (!$this->userId || $this->password) {
            $rules['password'] = 'required|string|min:8|same:password_confirmation';
        }

        $this->validate($rules);

        if ($this->userId) {
            Gate::authorize(UserPermission::Edit->value);

            $dto = new UpdateUserDTO(
                id: $this->userId,
                name: $this->name,
                email: $this->email,
                roles: $this->roles,
                status: $this->status,
                password: $this->password ?: null
            );
            app(UserService::class)->update($dto);
        } else {
            Gate::authorize(UserPermission::Create->value);

            $dto = new CreateUserDTO(
                name: $this->name,
                email: $this->email,
                roles: $this->roles,
                status: $this->status,
                password: $this->password
            );
            app(UserService::class)->create($dto);
        }

        $this->dispatch('close-modal', 'user-form-modal');
        $this->dispatch('user-saved');

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Usuario: ' . $this->name . ' ' . ($this->userId ? 'editado' : 'creado') . ' exitosamente.'
        ]);
    }

    public function render()
    {
        return view('users::user-form');
    }
}
