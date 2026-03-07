<?php

namespace App\Modules\Users\DTOs;

use App\Modules\Users\Enums\UserStatus;

class UpdateUserDTO
{
    public function __construct(
        public string $id,
        public ?string $name = null,
        public ?string $email = null,
        public array $roles = [],
        public ?string $status = UserStatus::Active->value,
        public ?string $password = null
    ) {}
}
