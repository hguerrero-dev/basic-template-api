<?php

namespace App\Modules\Users\Enums;

use App\Modules\Core\Traits\HasPermissionLabels;

enum UserPermission: string
{
    use HasPermissionLabels;

    case View   = 'users.view';
    case Create = 'users.create';
    case Edit   = 'users.edit';
    case Delete = 'users.delete';

    public function getModuleLabel(): string
    {
        return 'Usuarios';
    }
}
