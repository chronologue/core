<?php

namespace Chronologue\Core\Database\Support;

use Illuminate\Database\Eloquent\Builder;

class FullTextSearch
{
    private string|array $columns;
    private ?string $search;
    private string $boolean;

    public function __construct(array|string $columns, ?string $search, string $boolean = 'and')
    {
        $this->columns = $columns;
        $this->search = $search;
        $this->boolean = $boolean;
    }

    public function __invoke(Builder $builder): Builder
    {
        if (empty($this->search)) {
            return $builder;
        }

        $options = [
            'mode' => 'websearch',
        ];

        return $builder->whereFullText($this->columns, $this->search, $options, $this->boolean);
    }
}