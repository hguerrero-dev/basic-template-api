<?php

namespace App\Modules\Users\Enums;

enum UserPermission: string
{
    case View   = 'users.view';
    case Create = 'users.create';
    case Edit   = 'users.edit';
    case Delete = 'users.delete';

    public static function label(self $value): string
    {
        return match ($value) {
            self::View => 'Ver Usuarios',
            self::Create => 'Crear Usuarios',
            self::Edit => 'Editar Usuarios',
            self::Delete => 'Eliminar Usuarios',
        };
    }
}
