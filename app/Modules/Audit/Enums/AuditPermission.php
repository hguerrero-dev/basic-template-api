<?php

namespace App\Modules\Audit\Enums;

use App\Modules\Core\Traits\HasPermissionLabels;

enum AuditPermission: string
{
    use HasPermissionLabels;

    case View   = 'audit.view';

    public function getModuleLabel(): string
    {
        return 'Auditoría';
    }
}
