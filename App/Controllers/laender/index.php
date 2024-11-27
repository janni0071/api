<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

// Get the database connection
$db = DatabaseConnection::getDatabase();

try {
    // Query to select all lehrbetriebe
    $query = 'SELECT * FROM tbl_countries';
    $stmt = $db->query($query);
    $stmt->execute();

    // Fetch all records
    $laender = $stmt->fetchAll();

    // Send success response with the records
    Response::json([
        'status' => 'success',
        'message' => 'Länder retrieved successfully',
        'data' => $laender
    ], Response::OK);
} catch (Exception $e) {
    // Handle unexpected errors with a 500 Internal Server Error response
    Response::json([
        'status' => 'error',
        'message' => 'An error occurred while retrieving Länder',
        'data' => ['error' => $e->getMessage()]
    ], Response::SERVER_ERROR);
}