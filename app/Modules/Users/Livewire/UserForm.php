<?php

namespace App\Modules\Users\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Modules\Users\Models\User;
use App\Modules\Users\Services\UserService;
use App\Modules\Users\DTOs\CreateUserDTO;
use App\Modules\Users\DTOs\UpdateUserDTO;
use Illuminate\Validation\Rule;

class UserForm extends Component
{
    public ?string $userId = null;

    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    #[On('create-user')]
    public function create()
    {
        $this->reset();
        $this->resetValidation();
        $this->dispatch('open-modal', 'user-form-modal');
    }

    #[On('edit-user')]
    public function edit(string $id)
    {
        $this->reset();
        $this->resetValidation();

        $this->userId = $id;
        $user = User::findOrFail($id);

        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = '';
        $this->password_confirmation = ''; // Don't populate the password

        $this->dispatch('open-modal', 'user-form-modal');
    }

    public function save()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->userId)],
        ];

        if (!$this->userId || $this->password) {
            $rules['password'] = 'required|string|min:8|same:password_confirmation';
        }

        $this->validate($rules);

        if ($this->userId) {
            $dto = new UpdateUserDTO(
                id: $this->userId,
                name: $this->name,
                email: $this->email,
                password: $this->password ?: null
            );
            app(UserService::class)->update($dto);
        } else {
            $dto = new CreateUserDTO(
                name: $this->name,
                email: $this->email,
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
