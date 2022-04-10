<?php

namespace App\Core;


class Session
{

    public static function add(string $key, $value){
        if(!empty($key) && $key !='' && !empty($value) && $value !=''){
            $_SESSION[$key]=$value;
        }else{
            throw new \Exception('Key and value are required');
        }

    }

    public static function has(string $key) :bool {
        if(!empty($key) && $key !=''){
            return isset($_SESSION[$key]);
        }
        throw new \Exception('Key is required');
    }

    public static function destroy(string $key){
        if(self::has($key)){
            unset($_SESSION[$key]);
        }

    }

    public static function get(string $key){
        if(self::has($key)){
            return $_SESSION[$key];
        }else{
            throw new \Exception('Key get is required');
        }
    }

    public static function flash($key){
        if(self::has($key)){
            $value=self::get($key);
            self::destroy($key);
            return $value;

        }else{
            return null;
        }
    }



}