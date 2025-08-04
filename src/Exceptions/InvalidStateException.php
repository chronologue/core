<?php

namespace Chronologue\Core\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class InvalidStateException extends ApplicationException
{
    public function __construct(string $message)
    {
        parent::__construct(Response::HTTP_CONFLICT, $message);
    }
}
