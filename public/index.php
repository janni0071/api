<?php
// Add CORS headers to allow requests from other origins (modify as needed for production)
// Allow all origins (wildcard)
header("Access-Control-Allow-Origin: *");
// Allow specific methods (GET, POST, PUT, DELETE, OPTIONS)
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
// Allow specific headers (including Authorization)
header("Access-Control-Allow-Headers: Authorization, Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

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