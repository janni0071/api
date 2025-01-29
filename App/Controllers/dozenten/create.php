<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

// Get the database connection
$db = DatabaseConnection::getDatabase();

// Read the raw JSON input
$input = json_decode(file_get_contents('php://input'), true);

// Sanitize inputs using htmlspecialchars to prevent HTML special characters in the database
$vorname = htmlspecialchars($input['vorname'] ?? '', ENT_QUOTES, 'UTF-8');
$nachname = htmlspecialchars($input['nachname'] ?? '', ENT_QUOTES, 'UTF-8');
$strasse = htmlspecialchars($input['strasse'] ?? '', ENT_QUOTES, 'UTF-8');
$plz = htmlspecialchars($input['plz'] ?? '', ENT_QUOTES, 'UTF-8');
$ort = htmlspecialchars($input['ort'] ?? '', ENT_QUOTES, 'UTF-8');
$fk_land = htmlspecialchars($input['fk_land'] ?? '', ENT_QUOTES, 'UTF-8');
$geschlecht = htmlspecialchars($input['geschlecht'] ?? '', ENT_QUOTES, 'UTF-8');
$telefon = htmlspecialchars($input['telefon'] ?? '', ENT_QUOTES, 'UTF-8');
$handy = htmlspecialchars($input['handy'] ?? '', ENT_QUOTES, 'UTF-8');
$email = htmlspecialchars($input['email'] ?? '', ENT_QUOTES, 'UTF-8');
$birthdate = htmlspecialchars($input['birthdate'] ?? '', ENT_QUOTES, 'UTF-8');

// Initial validation: check for empty fields
$errors = [];
if (empty($vorname)) $errors['vorname'] = 'Vorname is required.';
if (empty($nachname)) $errors['nachname'] = 'Nachname is required.';

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
$query = 'INSERT INTO tbl_dozenten (vorname, nachname, strasse, plz, ort, fk_land, geschlecht, telefon, handy, email, birthdate) VALUES (:vorname, :nachname, :strasse, :plz, :ort, :fk_land, :geschlecht, :telefon, :handy, :email, :birthdate)';

try {
    $stmt = $db->query($query);
    $stmt->execute([
        ':vorname' => $vorname,
        ':nachname' => $nachname,
        ':strasse' => $strasse,
        ':plz' => $plz,
        ':ort' => $ort,
        ':fk_land' => $fk_land,
        ':geschlecht' => $geschlecht,
        ':telefon' => $telefon,
        ':handy' => $handy,
        ':email' => $email,
        ':birthdate' => $birthdate
    ]);

    // Send success response in JSON format with a 201 Created status
    Response::json([
        'status' => 'success',
        'message' => 'Dozent created successfully!'
    ], Response::CREATED);
} catch (Exception $e) {
    // Handle unexpected errors with a 500 Internal Server Error response
    Response::json([
        'status' => 'error',
        'message' => 'An error occurred while creating the Dozent',
        'data' => ['error' => $e->getMessage()]
    ], Response::SERVER_ERROR);
}