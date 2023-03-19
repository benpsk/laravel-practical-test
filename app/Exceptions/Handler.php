<?php

namespace App\Exceptions;

use App\Api\Exceptions\FatalErrorException;
use App\Api\Exceptions\NotFoundException;
use App\Api\Exceptions\ValidationException;
use App\Api\Exceptions\WrongCredentialException;
use App\Api\Service\Formatter;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException as ValidationValidationException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
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

        $this->reportable(function (Throwable $exception) {
        });

        $this->renderable(function (Throwable $exception, Request $request) {

            if (!config('app.debug')) {
                if ($exception instanceof \PDOException) {
                    logger()->error($exception);
                    return response(
                        [
                            'title' => 'Service Temporarily Unavailable!',
                            'description' => 'We are experiencing technical difficulties. We will return shortly.'
                        ],
                        503
                    );
                }
            }
            if ($request->wantsJson()) {
                $message = $exception->getMessage();

                $code = $exception->getCode();

                if ($exception instanceof NotFoundException) {
                    $code = 404;
                    $message = "You requested route not found.";
                } elseif ($exception instanceof ValidationException) {
                    $code = 400;
                } elseif ($exception instanceof MethodNotAllowedException) {
                    $code = 405;
                } elseif ($exception instanceof ValidationValidationException) {
                    $message = json_encode($exception->errors());
                } elseif ($exception instanceof FatalErrorException) {
                    if (!config('app.debug')) {
                        $message = 'Internal Server Error.';
                    }
                }

                return $this->handle($message, $code, $exception);
            }
        });
    }

    /**
     * Handle the exception for json response
     *
     * @param $message
     * @param $code
     * @param $exceptions
     * @return JsonResponse|object
     */
    private function handle($message, $code, $exceptions)
    {
        $error = Formatter::factory()->makeErrorException($message, $code);

        if ($code === 403 || $code === 401) {
            if (!$exceptions instanceof WrongCredentialException) {
                return response()->json($error)->setStatusCode($code);
            }
        } elseif ($code !== 400) {
            logger()->debug($message);
        }

        return response()->json($error);
    }

}
