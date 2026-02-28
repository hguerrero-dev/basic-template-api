<?php

namespace App\Modules\Roles\DTOs;

class CreateRoleDTO
{
    public function __construct(
        public string $name,
        public array $permissions = []
    ) {}
}
