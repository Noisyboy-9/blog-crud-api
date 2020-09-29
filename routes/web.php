<?php

/** @var \Laravel\Lumen\Routing\Router $router */

$router->get('/posts', 'PostsController@index');
$router->post('/posts', 'PostsController@store');
$router->put('/posts/{id}', 'PostsController@update');
$router->get('/posts/{id}', 'PostsController@show');
