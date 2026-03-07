<?php

namespace App\Modules\Users\Requests;

use App\Modules\Users\Enums\UserStatus;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends CreateUserRequest
{
    public function rules(): array
    {
        $rules = parent::rules();
        $userId = $this->route('user') ?? $this->route('id');
        $rules['email'] = ['nullable', 'email', Rule::unique('users', 'email')->ignore($userId)];
        $rules['password'] = ['nullable', 'string', 'min:8', 'confirmed'];
        $rules['roles.*'] = ['integer', 'exists:roles,id'];
        $rules['status'] = ['nullable', Rule::enum(UserStatus::class)];

        return $rules;
    }
}
