<?php

use Illuminate\Routing\Router;

Admin::registerHelpersRoutes();

Route::group([
    'prefix'        => config('admin.prefix'),
    'namespace'     => Admin::controllerNamespace(),
    'middleware'    => ['web', 'admin'],
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    $router->resource('users', UserController::class);
    $router->resource('rental', RentalController::class);
    $router->resource('job', JobController::class);
    $router->resource('house', HouseController::class);
    $router->get('house/{data}/data', 'HouseController@operation');

});
