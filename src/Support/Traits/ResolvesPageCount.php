<?php

namespace Chronologue\Core\Support\Traits;

use Illuminate\Contracts\Support\Arrayable;

trait ResolvesPageCount
{
    protected function resolvePageCount(array|Arrayable $params): ?int
    {
        if ($params instanceof Arrayable) {
            $params = $params->toArray();
        }

        return $params['per_page'] ?? null;
    }
}