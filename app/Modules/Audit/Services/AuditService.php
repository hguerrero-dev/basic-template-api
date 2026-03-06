<?php

namespace App\Modules\Audit\Services;

use App\Modules\Audit\Models\Audit;
use App\Modules\Core\Services\BaseService;
use Illuminate\Pagination\Paginator;

class AuditService extends BaseService
{
    public function getAll(?string $search = null, ?int $perPage = null)
    {
        $page = Paginator::resolveCurrentPage('page') ?: 1;
        $perPage = $perPage ?? config('api.pagination.default', 15);

        $cacheKey = sprintf(
            '%s:s:%s:p:%s:pg:%s',
            Audit::CACHE_KEY_LIST,
            $search,
            $perPage,
            $page
        );

        return $this->paginateAndCache(
            Audit::with('user', 'auditable'),
            $cacheKey,
            [Audit::CACHE_TAG],
            3600,
            [
                'search' => $search,
                'page' => $page,
                'perPage' => $perPage,
                'searchFields' => ['event', 'ip_address']
            ]
        );
    }

    public function getByOne($id)
    {
        $cacheKey = sprintf('%s:id:%s', Audit::CACHE_KEY_DETAIL, $id);

        return cache()->tags([Audit::CACHE_TAG])->remember($cacheKey, 3600, function () use ($id) {
            return Audit::with('user', 'auditable')->findOrFail($id);
        });
    }
}
