<?php

namespace Chronologue\Core\Database\Eloquent;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Attributes\UseEloquentBuilder;
use Illuminate\Database\Eloquent\Relations\Pivot as BasePivot;

#[UseEloquentBuilder(Builder::class)]
abstract class Pivot extends BasePivot
{
    public static $snakeAttributes = false;

    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    protected $perPage = 10;

    private array $defaultHidden = ['created_at', 'updated_at', 'deleted_at'];

    public function getHidden(): array
    {
        return array_merge($this->defaultHidden, $this->hidden);
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d');
    }
}
