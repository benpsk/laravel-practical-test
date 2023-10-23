<?php

namespace App\Exceptions;

use App\Api\Exceptions\FatalErrorException;
use App\Api\Exceptions\NotFoundException;
use App\Api\Exceptions\UnauthorizedException;
use App\Api\Exceptions\ValidationException;
use App\Api\Exceptions\WrongCredentialException;
use App\Api\Service\Formatter;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException as ValidationValidationException;
use PDOException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function (MethodNotAllowedHttpException $e) {
            return $this->handle($e->getMessage(), 405);
        });

        $this->renderable(function (UnauthorizedException $e) {
            return $this->handle($e->getMessage(), 403);
        });

        $this->renderable(function (NotFoundException $e) {
            return $this->handle($e->getMessage(), 404);
        });

        $this->renderable(function (AuthenticationException $e) {
            return $this->handle($e->getMessage(), 401);
        });
        $this->renderable(function (ValidationException $e) {
            return $this->handle($e->getMessage(), 400);
        });
        $this->renderable(function (MethodNotAllowedException $e) {
            return $this->handle($e->getMessage(), 405);
        });

        $this->renderable(function (ValidationValidationException $e) {
            return $this->handle(json_encode($e->errors()), 422);
        });
        $this->renderable(function (WrongCredentialException $e) {
            return $this->handle($e->getMessage(), 401);
        });

        $this->renderable(function (FatalErrorException $e) {
            return $this->handle($e->getMessage(), 500);
        });

        $this->renderable(function (PDOException $e) {
            logger()->debug($e->getMessage());
            return response(
                [
                    'title' => 'Service Temporarily Unavailable!',
                    'description' => 'We are experiencing technical difficulties. We will return shortly.'
                ],
                503
            );
        });


        $this->renderable(function (Throwable $e) {
            return $this->handle($e->getMessage(), 500);
        });
    }
    private function handle(string $message, int $code): bool|JsonResponse
    {
        if (request()->wantsJson()) {
            $error = Formatter::factory()->makeErrorException($message, $code);
            logger()->debug($message);

            return response()->json($error)->setStatusCode($code);
        }
        return false;
    }
}
