<?php

namespace App\Modules\Audit\Services;

use App\Modules\Audit\Models\Audit;
use App\Modules\Core\Services\BaseService;
use Illuminate\Pagination\Paginator;

class AuditService extends BaseService
{
    public function getAll(array $filters = [], ?int $perPage = null)
    {
        $page = Paginator::resolveCurrentPage('page') ?: 1;
        $perPage = $perPage ?? config('api.pagination.default', 15);


        $search = $filters['search'] ?? null;
        $event = $filters['event'] ?? null;
        $dateFrom = $filters['date_from'] ?? null;
        $dateTo = $filters['date_to'] ?? null;

        $cacheKey = sprintf(
            '%s:s:%s:e:%s:df:%s:dt:%s:p:%s:pg:%s',
            Audit::CACHE_KEY_LIST,
            $search ?: 'all',
            $event ?: 'all',
            $dateFrom ?: 'all',
            $dateTo ?: 'all',
            $perPage,
            $page
        );

        $query = Audit::with('user', 'auditable');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $searchLower = mb_strtolower($search, 'UTF-8');
                $q->whereRaw("LOWER(event::text) LIKE ?", ["%{$searchLower}%"])
                    ->orWhereRaw("LOWER(ip_address::text) LIKE ?", ["%{$searchLower}%"])
                    ->orWhereRaw("LOWER(auditable_type::text) LIKE ?", ["%{$searchLower}%"])
                    ->orWhereExists(function ($sub) use ($searchLower) {
                        $sub->select('id')
                            ->from('users')
                            ->whereRaw('users.id::text = audits.user_id::text')
                            ->whereRaw("LOWER(users.name::text) LIKE ?", ["%{$searchLower}%"]);
                    });
            });
        }

        if (!empty($event)) {
            $query->where('event', $event);
        }

        if (!empty($dateFrom)) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if (!empty($dateTo)) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $query->orderBy('created_at', 'desc');

        return $this->paginateAndCache(
            $query,
            $cacheKey,
            [Audit::CACHE_TAG],
            3600,
            [
                'page' => $page,
                'perPage' => $perPage,
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
