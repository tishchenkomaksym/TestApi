<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
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
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        $rendered = parent::render($request, $exception);

        if ($exception instanceof ValidationException) {
            $json = [
                'error' => $exception->validator->errors(),
                'status_code' => $rendered->getStatusCode()
            ];
        } elseif ($exception instanceof AuthorizationException) {
            $json = [
                'error' => 'You are not allowed to do this action.',
                'status_code' => 403
            ];
        }
        else {
            // Default to vague error to avoid revealing sensitive information
            $json = [
                'error' => (app()->environment() !== 'production')
                    ? $exception->getMessage()
                    : 'An error has occurred.',
                'status_code' => $exception->getCode()
            ];
        }

        return response()->json($json, $rendered->getStatusCode());
    }
}
