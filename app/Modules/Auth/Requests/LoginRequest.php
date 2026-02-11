<?php

namespace App\Modules\Auth\Requests;

use App\Modules\Core\Requests\BaseRequest;

class LoginRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'username' => ['required_without:email', 'string'],
            'email'    => ['required_without:username', 'email', 'ends_with:@mingob.gob.pa'],
            'password' => ['required', 'string'],
        ];
    }
}
