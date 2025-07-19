<?php

namespace Chronologue\Core\Contracts;

interface SearchParams
{
    public function getSearchParams($key = null, $default = null);

    public function getSearchQuery(): ?string;

    public function getPageSize(): int;
}