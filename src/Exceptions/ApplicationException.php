<?php

namespace Chronologue\Core\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Debug\ShouldntReport;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Renderer\Renderer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Support\Header;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

class ApplicationException extends RuntimeException implements Responsable, ShouldntReport
{
    protected int $statusCode;

    public function __construct(int $statusCode, string $message, ?Exception $previous = null)
    {
        parent::__construct($message, 0, $previous);
        $this->statusCode = $statusCode;
    }

    final public static function from(Exception $exception): static
    {
        if ($exception instanceof ModelNotFoundException) {
            return new static(
                Response::HTTP_NOT_FOUND,
                sprintf('Resource (%s) not found.', implode(', ', $exception->getIds())),
                $exception
            );
        } else if ($exception instanceof AuthorizationException) {
            return new static(Response::HTTP_FORBIDDEN, $exception->getMessage(), $exception);
        } else if ($exception instanceof AuthenticationException) {
            return new static(Response::HTTP_UNAUTHORIZED, $exception->getMessage(), $exception);
        }

        // For any other exception, return a generic internal server error
        return new static(Response::HTTP_INTERNAL_SERVER_ERROR, $exception->getMessage(), $exception);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @param Request $request
     */
    public function toResponse($request): JsonResponse|RedirectResponse|Response
    {
        // If the request expects a JSON response, return a JSON response
        if ($request->expectsJson()) {
            return response()->json(['message' => $this->getMessage()], $this->statusCode);
        }

        // If the request is a form submission (POST, PUT, PATCH, DELETE), redirect back with errors
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            return redirect(url()->previous())->withErrors(['message' => $this->getMessage()]);
        }

        // Handle authentication exception and redirect user appropriately
        $previous = $this->getPrevious();
        if ($previous instanceof AuthenticationException) {
            $location = $previous->redirectTo($request) ?? route('login');

            // If the request expects an Inertia response, return a response with a Location header
            if ($request->header(Header::INERTIA)) {
                return response('', Response::HTTP_CONFLICT, [Header::LOCATION => $location]);
            }

            // Otherwise, redirect the user to the login page
            return redirect()->guest($location);
        }

        // Default response for other exceptions
        $content = app(Renderer::class)->render($request, $this);
        return response($content, $this->statusCode)->withException($this);
    }
}
