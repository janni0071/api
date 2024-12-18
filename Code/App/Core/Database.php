<?php

namespace App\Core;
use PDO;
use PDOException;

class Database
{
    protected $connection;

    public function __construct($config){

        $username = $config['user'] ?? 'root';
        $password = $config['password'] ?? '';

        $dsn = 'mysql:' . http_build_query($config, '', ';');
        
        try {
            $this->connection = new PDO($dsn, $username, $password, [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            // Database connection error response
            Response::json([
                'status' => 'error',
                'message' => 'Database connection failed.',
                'data' => ['error' => $e->getMessage()]
            ], Response::SERVER_ERROR);
            exit;
        }
        
    }
    public function query($query){
        try {
            $statement = $this->connection->prepare($query);
            return $statement;
        } catch (PDOException $e) {
            // Handle query execution errors
            Response::json([
                'status' => 'error',
                'message' => 'Database query failed.',
                'data' => ['error' => $e->getMessage()]
            ], Response::SERVER_ERROR);
            exit;
        }
    }
}