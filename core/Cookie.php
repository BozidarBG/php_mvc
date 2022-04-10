<?php

namespace App\core;

class Cookie
{
    public static function set(string $name, string $value, int $expire,
       string $path="/", string $domain=null, bool $secure=false, bool $httponly=false){
        setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
    }

    public static function get(string $key){
        if(isset($_COOKIE[$key])){
            return $_COOKIE[$key];
        }else{
            return false;
        }
    }

}