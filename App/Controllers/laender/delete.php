<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

// Get the database connection
$db = DatabaseConnection::getDatabase();

if (isset($params['id']) && ctype_digit($params['id'])) {

    $id = (int) $params['id'];

    try {
        // Delete the countries by ID
        $query = 'DELETE FROM tbl_countries WHERE id_countries = :id_countries';

        $stmt = $db->query($query);
        $stmt->execute([':id_countries' => $id]);
        $countries = $stmt->fetch();

        if ($stmt->rowCount() > 0) {
            // Send success response in JSON format
            Response::json([
                'status' => 'success',
                'message' => 'Country deleted successfully!'
            ], Response::OK);
        } else {
            // If no rows were affected, the ID was not found
            Response::json([
                'status' => 'error',
                'message' => 'Country not found.'
            ], Response::NOT_FOUND);
        }
    } catch (Exception $e) {
        // Handle unexpected errors with a 500 Internal Server Error response
        Response::json([
            'status' => 'error',
            'message' => 'An error occurred while deleting the Country',
            'data' => ['error' => $e->getMessage()]
        ], Response::SERVER_ERROR);
    }

} else {
    // Send a 400 Bad Request response if 'id' is missing or invalid
    Response::json([
        'status' => 'error',
        'message' => 'Invalid ID parameter'
    ], Response::BAD_REQUEST);
}
