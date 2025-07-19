<?php

namespace Chronologue\Core\Database\Eloquent;

use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

abstract class AdjacencyListModel extends Model
{
    use HasRecursiveRelationships;

    public function newEloquentBuilder($query): AdjacencyListBuilder
    {
        $builderClass = $this->resolveCustomBuilderClass();

        if ($builderClass && is_subclass_of($builderClass, AdjacencyListBuilder::class)) {
            return new $builderClass($query);
        }

        return new AdjacencyListBuilder($query);
    }
}