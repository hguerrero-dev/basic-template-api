<?php

namespace App\Modules\Roles\DTOs;

class UpdateRoleDTO
{
    public function __construct(
        public int $id,
        public ?string $name = null,
        public string $description,
        public ?string $guard_name = null,
        public ?array $permissions = null
    ) {}
}
