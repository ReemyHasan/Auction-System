<?php

namespace App\Exceptions;

use App\Notifications\TelegramNotification;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $internalDontReport = [
        AuthenticationException::class,
        AuthorizationException::class,
        HttpException::class,
        HttpResponseException::class,
        ModelNotFoundException::class,
        SuspiciousOperationException::class,
        TokenMismatchException::class,
        ValidationException::class,
    ];

    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if ($this->shouldntReport($e)) {
            return;
        }
        if ($e instanceof \Illuminate\Auth\AuthenticationException) {
            return redirect()->route('login')->with("error", 'Unauthenticated');
        }

        // if ($e instanceof \Illuminate\Routing\Exceptions\RouteNotFoundException) {
        //     abort(404, 'Not Found');
        // }
        // if ($e instanceof AuthorizationException) {
        //     abort(403, $e->getMessage());
        // }
        if (
            !$this->isHttpException($e) && !($e instanceof AuthorizationException )
        ) {
            Notification::route('telegram', [])->notify(new TelegramNotification($e->getMessage()));
        }

        return parent::render($request, $e);
    }
}
