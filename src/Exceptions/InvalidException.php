<?php

namespace Chronologue\Core\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class InvalidException extends AppException
{
    public function getStatusCode(): int
    {
        return Response::HTTP_UNPROCESSABLE_ENTITY;
    }
}