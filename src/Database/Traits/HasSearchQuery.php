<?php

namespace Chronologue\Core\Database\Traits;

trait HasSearchQuery
{
    public function whereSearchQuery(?string $search, ?callable $callback = null): static
    {
        if (!empty($search)) {
            $this->where(function (self $query) use ($search, $callback) {
                if (is_callable($callback)) {
                    $callback($query, $search);
                }
            });
        }

        return $this;
    }
}