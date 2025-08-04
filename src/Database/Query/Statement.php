<?php

namespace Chronologue\Core\Database\Query;

use Illuminate\Database\Query\Expression;
use Stringable;

class Statement extends Expression implements Stringable
{
    public function __toString(): string
    {
        return (string)$this->value;
    }
}