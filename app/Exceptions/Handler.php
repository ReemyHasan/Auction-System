<?php

namespace App\Exceptions;

use App\Notifications\TelegramNotification;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Notification;
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
    if ($e instanceof \Illuminate\Auth\AuthenticationException) {
        return redirect()->route('login')->with("error", 'unauthenticated');
    }

    Notification::route('telegram', [])->notify(new TelegramNotification($e->getMessage()));

    return redirect()->back()->with("error", $e);
}
}
