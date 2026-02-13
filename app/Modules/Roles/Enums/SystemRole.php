<?php

namespace App\Modules\Roles\Enums;

enum SystemRole: string
{
    case SuperAdmin = 'super_admin';
    case Admin      = 'admin';
    case Customer   = 'customer';

    /**
     * Return an array of protected roles that cannot be deleted or modified in certain ways.
     */
    public static function protectedRoles(): array
    {
        return [
            self::SuperAdmin->value,
            self::Admin->value,
        ];
    }
}
