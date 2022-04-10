<?php


return [
    'database' => [
             'pdo' => [
                'driver' => 'mysql',
                'host' => 'localhost',
                'db_name' => 'smarteduold',
                'db_username' => 'root',
                'db_user_password' => '',
                'default_fetch' => PDO::FETCH_OBJ,
            ],
            

        ],
    'website_name'=>'Smart EDU',
    'website_address'=>"http://smartedu.test",
    'environment'=>'live',
    'inc_dir'=>$_SERVER["DOCUMENT_ROOT"].DIRECTORY_SEPARATOR."views".DIRECTORY_SEPARATOR,
    'files_dir'=>__DIR__.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'files',
    'remember_me_cookie_name'=>'remember',
    'remember_me_duration'=>999999,
    'register_with_email_confirmation'=>false,
     //mailable settings
    'mailable_host'=>"smtp.gmail.com",
    'mailable_username'=>'bozidar.djordjevic.business@gmail.com',
    'mailable_password'=>'oijeyhkpcguiyjhw',
    'mailable_from_email'=>'bozidar.djordjevic.business@gmail.com',
    'mailable_from_name'=>'Smart EDU Bozidar Djordjevic',
];
