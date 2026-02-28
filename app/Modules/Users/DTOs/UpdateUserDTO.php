<?php

namespace App\Modules\Users\DTOs;

class UpdateUserDTO
{
    public function __construct(
        public string $id,
        public ?string $name = null,
        public ?string $email = null,
        public ?string $username = null,
        public array $roles = [],
        public ?string $password = null
    ) {}
}
