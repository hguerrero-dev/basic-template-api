<?php

namespace App\Modules\Core\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

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
        $pageName = $options['pageName'] ?? 'page';
        $page = $options['page'] ?? null;

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

        return $query->orderBy($orderBy, $orderDir)->paginate($perPage, ['*'], $pageName, $page);
    }

    /**
     * Aplica paginación envolviéndola en caché sin los problemas de serialización en Livewire.
     * Solo cachea la data cruda (ítems y total) y reconstruye el paginador.
     *
     * @param Builder $query
     * @param string $cacheKey
     * @param array $tags
     * @param int $ttl Segundos para expirar
     * @param array $options
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    protected function paginateAndCache(Builder $query, string $cacheKey, array $tags = [], int $ttl = 3600, array $options = [])
    {
        $pageName = $options['pageName'] ?? 'page';
        // Volvemos al resolveCurrentPage porque Livewire interactúa con esto dinámicamente.
        $page = $options['page'] ?? \Illuminate\Pagination\Paginator::resolveCurrentPage($pageName) ?: 1;
        $perPage = (int) ($options['perPage'] ?? config('api.pagination.default'));

        // Aseguramos que la configuración de perPage dentro de $options también sea la correcta
        $options['perPage'] = $perPage;
        $options['page'] = $page;

        // ¡EL ERROR DE LIVEWIRE!: Debemos incluir la página (y parámetros) en la llave de caché 
        // o siempre devolverá la primera página en llamadas asíncronas.
        $finalCacheKey = "{$cacheKey}_page_{$page}_per_{$perPage}";

        $cachedData = Cache::tags($tags)->remember($finalCacheKey, $ttl, function () use ($query, $options) {
            $paginator = $this->paginate($query, $options);

            return [
                // Usamos collect() sobre items() para evitar el error de Undefined Method
                'items' => collect($paginator->items()),
                'total' => $paginator->total()
            ];
        });

        return new \Illuminate\Pagination\LengthAwarePaginator(
            $cachedData['items'],
            $cachedData['total'],
            $perPage,
            $page,
            [
                'path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(),
                'pageName' => $pageName,
            ]
        );
    }
}
