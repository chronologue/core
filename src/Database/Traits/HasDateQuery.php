<?php

namespace Chronologue\Core\Database\Traits;

use Illuminate\Support\Facades\Date;

trait HasDateQuery
{
    public function whereAllowedDate(string $column, mixed $month, mixed $year = null): static
    {
        if (!empty($month)) {
            $this->whereMonth($column, $month);
            $this->whereYear($column, $year ?: Date::now()->year);
            return $this;
        }

        $this->whereDate($column, '<=', Date::now());
        $this->whereYear($column, Date::now()->year);
        return $this;
    }
}