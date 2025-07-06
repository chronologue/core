<?php

namespace Chronologue\Core\Support\Traits;

trait InteractsWithData
{
    protected function normalizeSpaceFromString(?string $input): ?string
    {
        if ($input === null) {
            return null;
        }

        return preg_replace('/ {2,}/', ' ', $input);
    }
}