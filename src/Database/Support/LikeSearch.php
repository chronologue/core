<?php

namespace Chronologue\Core\Database\Support;

use Chronologue\Core\Support\Traits\ResolvesPageCount;
use Illuminate\Database\Eloquent\Builder;

class LikeSearch
{
    use ResolvesPageCount;

    private string $column;
    private string $search;
    private string $boolean;

    public function __construct(string $column, string $search, string $boolean = 'and')
    {
        $this->column = $column;
        $this->search = $search;
        $this->boolean = $boolean;
    }

    public function __invoke(Builder $builder): Builder
    {
        if (empty($this->search)) {
            return $builder;
        }

        $search = $this->search . '%';
        if (strlen($this->search) >= config('parameters.like_search_min_length', 3)) {
            $search = '%' . $search;
        }

        return $builder->whereLike($this->column, $search, false, $this->boolean);
    }
}