<?php

namespace App\Modules\Auth\DTOs;

class RegisterDTO
{
    public function __construct(
        public string $name,
        public ?string $email = null,
        public string $password,
        public ?string $username = null
    ) {}
}
