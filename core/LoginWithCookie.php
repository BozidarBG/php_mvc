<?php

namespace App\core;

use App\app\models\User;
use \App\Core\Session;

class LoginWithCookie
{
    public static function LoginWithCookie(){
        //vardump('ovde');
        //logmsg($_SESSION);
        //if user is not logged we check if session has that he doesnt have remember me in cookie
        //so that we dont get cookie in every request of not-logged-in user
        if(!isset($_SESSION['authenticated_user_id'])){
            if(Session::has('user_doesnt_have_remember_me_token') && Session::get('user_doesnt_have_remember_me_token')===true){
                return true;
            }else{
                //user is not logged in so we try to log him through cookie if exists
                $remember_token = Cookie::get(getAppValue('remember_me_cookie_name'));
                
                if($remember_token){
                    //token exists in cookie so we try to login this user
                    $model=new User();
                    
                    $user = $model->select([])->where(['remember_token', Encryption::decryptData($remember_token)])->limit(1)->get('first');
                    if($user){
                        Session::add('authenticated_user_id', $user->id);
                        Session::add('authenticated_user_object', $user);
                    }//else there is no user with this token so it may be hacker or my bad code
                }
                else{
                    //user is not logged in and he doesn't have remember me token in cookie, so that we dont check cookie in every request
                    Session::add('user_doesnt_have_remember_me_token', true);

                }
            }

            //user is logged in
        }
        return true;
    }
}