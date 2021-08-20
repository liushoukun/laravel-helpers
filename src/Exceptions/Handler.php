<?php

namespace Liushoukun\LaravelHelpers\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\ViewErrorBag;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        AppRuntimeException::class
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


    protected function convertExceptionToArray(Throwable $e)
    {
        $data            = [];
        $data['code']    = $e->getCode();
        $data['message'] = $this->isHttpException($e) ? $e->getMessage() : '服务器异常';
        if ($e instanceof AppRuntimeException) {
            $data['message'] = $e->getMessage();
        }
        if ($e instanceof ValidationException) {
            $data['message'] = Arr::get(Arr::first(array_values($e->errors())), 0) ?? $e->getMessage();
        }
        if (method_exists($e, 'errors')) {
            $data['errors'] = $e->errors();
        }

        if (config('app.debug')) {
            $data['message']   = $e->getMessage();
            $data['file']      = $e->getFile();
            $data['line']      = $e->getLine();
            $data['trace']     = collect($e->getTrace())->map(function ($trace) {
                return Arr::except($trace, [ 'args' ]);
            })->all();
            $data['exception'] = get_class($e);
        }
        if (method_exists($e, 'data')) {
            $data['data'] = $e->getData();
        } else {
            $data['data'] = null;
        }
        $data['time'] = bcsub(microtime(true), LARAVEL_START, 5);
        return $data;


    }

    /**
     * Convert a validation exception into a JSON response.
     *
     * @param Request $request
     * @param ValidationException $exception
     * @return JsonResponse
     */
    protected function invalidJson($request, ValidationException $exception)
    {
        return new JsonResponse(
            $this->convertExceptionToArray($exception),
            $this->isHttpException($exception) ? $exception->getStatusCode() : $exception->status,
            $this->isHttpException($exception) ? $exception->getHeaders() : [],
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        );

    }

    protected function renderHttpException(HttpExceptionInterface $e)
    {
        $this->registerErrorViewPaths();
        if ($e instanceof AppRuntimeException && !config('app.debug')) {
            return response()->view('errors::error', [
                'errors'    => new ViewErrorBag,
                'exception' => $e,
            ],                      $e->getStatusCode(), $e->getHeaders());
        }
        // 如果定义了指定错误页面 那么就一定返回错误页面
        if (view()->exists($view = $this->getHttpExceptionView($e))) {
            return response()->view($view, [
                'errors'    => new ViewErrorBag,
                'exception' => $e,
            ],                      $e->getStatusCode(), $e->getHeaders());
        }

        return $this->convertExceptionToResponse($e);
    }


}
