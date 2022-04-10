<?php


namespace App\Core;


class Csrf
{

    public static function token() :string
    {
        if(!Session::has('token')){
            $randomToken = base64_encode(openssl_random_pseudo_bytes(32));
            Session::add('token', $randomToken);
        }
        return Session::get('token');
    }


    public static function verifyCSRFToken(string $requestToken, $regenerate = true) : bool
    {
        if(Session::has('token') && Session::get('token') === $requestToken){
            if($regenerate){
                Session::destroy('token');
            }
            return true;
        }
        return false;
    }
}