<?php

namespace Chronologue\Core\Database\Eloquent;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

abstract class AdjacencyListModel extends Model
{
    use HasRecursiveRelationships;

    public function newEloquentBuilder($query): EloquentBuilder
    {
        return new AdjacencyListBuilder($query);
    }
}