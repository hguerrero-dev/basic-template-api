<?php

namespace App\Modules\Auth\DTOs;

class LoginDTO
{
    public string $identifier;
    public string $password;

    public function __construct(array $credentials)
    {
        $this->identifier = $credentials['username'] ?? $credentials['email'] ?? '';
        $this->password = $credentials['password'] ?? '';
    }
}
