<?php

/** @var \Laravel\Lumen\Routing\Router $router */

$router->get('/posts', 'PostsController@index');
$router->post('/posts', 'PostsController@store');
$router->get('/posts/{id}', 'PostsController@show');
