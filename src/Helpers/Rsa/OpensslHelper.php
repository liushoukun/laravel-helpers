<?php

namespace Liushoukun\LaravelHelpers\Helpers\Rsa;
class OpensslHelper
{
    /**
     * 生成密钥对
     * @return array
     */
    public static function generate()
    {
        $config  = array (
            "digest_alg"       => "sha512",
            "private_key_bits" => 2048,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        );
        $private = openssl_pkey_new($config);
        openssl_pkey_export($private, $private_key);
        $public_key = openssl_pkey_get_details($private)['key'];
        return [ 'public_key' => $public_key, 'private_key' => $private_key ];
    }


    public function sign($data)
    {

    }

}
