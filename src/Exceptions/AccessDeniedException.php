<?php

namespace Chronologue\Core\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Debug\ShouldntReport;

class AccessDeniedException extends AuthorizationException implements ShouldntReport
{

}