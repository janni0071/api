<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

// Get the database connection
$db = DatabaseConnection::getDatabase();

// Check if 'id' is set in $params and is a valid integer
if (isset($params['id']) && ctype_digit($params['id'])) {
    $id = (int) $params['id'];

    try {
        // Prepare and execute the query to find the lehrbetrieb by id
        $query = 'SELECT * FROM tbl_benutzer WHERE id = :id';

        // Execute the query through the Database class
        $stmt = $db->query($query);
        $stmt->execute([':id' => $id]);
        $benutzer = $stmt->fetch();

        if ($benutzer) {
            // Send success response with the found record
            Response::json([
                'status' => 'success',
                'message' => 'Lehrbetrieb found',
                'data' => $benutzer
            ], Response::OK);
        } else {
            // Send a 404 Not Found response if no record is found
            Response::json([
                'status' => 'error',
                'message' => 'Benutzer not found'
            ], Response::NOT_FOUND);
        }
    } catch (Exception $e) {
        // Handle unexpected errors with a 500 Internal Server Error response
        Response::json([
            'status' => 'error',
            'message' => 'An error occurred while retrieving the Benutzer'
        ], Response::SERVER_ERROR);
    }
} else {
    // Send a 400 Bad Request response if 'id' is missing or invalid
    Response::json([
        'status' => 'error',
        'message' => 'Invalid ID parameter'
    ], Response::BAD_REQUEST);
}