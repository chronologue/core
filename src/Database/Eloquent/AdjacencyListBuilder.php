<?php

namespace Chronologue\Core\Database\Eloquent;

use Chronologue\Core\Database\Traits\HasDateQuery;
use Chronologue\Core\Database\Traits\HasSearchQuery;
use Chronologue\Core\Database\Traits\PaginatesQuery;
use Staudenmeir\LaravelAdjacencyList\Eloquent\Builder as EloquentBuilder;

class AdjacencyListBuilder extends EloquentBuilder
{
    use PaginatesQuery;
    use HasSearchQuery;
    use HasDateQuery;
}