<?php


namespace App\app\models;


use App\core\BaseModel;


//application = person has applied for a course

class Application extends BaseModel
{
    protected $table="applications";

    protected $fillable=['user_id', 'course_id', 'answered'];

    public $rules=[

        'course_id'=>['required'=>true, 'number'=>true]
    ];


}