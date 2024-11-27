<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

// Get the database connection
$db = DatabaseConnection::getDatabase();

// Read the raw JSON input
$input = json_decode(file_get_contents('php://input'), true);

// Sanitize inputs using htmlspecialchars to prevent HTML special characters in the database
$username = htmlspecialchars($input['username'] ?? '', ENT_QUOTES, 'UTF-8');
$api_key = htmlspecialchars($input['api_key'] ?? '', ENT_QUOTES, 'UTF-8');
$created_at = htmlspecialchars($input['created_at'] ?? '', ENT_QUOTES, 'UTF-8');

// Initial validation: check for empty fields
$errors = [];
if (empty($username)) $errors['username'] = 'Username is required.';
if (empty($api_key)) $errors['api_key'] = 'API key is required.';
if (empty($created_at)) $errors['created_at'] = '"created_at" is required.';

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
$query = 'INSERT INTO tbl_benutzer (username, api_key, created_at) VALUES (:username, :api_key, :created_at)';

try {
    $stmt = $db->query($query);
    $stmt->execute([
        ':username' => $username,
        ':api_key' => $api_key,
        ':created_at' => $created_at
    ]);

    // Send success response in JSON format with a 201 Created status
    Response::json([
        'status' => 'success',
        'message' => 'Benutzer created successfully!'
    ], Response::CREATED);
} catch (Exception $e) {
    // Handle unexpected errors with a 500 Internal Server Error response
    Response::json([
        'status' => 'error',
        'message' => 'An error occurred while creating the Benutzer',
        'data' => ['error' => $e->getMessage()]
    ], Response::SERVER_ERROR);
}