<?php

namespace Chronologue\Core\Exceptions;

use Chronologue\Core\Support\Component;
use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ExceptionsHandler
{
    private ?Closure $callback;

    public function __construct(?Closure $callback = null)
    {
        $this->callback = $callback;
    }

    public function __invoke(Exceptions $exceptions): void
    {
        $this->handleReport($exceptions);
        $this->handleMap($exceptions);
        $this->handleRespond($exceptions);

        // Execute the callback if provided
        if ($this->callback) {
            call_user_func($this->callback, $exceptions);
        }
    }

    /*
     * Handle the response for exceptions
     */
    protected function handleRespond(Exceptions $exceptions): void
    {
        $exceptions->respond(function (Response $response, Throwable $e, Request $request) {
            if (!app()->environment(['local', 'testing']) &&
                in_array($response->getStatusCode(), [500, 503, 404, 403])) {
                return inertia(new Component('error', 'index'), [
                    'status' => $response->getStatusCode(),
                    'message' => Response::$statusTexts[$response->getStatusCode()],
                ])
                    ->toResponse($request)
                    ->setStatusCode($response->getStatusCode());
            } else if ($response->getStatusCode() === 419) {
                return redirect(url()->previous())->withErrors([
                    'message' => 'The page expired, please try again.',
                ]);
            }
            return $response;
        });
    }

    /*
     * Map specific exceptions to ApplicationException
     */
    protected function handleMap(Exceptions $exceptions): void
    {
        $exceptions->map(fn(AuthenticationException $exception) => ApplicationException::from($exception));
        $exceptions->map(fn(ModelNotFoundException $exception) => ApplicationException::from($exception));
        $exceptions->map(fn(AuthorizationException $exception) => ApplicationException::from($exception));
    }

    /*
     * Only report exceptions outside of local and testing environments
     */
    protected function handleReport(Exceptions $exceptions): void
    {
        $exceptions->report(fn(Throwable $exception) => !app()->environment(['local', 'testing']));
    }

}