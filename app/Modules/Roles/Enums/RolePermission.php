<?php

namespace App\Modules\Roles\Enums;

use App\Modules\Core\Traits\HasPermissionLabels;

enum RolePermission: string
{
    use HasPermissionLabels;

    case View   = 'roles.view';
    case Create = 'roles.create';
    case Edit   = 'roles.edit';
    case Delete = 'roles.delete';

    public function getModuleLabel(): string
    {
        return 'Roles';
    }
}
