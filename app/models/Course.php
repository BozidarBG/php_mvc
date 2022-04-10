<?php


namespace App\app\models;


use App\core\BaseModel;
use App\core\SlugTrait;

class Course extends BaseModel
{
    use SlugTrait;

    protected $table="courses";

    protected $all_table_fields=['id','name', 'slug', 'short', 'content', 'photo', 'duration', 'price', 'lectures','created_at', 'updated_at'];

    protected $fillable=['name', 'slug','short', 'content', 'photo', 'duration', 'price', 'lectures'];

    public $rules=[
        'name'=>['required'=>true, 'string'=>true, 'minLen'=>2],
        'short'=>['required'=>true, 'string'=>true, 'minLen'=>5],
        'content'=>['required'=>true, 'string'=>true, 'minLen'=>10],
        'duration'=>['required'=>true, 'maxLen'=>2],
        'price'=>['required'=>true, 'minLen'=>1],
        'lectures'=>['required'=>true, 'maxLen'=>4],
        'photo'=>['required'=>true],
    ];


}