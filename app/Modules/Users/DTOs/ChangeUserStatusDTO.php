<?php

namespace App\Modules\Users\DTOs;

class ChangeUserStatusDTO
{
    public function __construct(
        public int $id,
        public string $status
    ) {}
}
