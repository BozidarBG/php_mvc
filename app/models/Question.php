<?php


namespace App\app\models;


use App\core\BaseModel;

class Question extends BaseModel
{

    protected $table="questions";


    protected $fillable=['answered','first_name', 'last_name', 'phone', 'email', 'question'];

    public $rules=[
        'first_name'=>['required'=>true, 'minLen'=>2,'maxLen'=>100],
        'last_name'=>['required'=>true, 'minLen'=>2,'maxLen'=>100],
        'phone'=>['required'=>true, 'maxLen'=>20],
        'email'=>['required'=>true, 'email'=>true,'maxLen'=>150],
        'question'=>['required'=>true, 'maxLen'=>1500]

    ];

}