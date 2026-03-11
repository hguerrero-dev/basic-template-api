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
use Illuminate\Support\Str;
use Mary\Traits\Toast;

class UserForm extends Component
{
    use Toast;

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

    public function tryAddRole($role)
    {
        if ($role === SystemRole::SuperAdmin->value && !$this->pendingSuperAdmin) {
            $this->pendingSuperAdmin = true;
        } else {
            $this->roles[] = $role;
            $this->previousRoles = $this->roles;
        }
    }

    public function confirmSuperAdmin()
    {
        if (!in_array(SystemRole::SuperAdmin->value, $this->roles)) {
            $this->roles[] = SystemRole::SuperAdmin->value;
        }
        $this->pendingSuperAdmin = false;
        $this->previousRoles = $this->roles;
    }

    public function generateEmail()
    {
        if (trim($this->name) === '') {
            $this->addError('name', 'El nombre es requerido para generar el email.');
            return;
        }

        $cleanName = Str::slug($this->name, ' ');
        $parts = explode(' ', strtolower($cleanName));

        if (count($parts) < 2) {
            $this->addError('name', 'Se requiere al menos un nombre y un apellido.');
            return;
        }

        $firstName = $parts[0];
        $lastName = end($parts);

        $lettersToTake = 1;
        $maxLetters = strlen($firstName);
        $domain = '@mingob.gob.pa'; // => Change this to your desired domain
        $generatedEmail = '';
        $isUnique = false;

        while (!$isUnique) {
            $prefix = substr($firstName, 0, $lettersToTake);
            $alias = $prefix . $lastName;
            $generatedEmail = $alias . $domain;

            $exists = User::where('email', $generatedEmail)->exists();

            if (!$exists) {
                $isUnique = true;
            } else {
                if ($lettersToTake < $maxLetters) {
                    $lettersToTake++;
                } else {
                    $number = rand(10, 99);
                    $generatedEmail = $alias . $number . $domain;
                    while (User::where('email', $generatedEmail)->exists()) {
                        $number++;
                        $generatedEmail = $alias . $number . $domain;
                    }
                    $isUnique = true;
                }
            }
        }

        $this->email = $generatedEmail;
        $this->resetErrorBag('name'); // Clear name errors if email generation is successful
    }

    public function generatePassword()
    {
        // => Generate a random password with 12 characters, including letters and numbers, but no symbols
        $generatedPassword = Str::password(12, true, true, true, false);

        $this->password = $generatedPassword;
        $this->password_confirmation = $generatedPassword;
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

        $this->success('Usuario: ' . $this->name . ' ' . ($this->userId ? 'editado' : 'creado') . ' exitosamente.', css: 'alert-success');
    }

    public function render()
    {
        return view('users::user-form');
    }
}
