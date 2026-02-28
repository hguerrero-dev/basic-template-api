<?php

namespace App\Modules\Users\DTOs;

class CreateUserDTO
{
    public function __construct(
        public string $name,
        public ?string $email = null,
        public string $password,
        public string $username,
        public array $roles = []
    ) {}
}
