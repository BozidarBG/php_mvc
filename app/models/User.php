<?php


namespace App\app\models;

use App\core\BaseModel;


class User extends BaseModel
{
    protected $table='users';

    protected $all_table_fields=['name', 'email','password', 'role', 'verify_token', 'remember_token','created_at','updated_at'];

    protected $fillable=['name', 'email'];

    public $hidden_columns=['password', 'verify_token', 'remember_token'];


    public $register_rules=[
        'name'=>['required'=>true, 'minLen'=>2, 'maxLen'=>50],
        'email'=>['required'=>true, 'email'=>'true','minLen'=>6, 'maxLen'=>150],
        'password'=>['required'=>true, 'minLen'=>6, 'maxLen'=>150],
        'password_confirmation'=>['same_as'=>'password']
    ];

    public $login_rules=[
        'email'=>['required'=>true, 'email'=>true,'minLen'=>6, 'maxLen'=>150],
        'password'=>['required'=>true, 'minLen'=>6, 'maxLen'=>150]
    ];


}