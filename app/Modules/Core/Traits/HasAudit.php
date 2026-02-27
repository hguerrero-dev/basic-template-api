<?php

namespace App\Modules\Core\Traits;

use OwenIt\Auditing\Auditable as OwenItAuditable;

trait HasAudit
{
    use OwenItAuditable;

    // Esto permite que el template ya sepa qué NO auditar por defecto (seguridad)
    protected $auditExclude = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];
}
