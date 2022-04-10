<?php


namespace App\app\controllers;

use App\app\Middleware;
use App\app\models\Question;
use App\Core\BaseController;
use App\Core\Csrf;
use App\Core\Request;
use App\Core\Session;

class AdminQuestionsController extends BaseController
{

    public function __construct()
    {
        Middleware::admin();
    }

    public function unanswered(){
        $questionModel=new Question();
        $results=$questionModel->select([])->where(['answered', '0'])->orderBy('created_at', 'DESC')->all();
        $page_name='Unanswered Questions';
        return $this->view('admin/questions/index', compact('results', 'page_name'));
    }

    public function answered(){
        $questionModel=new Question();
        $results=$questionModel->select([])->where(['answered', '1'])->orderBy('created_at', 'DESC')->all();
        $page_name='Answered Questions';
        return $this->view('admin/questions/index', compact('results', 'page_name'));
    }

    public function update($id){
        Middleware::csrf();
        $questionModel=new Question();
        $question=$questionModel->select([])->where(['id', $id])->get('first');
          //printr($model->model_data);
          if ($question->answered == 1) {
              $data_arr['answered'] = 0;
              $page_to_redirect='admin-answered-questions';
          } else {
              $data_arr['answered'] = 1;
              $page_to_redirect='admin-unanswered-questions';
          }

          $questionModel->update('id', $id, $data_arr);
          Session::add('success', 'Question altered succesfully');
          return redirect($page_to_redirect);

    }

    public function destroy($id){
        Middleware::csrf();
        $questionModel=new Question();
        $question=$questionModel->select([])->where(['id', $id])->get('first');
        $page_to_redirect= $question->answered == 1 ? 'admin-answered-questions' : 'admin-unanswered-questions';

        //delete row from the table
        if($questionModel->deleteWhere('id',$id)){
            Session::add('success', 'Question deleted succesfully');
        }
        return redirect($page_to_redirect);

    }
}
