<?php
use App\Core\Database;
use App\Core\Router;

const BASE_PATH = __DIR__ . "/../";

require str_replace('/', DIRECTORY_SEPARATOR, BASE_PATH  . 'App/Core/functions.php');

spl_autoload_register(function($class){
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    require base_path("$class.php");
});

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$method = $_SERVER['REQUEST_METHOD'];

$router = new Router();
$routes = require base_path('App/Core/routes.php');
$router->route($path, $method);

$config = require base_path('App/Core/config.php');
$db = new Database($config['database']);
$query = 'SELECT * FROM tbl_countries';
$result = $db->query($query);
$result->execute();

dd($result->fetchAll());