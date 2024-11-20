<?php

echo 'meow';

const BASE_PATH = __DIR__ . "/../";

require str_replace('/', DIRECTORY_SEPARATOR, BASE_PATH  . 'App/Core/functions.php');

//echo base_path('test/test.php');

// Extracts the URL path without the query string
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$method = $_SERVER['REQUEST_METHOD'];

dd($path);
dd($method);
