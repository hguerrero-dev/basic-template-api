<?php

namespace App\Modules\Roles\DTOs;

class CreateRoleDTO
{
    public function __construct(
        public string $name,
        public string $description,
        public string $guard_name,
        public array $permissions = []
    ) {}
}
