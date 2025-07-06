<?php

namespace Chronologue\Core\Database\Eloquent;

use Chronologue\Core\Database\Traits\HasDateQuery;
use Chronologue\Core\Database\Traits\HasSearchQuery;
use Chronologue\Core\Database\Traits\PaginatesQuery;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class Builder extends EloquentBuilder
{
    use PaginatesQuery;
    use HasSearchQuery;
    use HasDateQuery;

    public function orderByKey(string $direction = 'asc'): static
    {
        return $this->orderBy($this->getModel()->getQualifiedKeyName(), $direction);
    }
}