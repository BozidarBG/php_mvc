<?php


namespace App\app\controllers;

use App\app\Middleware;
use App\app\models\CoursesTeachers;
use App\app\models\Question;
use App\app\models\Setting;
use App\app\models\Teacher;
use App\Core\BaseController;
use App\core\Encryption;
use App\Core\Validator;
use App\app\models\Course;
use App\Core\Request;
use App\Core\Session;
use App\app\models\User;

class PagesController extends BaseController
{

    public $settings;

    public function __construct()
    {
        $this->settings=Setting::getSettings();

    }

    public function verifyToken(){
        if(isset($_GET['token'])){
            $encryptedToken=trim(str_replace(' ', '+',$_GET['token']));
            $decryptedToken=Encryption::decryptData($encryptedToken);

            $userModel=new User();
            $user=$userModel->select(['id'])->where(['verify_token', $decryptedToken])->get('first');
            //vardump($user->id);
            if($user){
                $userModel->update('id', $user->id, ['verify_token'=>null]);
                $message="You are now confirmed. Please login with your ceredentials to continue";
            }else{
                $message="Something is wrong with your verification or you are already verified. Please try to logi in";

            }
            return $this->view('auth/after-confirmation', ['message'=>$message, $settings=>$this->settings]);
        }else{
            return redirectToHome();
        }


    }

    

    public function index(){
        $settings=$this->settings;
        return $this->view('frontend/index', compact('settings'));
    }

    public function courses(){
        
        return $this->view('frontend/courses',['settings'=>$this->settings, 'courses'=>$courses=(new Course())->select([])->all()] );
    }

    public function showCourse($slug){
        
        $course_model=new Course();
        $course=$course_model->select([])->where(['slug', $slug])->get('first');
        if(!$course){
            return $this->view('framework/404');
        }
        $courseWithTeachers=$course_model->belongsToMany([$course], CoursesTeachers::class, Teacher::class, 'teachers', 'course_id', 'teacher_id')[0];
        $settings=$this->settings;
        return $this->view('frontend/course',compact('settings', 'courseWithTeachers'));
    }



    public function teachers(){
        $settings=$this->settings;
        $teacher_model=new Teacher();
        $teachers=$teacher_model->select([])->all();
        
        $teachersWithCourses=$teacher_model->belongsToMany($teachers, CoursesTeachers::class, Course::class, 'courses', 'teacher_id', 'course_id');
        return $this->view('frontend/teachers',compact('settings', 'teachersWithCourses'));
    }

    public function contact(){
        $settings=$this->settings;
        return $this->view('frontend/contact',['settings'=>$this->settings]);
    }

    public function storeQuestion(){
        Middleware::csrf();
        $data_arr = Request::getParams();
        //insert into session old inputs
        $this->setOld($data_arr);
        $model = new Question();
        //do validation for other input fields
        $validation = new Validator($data_arr, $model->rules);
        //if validation has passed, then insert but add 'photo'=>image name.jpg to $data_arr so it can be inserted into db
        $errors = $validation->areThereErrors();

        if (!$errors) {
            if ($model->save([])) {
                Session::add('question_success', 'Your inquiry was sent to our administrator succesfully. Someone will conatact you as soon as possible');
                $this->clearOld($data_arr);
            } else {
                Session::add($errors['server_error'] = ['Something went wrong, please try again later']);
            }
        } else {
            Session::add('question_errors', $errors);
        }

        return redirect('contact');
    }


}
