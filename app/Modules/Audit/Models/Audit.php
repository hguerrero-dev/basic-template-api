<?php

namespace App\Modules\Audit\Models;

use OwenIt\Auditing\Models\Audit as OwenAudit;

class Audit extends OwenAudit
{
    protected $table = 'audits';

    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    public const CACHE_TAG = 'audits';
    public const CACHE_KEY_LIST = 'audits_list';
    public const CACHE_KEY_DETAIL = 'audit_detail';
}
