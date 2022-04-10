<?php


namespace App\app\controllers;


use App\app\Middleware;
use App\app\models\Course;
use App\app\models\CoursesTeachers;
use App\app\models\Teacher;
use App\Core\BaseController;
use App\Core\Csrf;
use App\Core\File;
use App\Core\Request;
use App\Core\Session;
use App\Core\Validator;
use App\Core\Log;

class AdminTeachersController extends BaseController
{

    public function __construct()
    {
        Middleware::admin();
    }

    public function index(){
        $teachers_model=new Teacher();
        $allTeachers=$teachers_model->select([])->all();
        $teachers=$teachers_model->belongsToMany($allTeachers, CoursesTeachers::class, Course::class,  'courses', 'teacher_id', 'course_id');
        return $this->view('admin/teachers/index', compact('teachers'));
    }

    public function edit($id){
        $teacher_model=new Teacher();
        $teacher=$teacher_model->select([])->where(['id', $id])->get('first');
        if(!$teacher){
            return $this->view('framework/404');
        }
        $courses_model=new Course();
        $courses=$courses_model->select([])->all();

        return $this->view('admin/teachers/edit', compact('teacher',  'courses'));
    }

    public function create(){
        $courses=(new Course())->select([])->all();

        return $this->view('admin/teachers/create', compact( 'courses'));
    }

    public function store()
    {
        Middleware::csrf();
        $data_arr = Request::getParams();
        //insert into session old inputs
        if(isset($data_arr['courses'])){
            $courses_ids=$data_arr['courses'];
            $data_arr['courses']='';
        }
        $this->setOld($data_arr);
        $model = new Teacher();
        //do validation for other input fields
        $validation = new Validator($data_arr, $model->rules);
        //if validation has passed, then insert but add 'photo'=>image name.jpg to $data_arr so it can be inserted into db
        $errors = $validation->areThereErrors();
        //if we have errors, we will just check if there are errors in photo so that we can return that error message too.
        //do we have a photo?
        if ($file = $_FILES['photo']) {
            if (!file_exists($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
                $errors['photo'] = ['Photo is required'];
            } else if (!File::isImage($file)) {
                $errors['photo'] = ['The image is invalid, please try again.'];
            }
        }

        if (!$errors) {

            $temp_file = $file['tmp_name'];
            //we dont have errors so we can first upload image and then when we get image new name, we can insert all fields in db
            $uploaded_file = File::move($temp_file, 'images', $_FILES['photo']);
            $image_name = $uploaded_file->getName();

            if ($teacher=$model->save(['photo'=>$image_name])) {
                //vardump($teacher_id);
                //have we selected courses for this teacher yet? if yes, then we need to insert them into courses_teachers table.
                //we need course_id (from select) and teacher_id after we insert new teacher
                if($courses_ids){
                    $course_model=new CoursesTeachers();
                    foreach ($courses_ids as $course_id){
                        if((int)$course_id){
                            //create array and insert into table
                            $array_of_ids=[];
                            $array_of_ids['teacher_id']=$teacher['id'];
                            $array_of_ids['course_id']=$course_id;
                            
                            $course_model->save($array_of_ids);
                        }
                    }
                }

                Session::add('success', 'Teacher added succesfully');
                $this->clearOld($data_arr);
            } else {
                Session::add($errors['server_error'] = ['Something went wrong, please try again later']);
            }
        } else {
            Session::add('errors', $errors);
        }

        return redirect('admin-teachers');
    }

    public function update($id){
        Middleware::csrf();
        $data_arr = Request::getParams();
        if(isset($data_arr['courses'])){
            $courses_ids=$data_arr['courses'];
            $data_arr['courses']='';
        }
        $model = new Teacher();
        $teacher=$model->select([])->where(['id', $id])->get('first');


        //do validation for other input fields
        $validation = new Validator($data_arr, $model->rules);
        //if validation has passed, then insert but add 'photo'=>image name.jpg to $data_arr so it can be inserted into db
        $errors = $validation->areThereErrors();
        //if we have errors, we will just check if there are errors in photo so that we can return that error message too.
        //we dont need new photo but if file was uploaded, it has to be type photo
        $file = $_FILES['photo'];
        
        if (file_exists($file['tmp_name']) && is_uploaded_file($file['tmp_name'])) {
            //we have file upload so we check if it is image
            if (!File::isImage($file)) {
                $errors['photo'] = ['The image is invalid, please try again.'];
            }
        }

        if (!$errors) {
            //are we changing photo? if we do, we need to remove photo from folder, else we do nothing
            $photo=[];
            if (file_exists($file['tmp_name']) && is_uploaded_file($file['tmp_name'])) {
                //remove old photo
                $old_photo = $teacher->photo;
                File::deleteFile($old_photo, 'images');

                $temp_file = $file['tmp_name'];
                //if there are errors in other fields, we will not upload photo
                //we dont have errors so we can first upload image and then when we get image new name, we can insert all fields in db
                $uploaded_file = File::move($temp_file, 'images', $_FILES['photo']);
                $image_name = $uploaded_file->getName();
                //$data_arr['photo'] = $image_name;
                $photo['photo']=$image_name;
            }

            $model->update('id', $id, $photo);

                //since we have table courses_teachers, we need to update it.
                //first we delete all courses for this teacher
                //if there are courses in request, we create new rows
                if($courses_ids){
                    //since we have table courses_teachers, we need to update it.
                    //first we delete all courses for this teacher

                    $course_model=new CoursesTeachers();
                    $courses_teachers_ids=$course_model->select(['id'])->where(['teacher_id', $id])->all();
                    //printr($courses_teachers_ids);
                    if(count($courses_teachers_ids)){
                        foreach ($courses_teachers_ids as $row){
                            $course_model->deleteWhere('id', $row->id);
                        }
                    }
                    foreach ($courses_ids as $course_id){
                        if((int)$course_id){
                            //create array and insert into table
                            //vardump($teacher);
                            $array_of_ids=[];
                            $array_of_ids['teacher_id']=$teacher->id;
                            $array_of_ids['course_id']=$course_id;
                            
                            $course_model->save($array_of_ids);
                        }
                    }
                }

                Session::add('success', 'Teacher updated succesfully');
                $this->clearOld($data_arr);
        } else {
            Session::add('errors', $errors);
        }

        return redirect('admin-teachers');
    }

    public function destroy($id){
        Middleware::csrf();
        $model = new Teacher();
        $teacher=$model->select([])->where(['id', $id])->get('first');
        //if we have photo, we need to remove it from folder
        if( $old_photo = $teacher->photo){
            File::deleteFile($old_photo, 'images');
        }

        //if we have course assigned to this teacher, we need to remove the row in courses_teachers
        $course_model=new CoursesTeachers();
        $courses_teachers_ids=$course_model->select(['id'])->where(['teacher_id', $id])->all();

        if(count($courses_teachers_ids)){
            foreach ($courses_teachers_ids as $row){
                $course_model->deleteWhere('id', $row->id);
            }
        }

        //delete row from the table
        if($model->deleteWhere('id', $id)){
            Session::add('success', 'Teacher deleted succesfully');
        }


        return redirect('admin-teachers');

    }

}
