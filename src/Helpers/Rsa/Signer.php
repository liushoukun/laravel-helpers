<?php

namespace Liushoukun\LaravelHelpers\Helpers\Rsa;

class Signer
{
    const ENCODE_POLICY_QUERY = 'QUERY';
    const ENCODE_POLICY_JSON  = 'JSON';
    const KEY_TYPE_PUBLIC     = 1;
    const KEY_TYPE_PRIVATE    = 2;
    public    $publicKey;
    public    $privateKey;
    protected $ignores = [ 'sign', 'sign_type' ];
    protected $sort    = true;


    protected $encodePolicy = self::ENCODE_POLICY_QUERY;

    public function __construct($publicKey = null, $privateKey = null)
    {
        $this->publicKey  = $publicKey;
        $this->privateKey = $privateKey;

    }

    public function signRsa($data)
    {
        $this->unsetKeys($data);
        $this->sort($data);
        $string = $this->getContentToSign($data);
        $sign   = $this->signContentWithRSA($string, $this->privateKey);
        return $sign;
    }

    public function verifyRsa($data)
    {
        $sign = $data['sign'];
        $this->unsetKeys($data);
        $this->sort($data);
        $string = $this->getContentToSign($data);
        return $this->verifyWithRSA($string, $sign, $this->publicKey);
    }

    /**
     * @param $params
     */
    protected function unsetKeys(&$params)
    {
        foreach ($this->getIgnores() as $key) {
            unset($params[$key]);
        }
    }

    /**
     * @return array
     */
    public function getIgnores()
    {
        return $this->ignores;
    }

    /**
     * @param array $ignores
     *
     * @return $this
     */
    public function setIgnores($ignores)
    {
        $this->ignores = $ignores;

        return $this;
    }

    /**
     * @param $params
     */
    protected function sort(&$params)
    {
        ksort($params);
    }

    public function signContentWithRSA($content, $privateKey, $alg = OPENSSL_ALGO_SHA512)
    {
        $privateKey = $this->prefix($privateKey);
        $privateKey = $this->format($privateKey, self::KEY_TYPE_PRIVATE);
        $res        = openssl_pkey_get_private($privateKey);
        $sign       = null;

        try {
            openssl_sign($content, $sign, $res, $alg);
        } catch (Exception $e) {
            if ($e->getCode() == 2) {
                $message = $e->getMessage();
                $message .= "\n 私钥格式有误;";
                throw new \Exception($message, $e->getCode(), $e);
            }
        }

        if (version_compare(8, PHP_VERSION) > 0) {
            openssl_free_key($res);
        }

        $sign = base64_encode($sign);


        return $sign;
    }

    /**
     * Prefix the key path with 'file://'
     *
     * @param $key
     *
     * @return string
     */
    private function prefix($key)
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) != 'WIN' && is_file($key) && substr($key, 0, 7) != 'file://') {
            $key = 'file://' . $key;
        }

        return $key;
    }

    /**
     * Convert key to standard format
     *
     * @param $key
     * @param $type
     *
     * @return string
     */
    public function format($key, $type)
    {
        if (is_file($key)) {
            $key = file_get_contents($key);
        }

        if (is_string($key) && strpos($key, '-----') === false) {
            $key = $this->convertKey($key, $type);
        }

        return $key;
    }

    /**
     * Convert one line key to standard format
     *
     * @param $key
     * @param $type
     *
     * @return string
     */
    public function convertKey($key, $type)
    {
        $lines = [];

        if ($type == self::KEY_TYPE_PUBLIC) {
            $lines[] = '-----BEGIN PUBLIC KEY-----';
        } else {
            $lines[] = '-----BEGIN RSA PRIVATE KEY-----';
        }

        for ($i = 0; $i < strlen($key); $i += 64) {
            $lines[] = trim(substr($key, $i, 64));
        }

        if ($type == self::KEY_TYPE_PUBLIC) {
            $lines[] = '-----END PUBLIC KEY-----';
        } else {
            $lines[] = '-----END RSA PRIVATE KEY-----';
        }

        return implode("\n", $lines);
    }

    public function getContentToSign($params)
    {

        if ($this->encodePolicy == self::ENCODE_POLICY_QUERY) {
            return urldecode(http_build_query($params));
        } elseif ($this->encodePolicy == self::ENCODE_POLICY_JSON) {
            return json_encode($params);
        } else {
            return null;
        }
    }


    public function verifyWithRSA($content, $sign, $publicKey, $alg = OPENSSL_ALGO_SHA512)
    {
        $publicKey = $this->prefix($publicKey);
        $publicKey = $this->format($publicKey, self::KEY_TYPE_PUBLIC);

        $res = openssl_pkey_get_public($publicKey);

        if (!$res) {
            $message = "The public key is invalid ";
            $message .= "公钥格式有误";
            throw new \Exception($message);
        }

        $result = (bool)openssl_verify($content, base64_decode($sign), $res, $alg);
        if (version_compare(8, PHP_VERSION) > 0) {
            openssl_free_key($res);
        }
        return $result;
    }

    public function verifySignature($data)
    {
    }

    private function filter($params)
    {
        return array_filter($params, 'strlen');
    }

}
