<?php


namespace App\app\models;


use App\core\BaseModel;

class CoursesTeachers extends BaseModel
{
    protected $table="courses_teachers";

    protected $fillable=['course_id', 'teacher_id'];

}