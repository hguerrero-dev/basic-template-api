<?php

namespace App\Modules\Users\Requests;

use App\Modules\Core\Requests\BaseRequest;

class CreateUserRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['nullable', 'email', 'unique:users,email'],
            'username' => ['required', 'string', 'unique:users,username'],
            'password' => ['required', 'string', 'min:8', 'confirmed'], // 'confirmed' espera password_confirmation
            'role'     => ['nullable', 'exists:roles,name'],
        ];
    }
}
