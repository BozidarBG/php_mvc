<?php


namespace App\Core;


class Log
{
    protected static $file='log.txt';

    public static function add($content){
        $date=new \DateTime('now');

        $details=sprintf("%s message: %s", $date->format('d.m.Y H:i:s'), toJson($content).PHP_EOL);
        file_put_contents(static::$file, $details, FILE_APPEND);
    }


}