<?php


namespace App\app\controllers;


use App\app\Middleware;
use App\app\models\Application;
use App\app\models\Question;
use App\app\models\User;
use App\app\models\Course;
use App\Core\BaseController;
use App\Core\Csrf;
use App\Core\Session;
use App\core\Pagination;


class AdminPagesController extends BaseController
{

    public function __construct()
    {
        Middleware::admin();
    }

    public function dashboard(){
        $users_model=new User();
        $users_count=$users_model->count()->where(['role', '!=', 1])->get('first')->total_records;
        $users=$users_model->select([])->where(['role', '!=', 1])->orderBy('created_at', 'DESC')->limit(5)->all();

        $application_model=new Application();
        $unanswered_applications_count=$application_model->count()->where(['answered', 0])->get('first')->total_records;
        $applicationsOnly=$application_model->select([])->where(['answered', 0])->orderBy('created_at', 'DESC')->limit(5)->all();
        $applicationsWithUsers=$application_model->belongsTo($applicationsOnly, User::class, 'user', 'user_id');//->belongsTo($application, Course::class, 'course', 'course_id');
        $applications=$application_model->belongsTo($applicationsWithUsers, Course::class, 'course', 'course_id');

        $question_model=new Question();
        $unanswered_questions_count=$question_model->count()->get('first')->total_records;

        return $this->view('admin/index', compact('users', 'users_count', 'applications','unanswered_applications_count',  'unanswered_questions_count'));
    }

    public function users(){
        

        $users_model=new User();
        $count=$users_model->count()->where(['role', '!=', 1])->get('first');
        $paginationObj=new Pagination($count->total_records, 5);
        $users=$users_model->select([])->where(['role', '!=', 1])->limit($paginationObj->per_page)->offset($paginationObj->offset)->all();
        return $this->view('admin/users/index', compact('users', 'paginationObj'));
    }

    public function destroy($id){
        Middleware::csrf();
        $user_model = new User($id);

        //delete all applications for deleted user
        $application_model=new Application;
        $pplications_for_this_user=$application_model->select(['id'])->where(['user_id', $id])->all();
       //printr($pplications_for_this_user);
        if(count($pplications_for_this_user)){
            foreach ($pplications_for_this_user as $app){
                $application->deleteWhere('id',$app->id);
            }
        }
        //delete row from the table
        if($user_model->deleteWhere('id',$id)){
            Session::add('success', 'User deleted succesfully');
        }


        return redirectBack();

    }


}
