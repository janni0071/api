<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

// Get the database connection
$db = DatabaseConnection::getDatabase();

// Check if 'id' is set in $params and is a valid integer
if (isset($params['id']) && ctype_digit($params['id'])) {
    $id = (int) $params['id'];

    try {
        // Read the raw JSON input
        $input = json_decode(file_get_contents('php://input'), true);

        // Initialize an array to store the fields to update
        $fieldsToUpdate = [];
        $params = [];

        // Check each field and add to the update list if provided
        if (isset($input['firma'])) {
            $fieldsToUpdate[] = 'firma = :firma';
            $params[':firma'] = htmlspecialchars($input['firma'], ENT_QUOTES, 'UTF-8');
        }
        if (isset($input['strasse'])) {
            $fieldsToUpdate[] = 'strasse = :strasse';
            $params[':strasse'] = htmlspecialchars($input['strasse'], ENT_QUOTES, 'UTF-8');
        }
        if (isset($input['plz'])) {
            $fieldsToUpdate[] = 'plz = :plz';
            $params[':plz'] = htmlspecialchars($input['plz'], ENT_QUOTES, 'UTF-8');
        }
        if (isset($input['ort'])) {
            $fieldsToUpdate[] = 'ort = :ort';
            $params[':ort'] = htmlspecialchars($input['ort'], ENT_QUOTES, 'UTF-8');
        }

        // If no fields are provided, send a 400 Bad Request response
        if (empty($fieldsToUpdate)) {
            Response::json([
                'status' => 'error',
                'message' => 'No fields to update'
            ], Response::BAD_REQUEST);
            exit;
        }

        // Add the ID to the params for the WHERE clause
        $params[':id'] = $id;

        // Construct the SQL query with dynamic SET clause
        $query = 'UPDATE tbl_lehrbetrieb SET ' . implode(', ', $fieldsToUpdate) . ' WHERE id_lehrbetrieb = :id';
        
        // Execute the query through the Database class
        $stmt = $db->query($query);
        $stmt->execute($params);

        // Send a success response
        Response::json([
            'status' => 'success',
            'message' => 'Lehrbetrieb updated successfully!'
        ], Response::OK);

    } catch (Exception $e) {
        // Handle unexpected errors with a 500 Internal Server Error response
        Response::json([
            'status' => 'error',
            'message' => 'An error occurred while updating the lehrbetrieb'
        ], Response::SERVER_ERROR);
    }
} else {
    // Send a 400 Bad Request response if 'id' is missing or invalid
    Response::json([
        'status' => 'error',
        'message' => 'Invalid ID parameter'
    ], Response::BAD_REQUEST);
}