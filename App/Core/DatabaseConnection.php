<?php
namespace App\Core;

class DatabaseConnection
{
    // Static method to get a Database instance
    public static function getDatabase()
    {
        $config = require base_path('App/Core/config.php');
        return new Database($config['database']);
    }
}