<?php

namespace Chronologue\Core\Support;

use Illuminate\Pagination\LengthAwarePaginator;

class Paginator extends LengthAwarePaginator
{
    public function toArray(): array
    {
        return [
            'data' => $this->items->toArray(),
            'metadata' => [
                'currentPage' => $this->currentPage(),
                'to' => $this->lastPage(),
                'total' => $this->total(),
            ],
        ];
    }
}