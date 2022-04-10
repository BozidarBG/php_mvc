<?php


namespace App\app\controllers;


use App\app\models\Setting;
use App\Core\BaseController;

use App\Core\Validator;
use App\app\Middleware;
use App\Core\App;
use App\Core\Csrf;
use App\Core\Request;
use App\Core\Session;

class AdminSettingsController extends BaseController
{
    public function __construct()
    {
        Middleware::admin();
    }

    public function index(){

        $settings=(new Setting())->select([])->all();
        return $this->view('admin/settings/index', compact( 'settings'));
    }

    public function store()
    {
        Middleware::csrf();
        $data_arr = Request::getParams();
        //insert into session old inputs
        $this->setOld($data_arr);
        $model = new Setting();
        //do validation for other input fields
        $validation = new Validator($data_arr, $model->rules);
        //if validation has passed, then insert but add 'photo'=>image name.jpg to $data_arr so it can be inserted into db
        $errors = $validation->areThereErrors();

        if (!$errors) {

            if ($model->save([])) {
                Session::add('success', 'Setting added succesfully');
                $this->clearOld($data_arr);
            } else {
                Session::add($errors['server_error'] = ['Something went wrong, please try again later']);
            }
        } else {
            Session::add('errors', $errors);
        }

        return redirect('admin-settings');
    }

    public function update($id){
        Middleware::csrf();
        $data_arr = Request::getParams();

        $settingsModel = new Setting();
        $setting=$settingsModel->select([])->where(['id', $id])->get('first');
        //do validation for other input fields
        $validation = new Validator($data_arr, $settingsModel->rules);
        //if validation has passed, then insert but add 'photo'=>image name.jpg to $data_arr so it can be inserted into db
        $errors = $validation->areThereErrors();

        if (!$errors) {
            //are we changing photo? if we do, we need to remove photo from folder, else we do nothing

            if ($settingsModel->update('id', $id)) {
                Session::add('success', 'Setting updated succesfully');
            } else {

                Session::add($errors['server_error'] = ['Something went wrong, please try again later']);
            }
        } else {
            Session::add('errors', $errors);

        }

        return redirect('admin-settings');
    }

    public function destroy($id){
        Middleware::csrf();
        $model = new Setting();
        
        //delete row from the table
        if($model->deleteWhere('id', $id)){
            Session::add('success', 'Setting deleted succesfully');
        }
      
        return redirect('admin-settings');;

    }
}
