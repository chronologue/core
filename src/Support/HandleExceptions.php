<?php

namespace Chronologue\Core\Support;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class HandleExceptions
{
    public function __invoke(Response $response, Throwable $e, Request $request): Response
    {
        if (!$this->hasDebugModeEnabled() && in_array($response->getStatusCode(), [500, 503, 404, 403])) {
            return inertia($this->errorComponent(), [
                'status' => $response->getStatusCode(),
                'message' => $this->getMessage($e),
                'errors' => Inertia::always([
                    'message' => $this->getMessage($e),
                ]),
            ])
                ->toResponse($request)
                ->setStatusCode($response->getStatusCode());
        } else if ($response->getStatusCode() === 419) {
            return back()
                ->setStatusCode(303)
                ->withErrors([
                    'message' => 'The page expired, please try again.',
                ]);
        }

        if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            if ($response->getStatusCode() === 302 && $request->method() !== 'POST') {
                $response->setStatusCode(303);
            }
            if ((!$request->hasSession() || !$request->session()->has('errors')) &&
                $response instanceof RedirectResponse) {
                $response->withErrors([
                    'message' => $this->getMessage($e),
                ]);
            }
        }

        return $response;
    }

    protected function errorComponent(): Component
    {
        return new Component('error', 'index');
    }

    protected function hasDebugModeEnabled(): bool
    {
        return app()->hasDebugModeEnabled() || app()->environment(['local', 'testing']);
    }

    protected function getMessage(Throwable $exception): string
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