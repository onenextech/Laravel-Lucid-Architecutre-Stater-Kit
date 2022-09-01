<?php

namespace App\Exceptions;

use App\Helpers\JsonResponder;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Exceptions\UnauthorizedException as PermissionAuthorizedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
        UnauthorizedException::class,
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $throwable) {
        });
    }

    public function render($request, Throwable $exception)
    {
        if (! Request::ajax()) {
            return parent::render($request, $exception);
        }

        $exceptionClass = get_class($exception);
        $message = $exception->getMessage();

        switch ($exceptionClass) {
            case NotFoundHttpException::class:
                return JsonResponder::notFound('Route Not Found');

            case ModelNotFoundException::class:
                return JsonResponder::notFound('The resource is not found');

            case UnauthorizedException::class:
            case PermissionAuthorizedException::class:
                return JsonResponder::unauthorized($message);

            case ValidationException::class:
                return JsonResponder::validationError('Validation Failed', $exception->errors());

            case AuthenticationException::class:
                return JsonResponder::unauthorized('Unauthenticated');

            default:
                info($exception);

                return JsonResponder::internalServerError();
        }
    }
}
