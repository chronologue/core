<?php

namespace Chronologue\Core\Database\Eloquent;

use Illuminate\Database\Eloquent\Attributes\UseEloquentBuilder;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

#[UseEloquentBuilder(AdjacencyListBuilder::class)]
abstract class AdjacencyListModel extends Model
{
    use HasRecursiveRelationships;
}