<?php

namespace Chronologue\Core\Exceptions;

use Illuminate\Contracts\Debug\ShouldntReport;
use Illuminate\Validation\ValidationException as LaravelValidationException;

class ValidationException extends LaravelValidationException implements ShouldntReport
{
    public static function of(string $message): static
    {
        return static::withMessages([
            'message' => $message,
        ]);
    }
}