<?php


namespace App\app\controllers;


use App\app\Middleware;
use App\app\models\Application;
use App\app\models\User;
use App\app\models\Course;
use App\Core\App;
use App\Core\BaseController;
use App\Core\Csrf;
use App\Core\Session;
use App\Core\Request;
use App\Core\Log;

//controller that handles applications for courses

class AdminApplicationsController extends BaseController
{

    public function __construct()
    {
        Middleware::admin();
    }

    public function answered(){
        $application_model=new Application;
        $applicationsOnly=$application_model->select([])->where(['answered', 1])->orderBy('created_at', 'DESC')->limit(10)->all();
        $applicationsWithUsers=$application_model->belongsTo($applicationsOnly, User::class, 'user', 'user_id');
        $applications=$application_model->belongsTo($applicationsWithUsers, Course::class, 'course', 'course_id');
        $page_name='Answered Aplications';
        return $this->view('admin/applications/index', compact('applications', 'page_name'));
    }

    public function unanswered(){
        $application_model=new Application;
        $applicationsOnly=$application_model->select([])->where(['answered', 0])->orderBy('created_at', 'DESC')->limit(10)->all();
        $applicationsWithUsers=$application_model->belongsTo($applicationsOnly, User::class, 'user', 'user_id');
        $applications=$application_model->belongsTo($applicationsWithUsers, Course::class, 'course', 'course_id');
        $page_name='Unanswered Applications';
        return $this->view('admin/applications/index', compact('applications', 'page_name'));
    }

    public function update(int $id){
        Middleware::csrf();
          $model = new Application();
          $application=$model->select([])->where(['id', $id])->get('first');

          if ($application->answered == 1) {
              $data_arr['answered'] = 0;
              $page_to_redirect='admin-solved-applications';

          } else {
              $data_arr['answered'] = 1;
              $page_to_redirect='admin-new-applications';

          }

          $model->update('id', $id, $data_arr);
          Session::add('success', 'Application altered succesfully');
          return redirect($page_to_redirect);


    }

    public function destroy(int $id){
        Middleware::csrf();
        $model = new Application();
        $application=$model->select([])->where(['id', $id])->get('first');
          //determine where to return
          $page_to_redirect= $application->answered == 1 ? 'admin-solved-applications' : 'admin-new-applications';

          //delete row from the table
          if($model->deleteWhere('id', $id)){
              Session::add('success', 'Application deleted succesfully');
          }
        
        return redirect($page_to_redirect);

    }

}
