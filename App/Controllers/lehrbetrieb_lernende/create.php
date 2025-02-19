<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

// Get the database connection
$db = DatabaseConnection::getDatabase();

// Parse input JSON
$input = json_decode(file_get_contents('php://input'), true);

// Extract and validate inputs
$fk_lehrbetrieb = $input['fk_lehrbetrieb'] ?? null;
$fk_lernende = $input['fk_lernende'] ?? null;
$start = htmlspecialchars($input['start_date'] ?? '', ENT_QUOTES, 'UTF-8');
$ende = htmlspecialchars($input['end_date'] ?? '', ENT_QUOTES, 'UTF-8');
$beruf = htmlspecialchars($input['beruf'] ?? '', ENT_QUOTES, 'UTF-8');

// Check required fields
$errors = [];
if (!$fk_lehrbetrieb || !ctype_digit($fk_lehrbetrieb)) {
    $errors['fk_lehrbetrieb'] = 'Valid fk_lehrbetrieb is required.';
}
if (!$fk_lernende || !ctype_digit($fk_lernende)) {
    $errors['fk_lernende'] = 'Valid fk_lernende is required.';
}
if (empty($start)) {
    $errors['start_date'] = 'Start date is required.';
}
if (empty($ende)) {
    $errors['end_date'] = 'End date is required.';
}
if (empty($beruf)) {
    $errors['beruf'] = 'Beruf is required.';
}
if (!empty($errors)) {
    Response::json([
        'status' => 'error',
        'message' => 'Invalid input',
        'data' => $errors
    ], Response::BAD_REQUEST);
    exit;
}

try {
    // Validate foreign key existence in lehrbetrieb table
    $stmt = $db->query('SELECT COUNT(*) FROM tbl_lehrbetrieb WHERE id_lehrbetrieb = :id');
    $stmt->execute([':id' => $fk_lehrbetrieb]);
    if ($stmt->fetchColumn() == 0) {
        Response::json([
            'status' => 'error',
            'message' => "Lehrbetrieb with ID $fk_lehrbetrieb does not exist."
        ], Response::BAD_REQUEST);
        exit;
    }

    // Validate foreign key existence in lernende table
    $stmt = $db->query('SELECT COUNT(*) FROM tbl_lernende WHERE id_lernende = :id');
    $stmt->execute([':id' => $fk_lernende]);
    if ($stmt->fetchColumn() == 0) {
        Response::json([
            'status' => 'error',
            'message' => "Lehrling with ID $fk_lernende does not exist."
        ], Response::BAD_REQUEST);
        exit;
    }

    // Insert the lehrbetrieb_lernende entry
    $query = 'INSERT INTO tbl_lehrbetrieb_lernende (fk_lehrbetrieb, fk_lernende, start, ende) 
            VALUES (:fk_lehrbetrieb, :fk_lernende, :start, :ende)';
    $stmt = $db->query($query);
    $stmt->execute([
        ':fk_lehrbetrieb' => $fk_lehrbetrieb,
        ':fk_lernende' => $fk_lernende,
        ':start' => $start,
        ':end' => $ende
    ]);

    // Success response
    Response::json([
        'status' => 'success',
        'message' => 'Lehrbetrieb_lernende created successfully!'
    ], Response::CREATED);
} catch (Exception $e) {
    // Handle unexpected errors with a generic error message
    Response::json([
        'status' => 'error',
        'message' => $e
    ], Response::SERVER_ERROR);
}