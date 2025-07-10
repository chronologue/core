<?php

namespace Chronologue\Core\Database\Support;

use Illuminate\Database\Eloquent\Builder;

class OrderByKey
{
    private string $direction;
    private bool $qualify;

    public function __construct(string $direction = 'desc', bool $qualify = true)
    {
        $this->direction = $direction;
        $this->qualify = $qualify;
    }

    public function __invoke(Builder $builder): Builder
    {
        return $builder->orderBy(
            $this->qualify
                ? $builder->getModel()->getQualifiedKeyName()
                : $builder->getModel()->getKeyName(),
            $this->direction
        );
    }
}