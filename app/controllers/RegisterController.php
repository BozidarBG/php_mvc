<?php


namespace App\app\controllers;

use App\app\Middleware;
use App\app\mails\AuthenticationMail;
use App\app\models\Setting;
use App\app\models\User;
//use App\app\models\Setting;
use App\Core\App;
use App\Core\BaseController;
use App\Core\Csrf;
use App\core\Encryption;
use App\Core\Request;
use App\Core\Session;
use App\Core\Validator;
use App\Core\Log;

class RegisterController extends BaseController
{

    public $settings;

    public function __construct()
    {
        $this->settings=Setting::getSettings();

    }


    public function showRegisterForm()
    {
        if (!isLoggedIn()) {
            return $this->view('auth/register', ['settings'=>$this->settings]);
        }
        return redirect('');
    }

//Bo123456/* for new accounts
    public function register()
    {
        Middleware::csrf();
        $data_arr = Request::getParams();

        //insert into session old inputs
        $this->setOld($data_arr);
        $model = new User;
        //do validation for other input fields
        $validation = new Validator($data_arr, $model->register_rules);
        //if validation has passed
        $errors = $validation->areThereErrors();

        //if no errors, we can register user if he is not registered wiht this email
        if (!$errors) {

            $alreadyRegisteredEmail=$model->select(['id'])->where(['email', $data_arr['email']])->limit(1)->get('first');
            if($alreadyRegisteredEmail){
                $this->setSessionMessage('register_errors_arr', ['email'=>['User with this email already exists. Please, log in!']]);
                return redirectBack();
            }
            

            //we need to hash password so we need to change that input
            $verifyToken=createRandomToken(60);
            $additional_data=[
                'password'=>password_hash($data_arr['password'], PASSWORD_DEFAULT),
                'verify_token'=>$verifyToken
                ];
            if(!getAppValue('register_with_email_confirmation')){
                $additional_data['verify_token']=null;
            }
            //vardump($additional_data);
            if ($model->save($additional_data)) {

                $this->clearOld($data_arr);
                //register with email confirmation or not
                if(getAppValue('register_with_email_confirmation')){
                    $encryptedToken=Encryption::encryptData($verifyToken);
                    $dataForEmail=[
                        'user_name'=>$data_arr['name'],
                        'link'=>getAppValue('website_address').'/email-confirmation/?token='.$encryptedToken
                        ];
                        if(new AuthenticationMail($data_arr['email'],$dataForEmail, $data_arr['name'] )){
                            return $this->view('auth/after-register', ['welcome_msg'=>"Please, go to your email account and confirm your eamail address!", 'settings'=>$this->settings]);
                        }else{
                            //some error with email
                            $this->setSessionMessage('register_errors_arr', ['server_register'=>['Something went wrong, please try again later']]);
                        }
                }else{
                    return $this->view('auth/after-register', ['welcome_msg'=>"You are registered. You can now log in with your credentials.", 'settings'=>$this->settings]);

                }//end register with email confirmation
                           
            } else { //if else model->save
               $this->setSessionMessage('register_errors_arr', ['server_register'=>['Something went wrong, please try again later']]);

            }
        } else {//if else do we have validation errors
            
        $this->setSessionMessage('register_errors_arr', $errors);
        }

        return redirectBack();
    }

}
