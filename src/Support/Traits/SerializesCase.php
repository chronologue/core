<?php

namespace Chronologue\Core\Support\Traits;

use BackedEnum;

/**
 * @mixin BackedEnum
 */
trait SerializesCase
{
    public static function toRecord(): array
    {
        $result = [];
        foreach (static::cases() as $case) {
            $result[$case->value] = $case->display();
        }
        return $result;
    }

    public function display(): string
    {
        return $this->name;
    }

    public function value(): int
    {
        return $this->value;
    }

    public function toArray(): array
    {
        return [$this->value(), $this->display()];
    }
}