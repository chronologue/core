<?php

namespace Chronologue\Core\Database\Support;

use Closure;
use Illuminate\Database\Eloquent\Collection;

class Prepend
{
    private ?int $key;
    private Closure $callback;

    public function __construct(?int $key, Closure $callback)
    {
        $this->key = $key;
        $this->callback = $callback;
    }

    public function __invoke(Collection $collection): Collection
    {
        if ($this->key && !$collection->contains($this->key)) {
            if ($model = call_user_func($this->callback, $this->key)) {
                $collection->prepend($model);
            }
        }

        return $collection;
    }
}