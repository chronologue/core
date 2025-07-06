<?php

namespace Chronologue\Core\Database\Schema;

use Illuminate\Database\Schema\Blueprint;

class Prototype extends Blueprint
{
    protected function createIndexName($type, array $columns): string
    {
        return $type . '_' . md5($this->getTable() . implode('', $columns));
    }
}
