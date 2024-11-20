<?php

echo 'meow';

const BASE_PATH = __DIR__ . "/../";

require str_replace('/', DIRECTORY_SEPARATOR, BASE_PATH  . 'App/Core/functions.php');

spl_autoload_register(function($class){
    require base_path("App/Core/$class.php");
});

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$method = $_SERVER['REQUEST_METHOD'];

$router = new Router();
$routes = require base_path('App/Core/routes.php');
$router->route($path, $method);