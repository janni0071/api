<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

// Get the database connection
$db = DatabaseConnection::getDatabase();

// Read the raw JSON input
$input = json_decode(file_get_contents('php://input'), true);

// Sanitize inputs using htmlspecialchars to prevent HTML special characters in the database
$country = htmlspecialchars($input['country'] ?? '', ENT_QUOTES, 'UTF-8');

// Initial validation: check for empty fields
$errors = [];
if (empty($country)) $errors['country'] = 'Country is required.';

if (!empty($errors)) {
    // Send error response in JSON format with a 400 Bad Request status
    Response::json([
        'status' => 'error',
        'message' => 'Bad request',
        'data' => $errors
    ], Response::BAD_REQUEST);
    exit;
}

// Insert data into the database
$query = 'INSERT INTO tbl_countries (country) VALUES (:country)';

try {
    $stmt = $db->query($query);
    $stmt->execute([
        ':country' => $country
    ]);

    // Send success response in JSON format with a 201 Created status
    Response::json([
        'status' => 'success',
        'message' => 'Land created successfully!'
    ], Response::CREATED);
} catch (Exception $e) {
    // Handle unexpected errors with a 500 Internal Server Error response
    Response::json([
        'status' => 'error',
        'message' => 'An error occurred while creating the Land',
        'data' => ['error' => $e->getMessage()]
    ], Response::SERVER_ERROR);
}