<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

// Get the database connection
$db = DatabaseConnection::getDatabase();

// Parse input JSON
$input = json_decode(file_get_contents('php://input'), true);

// Extract and validate inputs
$fk_lernende = $input['fk_lernende'] ?? null;
$fk_kurs = $input['fk_kurs'] ?? null;
$role = htmlspecialchars($input['role'] ?? '', ENT_QUOTES, 'UTF-8');

// Check required fields
$errors = [];
if (!$fk_lernende || !ctype_digit($fk_lernende)) {
    $errors['fk_lernende'] = 'Valid fk_lernende is required.';
}
if (!$fk_kurs || !ctype_digit($fk_kurs)) {
    $errors['fk_kurs'] = 'Valid fk_kurs is required.';
}
if (empty($role)) {
    $errors['role'] = 'Note is required.';
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
    $stmt = $db->query('SELECT COUNT(*) FROM tbl_kurse WHERE id_kurs = :id');
    $stmt->execute([':id' => $fk_kurs]);
    if ($stmt->fetchColumn() == 0) {
        Response::json([
            'status' => 'error',
            'message' => "Kurs with ID $fk_kurs does not exist."
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
    $query = 'INSERT INTO tbl_kurse_lernende (fk_lernende, fk_kurs, role) 
            VALUES (:fk_lernende, :fk_kurs, :role)';
    $stmt = $db->query($query);
    $stmt->execute([
        ':fk_lernende' => $fk_lernende,
        ':fk_kurs' => $fk_kurs,
        ':role' => $role
    ]);

    // Success response
    Response::json([
        'status' => 'success',
        'message' => 'Kurs_lernende created successfully!'
    ], Response::CREATED);
} catch (Exception $e) {
    // Handle unexpected errors with a generic error message
    Response::json([
        'status' => 'error',
        'message' => 'An error occurred while creating the entry.'
    ], Response::SERVER_ERROR);
}