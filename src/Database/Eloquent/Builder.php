<?php

namespace Chronologue\Core\Database\Eloquent;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class Builder extends EloquentBuilder
{
    use HasSearchQuery;
}