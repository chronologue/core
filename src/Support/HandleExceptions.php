<?php

namespace Chronologue\Core\Support;

use Chronologue\Core\Exceptions\AppException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Inertia\Support\Header;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class HandleExceptions
{
    public function __invoke(Response $response, Throwable $e, Request $request): Response
    {
        if (!$request->header(Header::INERTIA)) {
            return $response;
        }

        if ($e instanceof AppException && in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            return back()
                ->setStatusCode(303)
                ->withErrors([
                    'message' => $this->getMessage($e),
                ]);
        }

        if (!$this->hasDebugModeEnabled() && in_array($response->getStatusCode(), [500, 503, 404, 403])) {
            if ($e instanceof AppException) {
                $response->setStatusCode($e->getStatusCode());
            }
            return inertia($this->errorComponent(), [
                'status' => $response->getStatusCode(),
                'message' => $this->getMessage($e),
            ])
                ->toResponse($request)
                ->setStatusCode($response->getStatusCode());
        }

        return $response;
    }

    protected function errorComponent(): Component
    {
        return new Component('error', 'index');
    }

    private function hasDebugModeEnabled(): bool
    {
        return app()->hasDebugModeEnabled() || app()->environment(['local', 'testing']);
    }

    private function getMessage(Throwable $exception): string
    {
        if ($exception instanceof NotFoundHttpException) {
            $previous = $exception->getPrevious() ?? $exception;

            if ($previous instanceof ModelNotFoundException) {
                return sprintf('%s (%d) not found.',
                    class_basename($previous->getModel()), join(', ', $previous->getIds())
                );
            }
        }

        if ($exception instanceof HttpException) {
            return $exception->getMessage() ?: Response::$statusTexts[$exception->getStatusCode()];
        }

        return $exception->getMessage();
    }
}