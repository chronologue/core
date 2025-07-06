<?php

namespace Chronologue\Core\Database\Traits;

use Chronologue\Core\Support\Traits\ResolvesPageCount;
use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Pagination\LengthAwarePaginator;

trait PaginatesQuery
{
    use ResolvesPageCount;

    public function paginateQuery(array|Arrayable $params, ?Closure $callback = null): LengthAwarePaginator
    {
        $paginator = $this->paginate($this->resolvePageCount($params));

        if ($callback) {
            $callback($paginator->getCollection());
        }

        return $paginator;
    }
}