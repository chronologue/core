<?php

namespace Chronologue\Core\Database\Support;

use Chronologue\Core\Support\Traits\ResolvesPageCount;
use Illuminate\Database\Eloquent\Builder;

class FullTextSearch
{
    use ResolvesPageCount;

    private string|array $columns;
    private string $search;
    private string $boolean;

    public function __construct(array|string $columns, string $search, string $boolean = 'and')
    {
        $this->columns = $columns;
        $this->search = $search;
        $this->boolean = $boolean;
    }

    public function __invoke(Builder $builder): Builder
    {
        $options = [
            'mode' => 'websearch',
        ];

        return $builder->whereFullText($this->columns, $this->search, $options, $this->boolean);
    }
}