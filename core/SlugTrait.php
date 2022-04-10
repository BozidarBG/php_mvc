<?php


namespace App\core;


trait SlugTrait
{
    //$value to be converted to slug
    public function slug($value)
    {
        //remove all characters not in this list: underscore | letters | numbers | whitespace
        $value = preg_replace('![^' . preg_quote('_') . '\pL\pN\s]+!u', '', mb_strtolower($value));
        //replace underscore and whitespace with a dash -
        $value = preg_replace('![' . preg_quote('-') . '\s]+!u', '-', $value);
        //remove whitespace
        $value = trim($value, '-');
        $random_number=random_int(1000, 99999);
        return $value.'-'.$random_number;
    }


}