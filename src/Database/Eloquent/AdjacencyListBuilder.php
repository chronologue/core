<?php

namespace Chronologue\Core\Database\Eloquent;

use Staudenmeir\LaravelAdjacencyList\Eloquent\Builder as EloquentBuilder;

class AdjacencyListBuilder extends EloquentBuilder
{
    use HasSearchQuery;
}