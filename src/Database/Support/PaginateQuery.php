<?php

namespace Chronologue\Core\Database\Support;

use Chronologue\Core\Contracts\SearchParams;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class PaginateQuery
{
    private int|SearchParams $size;
    private ?Closure $callback;

    public function __construct(int|SearchParams $size = 10, ?Closure $callback = null)
    {
        $this->size = $size;
        $this->callback = $callback;
    }

    public function __invoke(Builder $builder): LengthAwarePaginator
    {
        $paginator = $builder->paginate(
            $this->size instanceof SearchParams ? $this->size->getPageSize() : $this->size
        );

        if ($this->callback) {
            call_user_func($this->callback, $paginator->getCollection());
        }

        return $paginator;
    }
}