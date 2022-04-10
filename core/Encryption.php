<?php

namespace App\core;

class Encryption
{
    private static $key='vdls3E1e2oirjvknmv4';

    public static function encryptData($data)
    {

        $encryption_key = base64_decode(self::$key);
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = openssl_encrypt($data, 'aes-256-cbc', $encryption_key, 0, $iv);
        return base64_encode($encrypted . '::' . $iv);
    }
    public static function decryptData($data) {
        $encryption_key = base64_decode(self::$key);
        list($encrypted_data, $iv) = array_pad(explode('::', base64_decode($data), 2),2,null);
        return openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $iv);
    }


}