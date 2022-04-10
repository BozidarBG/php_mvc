<?php
//pages for all

$router->get('', 'PagesController@index');
$router->get('courses', 'PagesController@courses');
$router->get('course/{slug}', 'PagesController@showCourse');
$router->get('teachers', 'PagesController@teachers');
$router->get('pricing', 'PagesController@pricing');
$router->get('contact', 'PagesController@contact');
$router->post('store-question', 'PagesController@storeQuestion');
$router->post('store-application', 'PagesController@storeApplication');
//end

//routes for admin home page
$router->get('admin-dashboard', 'AdminPagesController@dashboard');
$router->get('admin-users', 'AdminPagesController@users');
$router->post('admin-delete-user/{id}', 'AdminPagesController@destroy');
//end

//admin routes for courses
$router->get('admin-courses', 'AdminCoursesController@index');
$router->get('admin-create-course', 'AdminCoursesController@create');
$router->post('admin-create-course', 'AdminCoursesController@store');
$router->get('admin-edit-course/{id}', 'AdminCoursesController@edit');
$router->post('admin-edit-course/{id}', 'AdminCoursesController@update');
$router->post('admin-delete-course/{id}', 'AdminCoursesController@destroy');
//end

//admin routes for teachers
$router->get('admin-teachers', 'AdminTeachersController@index');
$router->get('admin-create-teacher', 'AdminTeachersController@create');
$router->post('admin-create-teacher', 'AdminTeachersController@store');
$router->get('admin-edit-teacher/{id}', 'AdminTeachersController@edit');
$router->post('admin-update-teacher/{id}', 'AdminTeachersController@update');
$router->post('admin-delete-teacher/{id}', 'AdminTeachersController@destroy');
//end

//admin routes for questions by all users
$router->get('admin-answered-questions', 'AdminQuestionsController@answered');
$router->get('admin-unanswered-questions', 'AdminQuestionsController@unanswered');
$router->post('admin-edit-question/{id}', 'AdminQuestionsController@update');
$router->post('admin-delete-question/{id}', 'AdminQuestionsController@destroy');
//end

//admin routes for applications
$router->get('admin-new-applications', 'AdminApplicationsController@unanswered');
$router->get('admin-solved-applications', 'AdminApplicationsController@answered');
$router->post('admin-edit-application/{id}', 'AdminApplicationsController@update');
$router->post('admin-delete-application/{id}', 'AdminApplicationsController@destroy');
//end

//admin routes for settings
$router->get('admin-settings', 'AdminSettingsController@index');
$router->post('admin-create-settings', 'AdminSettingsController@store');
$router->post('admin-edit-settings/{id}', 'AdminSettingsController@update');
$router->post('admin-delete-settings/{id}', 'AdminSettingsController@destroy');
//end

///routes for authenticated users
$router->get('profile', 'AuthenticatedUserController@index');
$router->post('apply', 'AuthenticatedUserController@storeApplication');
$router->post('change-password', 'AuthenticatedUserController@changePassword');
$router->post('logout', 'LoginController@logout');
$router->get('email-confirmation', 'PagesController@verifyToken');
//end

//login/register routes
$router->get('login', 'LoginController@showLoginForm');
$router->get('register', 'RegisterController@showRegisterForm');

$router->post('login', 'LoginController@login');
$router->post('register', 'RegisterController@register');

