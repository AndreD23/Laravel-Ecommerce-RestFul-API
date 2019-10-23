<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait ExceptionTrait
{
    /**
     * Verifica o tipo de erro para fazer o tratamento correto
     * @param $request
     * @param $exception
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|Response
     */
    public function apiException($request, $exception){
        if($this->isModel($exception)){
            return $this->exceptionResponse('Not Found', Response::HTTP_NOT_FOUND);
        } elseif($this->isHttp($exception)){
            return $this->exceptionResponse('Rota incorreta', Response::HTTP_NOT_FOUND);
        } else {
            return parent::render($request, $exception);
        }
    }

    /**
     * Verifica se o erro é do tipo de objeto Model não encontrado
     * @param $exception
     * @return bool
     */
    protected function isModel($exception)
    {
        return $exception instanceof ModelNotFoundException;
    }

    /**
     * Verifica se o erro é do tipo de rota não encontrada
     * @param $exception
     * @return bool
     */
    protected function isHttp($exception)
    {
        return $exception instanceof NotFoundHttpException;
    }

    /**
     * Monta a mensagem de retorno de erro
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function exceptionResponse(string $message, int $code)
    {
        return response()->json([
            'errors' => $message
        ], $code);
    }
}
