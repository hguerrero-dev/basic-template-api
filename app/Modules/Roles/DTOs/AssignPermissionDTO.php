<?php

namespace App\Modules\Roles\DTOs;

class AssignPermissionDTO
{
    public function __construct(
        public string $roleId,
        public array $permissions
    ) {}
}
