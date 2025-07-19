<?php

namespace Chronologue\Core\Database\Eloquent;

use Chronologue\Core\Database\Traits\HasSearchQuery;
use Staudenmeir\LaravelAdjacencyList\Eloquent\Builder as EloquentBuilder;

class AdjacencyListBuilder extends EloquentBuilder
{
    use HasSearchQuery;
}