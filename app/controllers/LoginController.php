<?php
namespace App\app\controllers;

use App\app\Middleware;
use App\app\models\User;
use App\app\models\Setting;
use App\Core\App;
use App\Core\BaseController;
use App\Core\Csrf;
use App\core\Encryption;
use App\Core\Request;
use App\Core\Session;
use App\Core\Validator;
use App\Core\Log;
use App\core\Cookie;

class LoginController extends BaseController
{
    private $data_arr=[];
    private $user;
    private $userModel;
    public $settings;

    public function __construct()
    {
        $this->settings=Setting::getSettings();

    }

    public function showLoginForm()
    {
        if (!isLoggedIn()) {
            //$settings=Setting::getSettings();
            return $this->view('auth/login', ['settings'=>$this->settings]);
        }
        return redirect('');
    }



    public function login(){
        Middleware::csrf();
        $this->data_arr = Request::getParams();
        $this->userModel = new User;
        $this->setOld($this->data_arr);
        //do validation for other input fields
        $validation = new Validator($this->data_arr, $this->userModel->login_rules);
        $db_errors=[];
        $errors = $validation->areThereErrors();//false or array of key value errors
        //if no errors, we can try to login user
        if (!$errors) {
            $this->user = $this->userModel->select([])->where(['email', $this->data_arr['email']])->limit(1)->get('first'); //
            if ($this->user) {
                if (password_verify($this->data_arr['password'], $this->user->password)) {
                    //check if user has confirmed his email. if not, return to page where it says that he should check mail
                    if(!is_null($this->user->verify_token)){
                        return $this->view('auth/after-register');
                    }
                    //check if remember me is clicked. if it is, make token, put it in DB,
                    //put token in cookie
                    $remember_token=$this->checkIfUserWantsToLoginWithToken();
                    //put new token into session authenticated_user_object
                    if($remember_token){
                        $this->user->remember_token=$remember_token;
                    }
                    $this->clearOld($this->data_arr);
                    Session::add('authenticated_user_id', $this->user->id);
                    Session::add('authenticated_user_object', $this->user);
                    return redirectToHome();
                }
                //password is wrong
            } 
        }
        //which ever error we have, we will return that the credentials are wrong
        $db_errors['email'] = ['Your credentials are wrong'];
        Session::add('login_errors', $db_errors);
        return redirectBack();
    }

    //if he watns, update user, put remember_token in db
    protected function checkIfUserWantsToLoginWithToken(){
        if(isset($this->data_arr['remember'])){
            //user wants to login with token next time
            $randomToken = createRandomToken(60);
            $update=['remember_token'=>$randomToken];
            $this->userModel->update('id', $this->user->id, $update);
            Cookie::set(getAppValue('remember_me_cookie_name'), Encryption::encryptData($randomToken), time()+getAppValue('remember_me_duration'));
            return $randomToken;
        }
        return false;
    }

    public function logout()
    {
        Middleware::csrf();
        if (isLoggedIn()) {
            $user=Session::has('authenticated_user_object') ? Session::get('authenticated_user_object') : null;
            if(!is_null($user->remember_token)){
                $update=['remember_token'=>null];
                $this->userModel=new User;
                $this->userModel->update('id', $user->id, $update);
                Cookie::set(getAppValue('remember_me_cookie_name'), '', -1);
            }
            Session::destroy('authenticated_user_id');
            Session::destroy('authenticated_user_object');
            Session::destroy('token');

            session_destroy();
        }

        return redirectToHome();
    }

}
