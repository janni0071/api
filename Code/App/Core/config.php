<?php

use App\Core\LoadEnv;

LoadEnv::load(base_path('.env'));

return [
    'database' => [
        'host' => $_ENV['DB_HOST'],
        'port' => '3306',
        'dbname' => $_ENV['DB_NAME'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD'] ?? '',
        'charset' => 'utf8mb4',
    ],
];