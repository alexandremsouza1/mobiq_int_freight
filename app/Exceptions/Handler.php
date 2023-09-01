<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Client\RequestException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{

    protected $details;
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
        return $this->handleApiException($request, $exception);
    }

    protected function handleApiException($request, Throwable $exception)
    {
        $statusCode = $this->getStatusCodeFromException($exception);
        $message = $this->getMessageFromException($exception);


        $responseData = [
            'details' => $this->details,
            'message' => $message,
            'status' => false
        ];

        $this->log($exception);

        return new JsonResponse($responseData, $statusCode);
    }

    protected function getStatusCodeFromException(Throwable $exception): int
    {
        if ($exception instanceof RequestException) {
            // Exceção do GuzzleHttp
            return $exception->getCode();
        } elseif ($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
            // Exceção do Laravel
            return $exception->getStatusCode();
        } else {
            // Outros casos de exceção
            return Response::HTTP_INTERNAL_SERVER_ERROR;
        }
    }

    protected function getMessageFromException(Throwable $exception): string
    {
        return $exception->getMessage() ?? '';
    }

        /**
     * Generate a log on server, and send a notification to admin
     *
     * @param HttpException $exception
     * @param string    $message
     * @param null      $context
     */
    private function log(Throwable $exception, $message = '', $context = null): void
    {
        try {
            $logMessage = [
                'ERROR_MSG' => $exception->getMessage(),
                'CONTEXT' => $context,
                'FILE' => $exception->getFile(),
                'LINE' => $exception->getLine(),
                'CODE' =>  $this->getStatusCodeFromException($exception),
                'MESSAGE' => $message,
                'TRACE' => $exception->getTrace(),
            ];

            Log::error(json_encode($logMessage));
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
    }
}
