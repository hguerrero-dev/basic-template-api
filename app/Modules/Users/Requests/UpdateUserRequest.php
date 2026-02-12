<?php

namespace App\Modules\Users\Requests;

use Illuminate\Validation\Rule;

class UpdateUserRequest extends CreateUserRequest
{
    public function rules(): array
    {
        $rules = parent::rules();
        $userId = $this->route('user') ?? $this->route('id');
        $rules['email'] = ['nullable', 'email', Rule::unique('users', 'email')->ignore($userId)];
        $rules['username'] = ['required', 'string', Rule::unique('users', 'username')->ignore($userId)];
        $rules['password'] = ['nullable', 'string', 'min:8', 'confirmed'];

        return $rules;
    }
}
