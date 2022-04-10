<?php


namespace App\app\controllers;



use App\app\Middleware;
use App\app\models\Application;
use App\app\models\Course;
use App\app\models\User;
use App\Core\BaseController;
use App\Core\Csrf;
use App\Core\Request;
use App\Core\Session;
use App\Core\Validator;

class AuthenticatedUserController extends BaseController
{
    public function __construct()
    {
        Middleware::auth();
    }


    public function index(){

        $user=getAuthUserModel();
        $applications_model=new Application();
        //this should get us all rows that has user_id of this user. then we need course name from course_id
        $applications=$applications_model->select([])->where(['user_id', $user->id])->all();
        $applicationsWithCourses=$applications_model->belongsTo($applications, Course::class, 'course', 'course_id');
        return $this->view('frontend/profile', compact('applicationsWithCourses', 'user'));
    }


    public function storeApplication(){
        Middleware::csrf();
        $data_arr = Request::getParams();
        //insert into session old inputs
        $this->setOld($data_arr);
        $model = new Application();
        //do validation for other input fields
        $validation = new Validator($data_arr, $model->rules);
        //if validation has passed, then insert but add 'photo'=>image name.jpg to $data_arr so it can be inserted into db
        $errors = $validation->areThereErrors();

        if (!$errors) {
            //check if this user has already applied for this course. we won't let apply twice
            $result=$model->select([])->where(['user_id', getAuthUserId()],[ 'course_id',$data_arr['course_id']])->all();
            //vardump($result);
            if($result){
                $errors=[];
                $errors['course']=['You have already applied for this course'];
                Session::add('errors_course', $errors);

                return redirectBack();
            }

            //check if this course exists. if not, then user has changed html. then we return back without any info
            $course_model=new Course();
            $course=$course_model->select([])->where(['id', $data_arr['course_id']])->get('first');
            if(!$course){
                return redirectBack();
            }

            if ($model->save(['user_id'=>getAuthUserId()])) {
                Session::add('success', 'You have applied succesfully');
                $this->clearOld($data_arr);
            } else {
                Session::add($errors['server_error']= ['Something went wrong, please try again later']);
            }
        } else {
            Session::add('errors', $errors);
        }

        return redirect('profile');
    }

    public function changePassword(){
        Middleware::csrf();
        $data_arr = Request::getParams();
        $model = new User;
        $user=$model->select([])->where(['id', getAuthUserId()])->get('first');

        //check if old password matches
        if (password_verify($data_arr['old_password'], $user->password)) {
            //do validation for other input fields
            
            $validation = new Validator($data_arr, $model->register_rules);
            //if validation has passed
            $errors = $validation->areThereErrors();
            if (!$errors) {
                //we need to hash password so we need to change that input
                

                if ($model->update('id', getAuthUserId(), ['password' => password_hash($data_arr['password'], PASSWORD_DEFAULT)])) {
                    Session::add('success', 'Password changed succesfully');

                } else {
                    Session::add($errors['server_error'] = ['Something went wrong, please try again later']);
                }

            }else{
                Session::add('errors', $errors);
            }


        } else {
            $errors=[];
            $errors['old_password']=['Your old password is inocrrect'];
            Session::add('errors', $errors);
        }

        return redirectBack();
    }


}
