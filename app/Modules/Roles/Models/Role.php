<?php

namespace App\Modules\Roles\Models;

use App\Modules\Core\Traits\HasAudit;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole implements Auditable
{
    use HasAudit;

    protected $fillable = ['name', 'description', 'guard_name', 'updated_at', 'created_at'];

    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    public const CACHE_TAG = 'roles';
    public const CACHE_KEY_LIST = 'roles_list';
    public const CACHE_KEY_DETAIL = 'role_detail';
    public const CACHE_TAG_PERMISSIONS = 'permissions';
    public const CACHE_KEY_PERMISSIONS_GROUPED = 'permissions_grouped';
}
