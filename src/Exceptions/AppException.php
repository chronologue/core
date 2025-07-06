<?php

namespace Chronologue\Core\Exceptions;

use Illuminate\Contracts\Debug\ShouldntReport;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

class AppException extends RuntimeException implements ShouldntReport
{
    public function getStatusCode(): int
    {
        return Response::HTTP_INTERNAL_SERVER_ERROR;
    }
}