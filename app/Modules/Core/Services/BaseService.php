<?php

namespace App\Modules\Core\Services;

use Illuminate\Database\Eloquent\Builder;

class BaseService
{
    /**
     * Aplica paginación y búsqueda a una consulta Eloquent de manera estandarizada.
     *
     * @param Builder $query La query de Eloquent
     * @param array $options Opciones de paginación y búsqueda (search, perPage, orderBy, orderDir, searchFields)
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    protected function paginate(Builder $query, array $options = [])
    {
        $search = $options['search'] ?? null;
        $perPage = (int) ($options['perPage'] ?? config('api.pagination.default'));
        $orderBy = $options['orderBy'] ?? 'created_at';
        $orderDir = $options['orderDir'] ?? 'desc';
        $searchFields = $options['searchFields'] ?? [];

        $max = config('api.pagination.max', 100);
        if ($perPage > $max) {
            $perPage = $max;
        }

        if ($search && !empty($searchFields)) {
            $query->where(function ($q) use ($search, $searchFields) {
                foreach ($searchFields as $field) {
                    $q->orWhere($field, 'like', "%{$search}%");
                }
            });
        }

        return $query->orderBy($orderBy, $orderDir)->paginate($perPage);
    }
}
