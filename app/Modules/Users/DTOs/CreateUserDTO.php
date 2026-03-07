<?php

namespace App\Modules\Users\DTOs;

use App\Modules\Users\Enums\UserStatus;

class CreateUserDTO
{
    public function __construct(
        public string $name,
        public string $password,
        public string $email,
        public array $roles = [],
        public string $status = UserStatus::Active->value
    ) {}
}
