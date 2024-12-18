<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

// Get the database connection
$db = DatabaseConnection::getDatabase();

// Read the raw JSON input
$input = json_decode(file_get_contents('php://input'), true);

// Sanitize inputs using htmlspecialchars to prevent HTML special characters in the database
$firma = htmlspecialchars($input['firma'] ?? '', ENT_QUOTES, 'UTF-8');
$strasse = htmlspecialchars($input['strasse'] ?? '', ENT_QUOTES, 'UTF-8');
$plz = htmlspecialchars($input['plz'] ?? '', ENT_QUOTES, 'UTF-8');
$ort = htmlspecialchars($input['ort'] ?? '', ENT_QUOTES, 'UTF-8');

// Initial validation: check for empty fields
$errors = [];
if (empty($firma)) $errors['firma'] = 'Firma is required.';
if (empty($strasse)) $errors['strasse'] = 'Strasse is required.';
if (empty($plz)) $errors['plz'] = 'PLZ is required.';
if (empty($ort)) $errors['ort'] = 'Ort is required.';

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
$query = 'INSERT INTO tbl_lehrbetrieb (firma, strasse, plz, ort) VALUES (:firma, :strasse, :plz, :ort)';

try {
    $stmt = $db->query($query);
    $stmt->execute([
        ':firma' => $firma,
        ':strasse' => $strasse,
        ':plz' => $plz,
        ':ort' => $ort
    ]);

    // Send success response in JSON format with a 201 Created status
    Response::json([
        'status' => 'success',
        'message' => 'Lehrbetrieb created successfully!'
    ], Response::CREATED);
} catch (Exception $e) {
    // Handle unexpected errors with a 500 Internal Server Error response
    Response::json([
        'status' => 'error',
        'message' => 'An error occurred while creating the lehrbetrieb',
        'data' => ['error' => $e->getMessage()]
    ], Response::SERVER_ERROR);
}