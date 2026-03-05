<?php

namespace App\Modules\Users\DTOs;

class CreateUserDTO
{
    public function __construct(
        public string $name,
        public string $password,
        public ?string $email = null,
        public array $roles = [],
        public string $status = 'active'
    ) {}
}
