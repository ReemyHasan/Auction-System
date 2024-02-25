<?php

namespace App\Exceptions;

use App\Notifications\TelegramNotification;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
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
    protected $internalDontReport = [
        AuthenticationException::class,
        AuthorizationException::class,
        HttpException::class,
        HttpResponseException::class,
        NotFoundHttpException::class,
        ModelNotFoundException::class,
        SuspiciousOperationException::class,
        TokenMismatchException::class,
        ValidationException::class,
    ];
    protected $dontReport = [
        AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        ValidationException::class,
    ];
    protected function shouldntReport($e){
        return in_array(get_class($e),$this->dontReport);
    }
    public function report(Throwable $e)
    {
        if (!$this->shouldntReport($e) && !$this->isHttpException($e)) {
            $message = get_class($e) . "\n" . $e->getMessage();
            Notification::route('telegram', [])->notify(new TelegramNotification($message));

        }

        parent::report($e);
    }

    public function register(): void
    {
    }


    public function render($request, Throwable $e)
    {
        if ($e instanceof ValidationException) {
            return redirect()->back();
        } else if ($e instanceof AuthenticationException) {
            return redirect()->route('login');
        }
        return parent::render($request, $e);
    }
}
