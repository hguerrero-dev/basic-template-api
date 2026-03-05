<?php

namespace App\Modules\Users\Livewire;

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

class UserForm extends Component
{
    public ?string $userId = null;

    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public array $roles = [];
    public string $status = 'active';

    #[Computed]
    public function catalogs()
    {
        return app(UserService::class)->getFormOptions();
    }

    #[On('create-user')]
    public function create()
    {
        Gate::authorize(UserPermission::Create->value);

        $this->reset();
        $this->resetValidation();
        // Fallback default value for array since reset() makes it empty natively, but let's be sure.
        $this->roles = [];
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
        // Convert to array of names for the multiselect
        $this->roles = $user->roles->pluck('name')->toArray();

        $this->dispatch('open-modal', 'user-form-modal');
    }

    public function save()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->userId)],
            'roles' => 'required|array|min:1',
            'roles.*' => 'string|exists:roles,name',
            'status' => ['required', Rule::enum(\App\Modules\Users\Enums\UserStatus::class)]
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
    }

    public function render()
    {
        return view('users::user-form');
    }
}
