<?php

namespace Chronologue\Core\Support\Traits;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

trait HasPagination
{
    use ResolvesPageCount;

    protected function paginateQuery(Builder $query, array|Arrayable $params, ?Closure $callback = null): LengthAwarePaginator
    {
        $paginator = $query->paginate($this->resolvePageCount($params));

        if ($callback) {
            $callback($paginator->getCollection());
        }

        return $paginator;
    }

    protected function getCollection(LengthAwarePaginator $paginator): Collection
    {
        return value($paginator->getCollection());
    }
}