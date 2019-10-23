<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait ExceptionTrait
{
    public function apiException($request, $exception){
        if($this->isModel($exception)){
            return $this->exceptionResponse('Not Found', Response::HTTP_NOT_FOUND);
        } elseif($this->isHttp($exception)){
            return $this->exceptionResponse('Rota incorreta', Response::HTTP_NOT_FOUND);
        } else {
            return parent::render($request, $exception);
        }
    }

    protected function isModel($exception)
    {
        return $exception instanceof ModelNotFoundException;
    }

    protected function isHttp($exception)
    {
        return $exception instanceof NotFoundHttpException;
    }

    protected function exceptionResponse(string $message, int $code)
    {
        return response()->json([
            'errors' => $message
        ], $code);
    }
}
