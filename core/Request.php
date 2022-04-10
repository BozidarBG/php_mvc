<?php

namespace App\Core;

class Request
{

    /**
     * Fetch the request URI.
     *
     * @return string
     */
    public static function uri()
    {
        return trim(rawurldecode($_SERVER['REQUEST_URI']),'/');

    }

    /**
     * Fetch the request method.
     *
     * @return string
     */
    public static function method()
    {

        return $_SERVER['REQUEST_METHOD'];
    }

    public static function getParams(){

        if(self::method() == 'GET'){
            return $_GET;
        }elseif(self::method() == 'POST'){
            return $_POST;
        }else{
            throw new \Exception('Method not allowed!');
        }
    }


}
