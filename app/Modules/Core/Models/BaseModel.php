<?php

namespace App\Modules\Core\BaseModels;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Core\Traits\HasAudit;

abstract class BaseModel extends Model
{
    use HasAudit;
}
