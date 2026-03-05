<?php

namespace App\Modules\Users\DTOs;

class UpdateUserDTO
{
    public function __construct(
        public string $id,
        public ?string $name = null,
        public ?string $email = null,
        public array $roles = [],
        public string $status = 'active',
        public ?string $password = null
    ) {}
}
