<?php

namespace Liushoukun\LaravelHelpers\Http;

use Illuminate\Http\JsonResponse;

trait ResponseJson
{

    /**
     * @param array $data
     * @param string $message
     * @param int $status_code
     * @return JsonResponse
     */
    public function success(array $data = [], string $message = 'success', int $status_code = 200)
    {
        return self::responseJson(0, $message, $status_code, $data, []);
    }

    /**
     * @param int $code
     * @param string $message
     * @param int $status_code
     * @param array|null $data
     * @param array $errors
     * @return JsonResponse
     */
    protected static function responseJson(int $code, string $message, int $status_code, array $data = null, array $errors = [])
    {
        $data = [
            'code'        => $code,
            'message'     => $message,
            'data'        => $data,
            'time'        => microtime(true) - LARAVEL_START,
            'errors'      => $errors,
            'status_code' => $status_code,

        ];
        return response()->json($data, $status_code);
    }

    /**
     * @param string $message
     * @param int $code
     * @param int $status_code
     * @param array $errors
     * @param array|null $data
     * @return JsonResponse
     */
    public function error(string $message = 'error', int $code = 1, int $status_code = 200, array $errors = [], array $data = null)
    {
        return self::responseJson($code, $message, $status_code, $data, $errors);
    }
}
