<?php

namespace App\Modules\Roles\Enums;

enum SystemPermission: string
{
    case ViewRoles = 'roles.view';
    case CreateRoles = 'roles.create';
    case EditRoles = 'roles.edit';
    case DeleteRoles = 'roles.delete';

    public static function label(self $value): string
    {
        return match ($value) {
            self::ViewRoles => 'Ver Roles',
            self::CreateRoles => 'Crear Roles',
            self::EditRoles => 'Editar Roles',
            self::DeleteRoles => 'Eliminar Roles',
        };
    }
}
