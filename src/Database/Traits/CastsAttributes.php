<?php

namespace Chronologue\Core\Database\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Model
 */
trait CastsAttributes
{
    protected function accessorAndMutator(string $key)
    {
        return value(new Attribute(
            get: fn($value, $attributes) => $this->castAttribute($key, $attributes[$key] ?? null),
            set: fn($value) => [$key => $value]
        ));
    }

    protected function accessorAndMutatorWithoutCast(string $key)
    {
        return value(new Attribute(
            get: fn($value, $attributes) => $attributes[$key] ?? null,
            set: fn($value) => [$key => $value]
        ));
    }
}