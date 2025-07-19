<?php

namespace Chronologue\Core\Database\Eloquent;

use Chronologue\Core\Database\Traits\HasSearchQuery;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class Builder extends EloquentBuilder
{
    use HasSearchQuery;
}