<?php

namespace App\app;

use App\Core\Csrf;
use App\Core\Log;

class Middleware
{

    public static function auth(){
        if(!isLoggedIn()){
            return redirectToHome();
        }
    }

    public static function admin(){
        $val=getAuthUserModel()->role;
        if(is_numeric($val)){
            $val=(int) $val;
        }else{
            return redirectToHome();
        }

        if(!isLoggedIn() ||  $val !== 1){
            return redirectToHome();
        }
    }

    public static function csrf(){
      if (Csrf::verifyCSRFToken($_POST['_token'], false)) {
        return true;
      }else{
        return redirectToHome();
      }
    }

}
