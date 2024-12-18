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
        if (isset($input['kursnummer'])) {
            $fieldsToUpdate[] = 'kursnummer = :kursnummer';
            $params[':kursnummer'] = htmlspecialchars($input['kursnummer'], ENT_QUOTES, 'UTF-8');
        }
        if (isset($input['kursthema'])) {
            $fieldsToUpdate[] = 'kursthema = :kursthema';
            $params[':kursthema'] = htmlspecialchars($input['kursthema'], ENT_QUOTES, 'UTF-8');
        }
        if (isset($input['inhalt'])) {
            $fieldsToUpdate[] = 'inhalt = :inhalt';
            $params[':inhalt'] = htmlspecialchars($input['inhalt'], ENT_QUOTES, 'UTF-8');
        }
        if (isset($input['fk_dozent'])) {
            $fieldsToUpdate[] = 'fk_dozent = :fk_dozent';
            $params[':fk_dozent'] = htmlspecialchars($input['fk_dozent'], ENT_QUOTES, 'UTF-8');
        }
        if (isset($input['startdatum'])) {
            $fieldsToUpdate[] = 'startdatum = :startdatum';
            $params[':startdatum'] = htmlspecialchars($input['startdatum'], ENT_QUOTES, 'UTF-8');
        }
        if (isset($input['enddatum'])) {
            $fieldsToUpdate[] = 'enddatum = :enddatum';
            $params[':enddatum'] = htmlspecialchars($input['enddatum'], ENT_QUOTES, 'UTF-8');
        }
        if (isset($input['dauer'])) {
            $fieldsToUpdate[] = 'dauer = :dauer';
            $params[':dauer'] = htmlspecialchars($input['dauer'], ENT_QUOTES, 'UTF-8');
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
        $query = 'UPDATE tbl_kurse SET ' . implode(', ', $fieldsToUpdate) . ' WHERE id_kurs = :id';
        
        // Execute the query through the Database class
        $stmt = $db->query($query);
        $stmt->execute($params);

        // Send a success response
        Response::json([
            'status' => 'success',
            'message' => 'Kurs updated successfully!'
        ], Response::OK);

    } catch (Exception $e) {
        // Handle unexpected errors with a 500 Internal Server Error response
        Response::json([
            'status' => 'error',
            'message' => 'An error occurred while updating the Kurs'
        ], Response::SERVER_ERROR);
    }
} else {
    // Send a 400 Bad Request response if 'id' is missing or invalid
    Response::json([
        'status' => 'error',
        'message' => 'Invalid ID parameter'
    ], Response::BAD_REQUEST);
}