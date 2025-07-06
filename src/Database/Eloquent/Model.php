<?php

namespace Chronologue\Core\Database\Eloquent;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\SoftDeletes;

abstract class Model extends EloquentModel
{
    use SoftDeletes, HasFactory;

    public static $snakeAttributes = false;

    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    protected $perPage = 10;

    public function newEloquentBuilder($query): EloquentBuilder
    {
        return new Builder($query);
    }

    public function getHidden(): array
    {
        return ['created_at', 'updated_at', 'deleted_at', ...$this->hidden];
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d');
    }
}
