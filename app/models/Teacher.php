<?php


namespace App\app\models;


use App\core\BaseModel;


class Teacher extends BaseModel
{


    protected $table="teachers";

    protected $all_table_fields=['id','name', 'about', 'photo', 'created_at', 'updated_at'];

    protected $fillable=['name', 'about','photo'];

    public $rules=[
        'name'=>['required'=>true, 'minLen'=>2],
        'about'=>['required'=>true, 'maxLen'=>1500],

    ];


}