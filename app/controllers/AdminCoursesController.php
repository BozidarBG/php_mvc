<?php


namespace App\app\controllers;

use App\app\Middleware;
use App\app\models\Course;
use App\app\models\CoursesTeachers;
use App\app\models\Teacher;
use App\app\models\Application;
use App\Core\BaseController;
use App\Core\File;
use App\Core\Request;
use App\Core\Session;
use App\Core\Validator;

class AdminCoursesController extends BaseController
{
    public function __construct()
    {
        Middleware::admin();
    }

    public function index(){
        $courseModel=new Course();
        $courses=$courseModel->select([])->all();
        $coursesWithTeachers=$courseModel->belongsToMany($courses, CoursesTeachers::class, Teacher::class, 'teachers', 'course_id', 'teacher_id');
        return $this->view('admin/courses/index', compact('coursesWithTeachers'));
    }

    public function create(){

        return $this->view('admin/courses/create');
    }

    public function store()
    {
        Middleware::csrf();
        $data_arr = Request::getParams();
        //insert into session old inputs
        $this->setOld($data_arr);
        $model = new Course();
        //do validation for other input fields
        $validation = new Validator($data_arr, $model->rules);
        //if validation has passed, then insert but add 'photo'=>image name.jpg to $data_arr so it can be inserted into db
        $errors = $validation->areThereErrors();
        //vardump($errors);
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
            $additionalData['photo']=$image_name;
            $additionalData['slug']=$model->slug($data_arr['name']);


            if ($model->save($additionalData)) {
                Session::add('success', 'Course added succesfully');
                $this->clearOld($data_arr);
            } else {
                Session::add($errors['server_error'] = ['Something went wrong, please try again later']);
            }
        } else {
            Session::add('errors', $errors);
        }

        return redirect('admin-create-course');
    }


    public function edit(int $id){
        $model=new Course();

        $course=$model->select([])->where(['id', $id])->get('first');
        if(!$course){
            return $this->view('framework/404');
        }
        return $this->view('admin/courses/edit', compact('course'));
    }

    public function update(int $id){
        Middleware::csrf();
        $data_arr = Request::getParams();
        $model = new Course($id);

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
            if (file_exists($file['tmp_name']) && is_uploaded_file($file['tmp_name'])) {
                //remove old photo
                $old_photo = $model->model_data->photo;
                File::deleteFile($old_photo, 'images');

                $temp_file = $file['tmp_name'];
                //image path needs to be just new name of file.

                //if there are errors in other fields, we will not upload photo
                //we dont have errors so we can first upload image and then when we get image new name, we can insert all fields in db
                $uploaded_file = File::move($temp_file, 'images', $_FILES['photo']);
                $image_name = $uploaded_file->getName();
            }

            if ($model->update('id', $id, ['photo'=>$image_name])) {
                Session::add('success', 'Course updated succesfully');
            } else {

                Session::add($errors['server_error'] = ['Something went wrong, please try again later']);
            }
        } else {
            Session::add('errors', $errors);

        }

        return redirect('admin-edit-course/'.$id);
    }

    public function destroy($id){
        Middleware::csrf();
        $courseModel=new Course();
        $course=$courseModel->select([])->where(['id', $id])->get('first');
        //if we have photo, we need to remove it from folder

        if($course->photo && file_exists('files/images/'.$course->photo)){
            File::deleteFile($course->photo, 'images');
        }
        //delete row from the table
        if($courseModel->deleteWhere('id', $id)){
            Session::add('course_deleted_success', 'Course deleted succesfully');
        }

        //remove all applications for this course
        $applicationModel=new Application();
        $applications=$applicationModel->select(['id'])->where(['course_id', $id])->all();
        foreach($applications as $obj){
            $applicationModel->deleteWhere('id', $obj->id);
        }
        //remove courses_teachers 
        $coursesTeachersModel=new CoursesTeachers;
        $courses_teachers=$coursesTeachersModel->select(['id'])->where(['course_id', $id])->all();
        foreach($courses_teachers as $obj){
            $coursesTeachersModel->deleteWhere('id', $obj->id);
        }

        return redirect('admin-courses');
    }

}
