<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post("/login", "AuthController@login");
$router->post("/register", "AuthController@register");

$router->group(['prefix' => 'events', 'middleware' => 'auth'], function () use ($router) {
    $router->get('/', "EventsController@getEvents");
    $router->post("create", "EventsController@createEvent");
    $router->post("remove", "EventsController@removeEvent");
    $router->post("update", "EventsController@updateEvent");
    
});

$router->post("/level", "AuthController@level");

