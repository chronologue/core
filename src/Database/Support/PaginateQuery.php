<?php

namespace Chronologue\Core\Database\Support;

use Chronologue\Core\Support\Traits\ResolvesPageCount;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Builder;

class PaginateQuery
{
    use ResolvesPageCount;

    private array|Arrayable $params;
    private ?Closure $callback;

    public function __construct(array|Arrayable $params = [], ?Closure $callback = null)
    {
        $this->params = $params;
        $this->callback = $callback;
    }

    public function __invoke(Builder $builder): LengthAwarePaginator
    {
        $paginator = $builder->paginate($this->resolvePageCount($this->params));

        if ($this->callback) {
            call_user_func($this->callback, $paginator->getCollection());
        }

        return $paginator;
    }
}