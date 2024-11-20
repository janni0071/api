<?php

function dd($variable) {
    echo '<pre style="background: #f4f4f4; color: #333; padding: 10px; border: 1px solid #ccc;">';
    var_dump($variable);
    echo '</pre>';
    die();
}

function base_path($path) {
    return str_replace('/', DIRECTORY_SEPARATOR, BASE_PATH . $path);
}