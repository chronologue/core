<?php

namespace Chronologue\Core\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class UnauthorizedException extends AppException
{
    public function getStatusCode(): int
    {
        return Response::HTTP_FORBIDDEN;
    }
}