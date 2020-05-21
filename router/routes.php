<?php

return [
    '/' => 'HomeController@index',
    'tasks'  => 'TaskController@index',
    'tasks/create' => 'TaskController@create',
    'tasks/store' => 'TaskController@store',
    'tasks/edit' => 'TaskController@edit',
    'tasks/update' => 'TaskController@update',
    'login' => 'AuthController@showLoginForm',
    'login/auth' => 'AuthController@auth',
    'logout' => 'AuthController@logout'
];