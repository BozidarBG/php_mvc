<?php


namespace App\app\models;


use App\core\BaseModel;
use App\Core\Request;

class Setting extends BaseModel
{
    protected $table="settings";

    protected $all_table_fields=['id','prop_name', 'prop_value', 'created_at', 'updated_at'];

    protected $fillable=['prop_name', 'prop_value'];

    public $rules=[
        'prop_name'=>['required'=>true, 'maxLen'=>30],
        'prop_value'=>['required'=>true, 'maxLen'=>250],

    ];

    public static function getSettings(){
        if(Request::method()==='GET'){
            $settingsModel=new Setting();
            $settings=$settingsModel->select([])->all();
            $start_arr=(json_decode(json_encode($settings), true));
            $result=[];
            foreach ($start_arr as $_arr){
                $result[$_arr['prop_name']]=$_arr['prop_value'];
            }
            return $result;
        }
    }

}