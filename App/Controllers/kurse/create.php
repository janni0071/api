<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

// Get the database connection
$db = DatabaseConnection::getDatabase();

// Read the raw JSON input
$input = json_decode(file_get_contents('php://input'), true);

// Sanitize inputs using htmlspecialchars to prevent HTML special characters in the database
$kursnummer = htmlspecialchars($input['kursnummer'] ?? '', ENT_QUOTES, 'UTF-8');
$kursthema = htmlspecialchars($input['kursthema'] ?? '', ENT_QUOTES, 'UTF-8');
$inhalt = htmlspecialchars($input['inhalt'] ?? '', ENT_QUOTES, 'UTF-8');
$fk_dozent = htmlspecialchars($input['fk_dozent'] ?? '', ENT_QUOTES, 'UTF-8');
$startdatum = htmlspecialchars($input['startdatum'] ?? '', ENT_QUOTES, 'UTF-8');
$enddatum = htmlspecialchars($input['enddatum'] ?? '', ENT_QUOTES, 'UTF-8');
$dauer = htmlspecialchars($input['dauer'] ?? '', ENT_QUOTES, 'UTF-8');

// Initial validation: check for empty fields
$errors = [];
if (empty($kursnummer)) $errors['kursnummer'] = 'Kursnummer is required.';
if (empty($kursthema)) $errors['kursthema'] = 'Kursthema is required.';
if (empty($inhalt)) $errors['inhalt'] = 'Inhalt is required.';
if (empty($fk_dozent)) $errors['fk_dozent'] = 'fk_dozent is required.';
if (empty($startdatum)) $errors['startdatum'] = 'Startdatum is required.';
if (empty($enddatum)) $errors['enddatum'] = 'Enddatum is required.';
if (empty($dauer)) $errors['dauer'] = 'Dauer is required.';

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
$query = 'INSERT INTO tbl_kurse (kursnummer, kursthema, inhalt, fk_dozent, startdatum, enddatum, dauer) VALUES (:kursnummer, :kursthema, :inhalt, :fk_dozent, :startdatum, :enddatum, :dauer)';

try {
    $stmt = $db->query($query);
    $stmt->execute([
        ':kursnummer' => $kursnummer,
        ':kursthema' => $kursthema,
        ':inhalt' => $inhalt,
        ':fk_dozent' => $fk_dozent,
        ':startdatum' => $startdatum,
        ':enddatum' => $enddatum,
        ':dauer' => $dauer,
    ]);

    // Send success response in JSON format with a 201 Created status
    Response::json([
        'status' => 'success',
        'message' => 'Kurs created successfully!'
    ], Response::CREATED);
} catch (Exception $e) {
    // Handle unexpected errors with a 500 Internal Server Error response
    Response::json([
        'status' => 'error',
        'message' => 'An error occurred while creating the Kurs',
        'data' => ['error' => $e->getMessage()]
    ], Response::SERVER_ERROR);
}