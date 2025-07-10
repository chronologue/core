<?php

namespace Chronologue\Core\Database\Traits;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin Model
 */
trait LoadsRelationships
{
    public function loadHasMany(string $relation, ?Closure $callback = null): static
    {
        return $this->load([
            $relation => function (HasMany $query) use ($callback) {
                if ($callback) {
                    $query->tap($callback);
                }
            },
        ]);
    }

    public function loadBelongsTo(string $relation, ?Closure $callback = null): static
    {
        return $this->load([
            $relation => function (BelongsTo $query) use ($callback) {
                if ($callback) {
                    $query->tap($callback);
                }
            },
        ]);
    }

    public function loadBelongsToMany(string $relation, ?Closure $callback = null): static
    {
        return $this->load([
            $relation => function (BelongsToMany $query) use ($callback) {
                if ($callback) {
                    $query->tap($callback);
                }
            },
        ]);
    }
}