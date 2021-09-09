<?php
// +----------------------------------------------------------------------
// | When work is a pleasure, life is a joy!
// +----------------------------------------------------------------------
// | User: shook Liu  |  Email:24147287@qq.com  | Time: 2018/8/29/029 13:52
// +----------------------------------------------------------------------
// | TITLE: todo?
// +----------------------------------------------------------------------

namespace Liushoukun\LaravelHelpers\Exceptions;


use RuntimeException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;


abstract class AppRuntimeException extends RuntimeException implements HttpExceptionInterface, ExceptionCodePrefixInterface
{

    protected     $codePrefix;
    protected     $errors;
    protected     $status_code;
    protected     $headers;
    protected     $data;
    public static $errorList = [];


    /**
     * @param int $code
     * @param string $message
     * @param array $errors
     * @param int $status_code
     * @param array $headers
     * @param array $data
     */
    public function __construct(int $code = 1, $message = 'error', $errors = [], int $status_code = 400, array $headers = [], array $data = [])
    {
        $code = sprintf("%03d", $code);

        $code              = (string)($this->getCodePrefix() . $code);
        $this->status_code = $status_code;
        $this->code        = (int)($code);
        $this->message     = $this->getDefaultMessage($code, $message);
        $this->errors      = $errors;
        $this->headers     = $headers;
        $this->data        = $data;
    }


    public function getDefaultMessage($code, $message = '')
    {

        if (filled($message)) {
            return $message;
        }
        return self::$errorList[$code] ?? ($this->message ?? '');
    }


    public function getStatusCode()
    {
        return $this->status_code;
    }

    public function getErrors()
    {
        return count($this->errors) > 0 ? $this->errors : null;
    }

    public function getData()
    {
        return $this->data;
    }

    public function errors()
    {
        return count($this->errors) > 0 ? $this->errors : null;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param int $status_code
     */
    public function setStatusCode(int $status_code) : void
    {
        $this->status_code = $status_code;
    }


}
