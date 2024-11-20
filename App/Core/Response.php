<?php
namespace App\Core;

class Response
{
    const OK = 200;
    const CREATED = 201;
    const BAD_REQUEST = 400;
    const NOT_FOUND = 404;
    const FORBIDDEN = 403;
    const SERVER_ERROR = 500;

    // Sets the HTTP response code and sends JSON response
    public static function json($data, $status = self::OK)
    {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
    }
}