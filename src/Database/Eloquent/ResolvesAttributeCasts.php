<?php

namespace Chronologue\Core\Database\Eloquent;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Model
 */
trait ResolvesAttributeCasts
{
    protected function resolveCustomAttribute(string $key, bool $cast = true)
    {
        return value(new Attribute(
            fn($value, $attributes) => $this->resolveAttributeCasting($attributes, $key, $cast),
            fn($value) => [$key => $value]
        ));
    }

    protected function resolveAttributeCasting(array $attributes, string $key, bool $cast)
    {
        $value = $attributes[$key] ?? null;
        return $cast ? $this->castAttribute($key, $value) : $value;
    }
}