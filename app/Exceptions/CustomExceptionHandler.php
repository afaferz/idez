<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\Response;

class CustomExceptionHandler extends ExceptionHandler
{
    protected $dontReport = [];

    public function render($request, \Throwable $exception)
    {
        if ($request->expectsJson()) {
            $statusCode = $this->getExceptionStatusCode($exception);
            $responseData = [
                'error' => [
                    'message' => $exception->getMessage(),
                    'status_code' => $statusCode,
                ],
            ];

            return response()->json($responseData, $statusCode);
        }

        return parent::render($request, $exception);
    }

    private function getExceptionStatusCode($exception)
    {
        if ($exception instanceof \Illuminate\Http\Exceptions\HttpResponseException) {
            return $exception->getResponse()->getStatusCode();
        }

        return method_exists($exception, 'getStatusCode')
            ? $exception->getStatusCode()
            : Response::HTTP_INTERNAL_SERVER_ERROR;
    }
}