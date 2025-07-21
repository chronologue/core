<?php

namespace Chronologue\Core\Database\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/** @mixin Model */
trait CreateUuidAttribute
{
    public static function bootCreateUuidAttribute(): void
    {
        static::creating(function (Model $model) {
            $model->setAttribute('uuid', Str::uuid());
        });
    }
}