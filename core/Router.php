<?php

namespace App\Core;

use App\app\RunWithEveryRequest;
use App\core\LoginWithCookie;

class Router
{

    /**
     * All registered routes.
     *
     * @var array
     */
    public $routes = [
        'GET' => [],
        'POST' => []
    ];

    protected $id;
    protected $slug;
    //protected $query_string;

    /**
     * Load a user's routes file.
     *
     * @param string $file
     */
    public static function load($file)
    {
        $router = new static;

        require $file;

        return $router;
    }

    /**
     * Register a GET route.
     *
     * @param string $uri
     * @param string $controller
     */
    public function get($uri, $controller)
    {
        $this->routes['GET'][$uri] = $controller;
    }

    /**
     * Register a POST route.
     *
     * @param string $uri
     * @param string $controller
     */
    public function post($uri, $controller)
    {
        $this->routes['POST'][$uri] = $controller;
    }

    /**
     * Load the requested URI's associated controller method.
     *
     * @param string $uri
     * @param string $requestType - GET or POST
     */

    //we get uri. then split it in parts and examine what the parts are
    /*
       /users
    /user/54 or /user/slug
    /add-user
    /update-user/54

    /sport/
    /sport/novak-pobedio
    /add-news
    /update-news/novak-pobedio


     */
    public function direct($uri, $requestType)
    {

        $new_uri=$this->checkUriParts($uri);
       //vardump($new_uri);

        if (array_key_exists($new_uri, $this->routes[$requestType])) {

            return $this->callAction(
                ...explode('@', $this->routes[$requestType][$new_uri])
            );
        }

       // throw new \Exception("No route defined for this URI. {$new_uri}");
        return redirectToHome();
    }
    //samo upisuje u this-> id, slug i query string??????
    private function checkUriParts($uri){
        $new_uri='';
        //1. da li je uri samo '', ako jeste onda je to root i id, slug i qs su null
        if($uri===''){
            return $new_uri;
            //2. znači nije, imamo ili /posts ili posts/561 ili posts/novak-pobedio ili posts?page=5 ali $uri je bez početne i krajnje /
            //2.1  ako u sebi ima / onda ima ili id ili slug
        }elseif(preg_match('/\//', $uri)){

            //2.1.1 radimo explode. ako je drugi član niza broj, onda imamo this->id a ako je reč, onda imamo this->slug
            $parts_arr=explode('/', $uri);
            if(is_numeric($parts_arr[1])){
                $this->id=$parts_arr[1];
                $new_uri=$parts_arr[0].'/{id}';
                //vardump('id'$this->id);
            }else{
                $this->slug=$parts_arr[1];
                $new_uri=$parts_arr[0].'/{slug}';
               //vardump($this->slug);
                //vardump($new_uri);
            }

            //2.2 ako ima u sebi ? onda ima query string. odna je this->query_string ono što ide posle ?
        }elseif(preg_match('/\?/', $uri)){
            $new_uri=$this->stripQueryString($uri);

            //2.3 ako nema ni / ni ? onda je samo stranica. id, slug i qs ostaju null
        }else{
            $new_uri=$uri;
        }

        return $new_uri;

    }

    private function stripQueryString($uri){

            $parts = explode('?', $uri);
            //$this->query_string=$parts[1];

        return $parts[0];
    }

    /**
     * Load and call the relevant controller action.
     *
     * @param string $controller
     * @param string $action
     */
    protected function callAction($controller, $action)
    {
        //LoginWithCookie::LoginWithCookie();
        new RunWithEveryRequest();
        $controller = "App\\app\\controllers\\{$controller}";
        $controller = new $controller;

        if (! method_exists($controller, $action)) {
            throw new \Exception(
                "{$controller} does not respond to the {$action} action."
            );
        }

        //vardump('id je '.$this->id.' slug je '.$this->slug. ' query string je '.$this->query_string);

        //ako je id, šaljemo id i vrednost a ako je slug, šaljemo slug i vrednost, ako imamo query string. ako nema, onda šaljemo null
        if($this->id){
            return $controller->$action($this->id);
        }elseif($this->slug){
            return $controller->$action($this->slug);
        }else{
            return $controller->$action();
        }

    }
}
