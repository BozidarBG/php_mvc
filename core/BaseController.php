<?php


namespace App\Core;


class BaseController
{


    public function setSessionMessage(string $title="success", $message){
        Session::add($title, $message);
    }


    /**
     * Require a view.
     *
     * @param  string $name
     * @param  array  $data
     */
    public function view($name, $data = [])
    {
        extract($data);
        return require "../app/views/{$name}.view.php";
    }


    public function setOld(array $params)
    {
        foreach ($params as $key => $value) {
            if(trim($value) != "" || trim($value) != null){

                Session::add($key, $value);
            }
        }
    }

    public function clearOld(array $params)
    {
        foreach ($params as $key => $value) {
            if(trim($value) != "" ){
                Session::destroy($key, $value);
            }
        }
    }


}
