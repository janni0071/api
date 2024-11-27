<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

// Get the database connection
$db = DatabaseConnection::getDatabase();

try {
    // Define the SQL query with joins for foreign key relationships
    $query = '
        SELECT kl.*, k.kursnummer, k.kursthema, k.inhalt, k.dauer, lern.nachname, lern.vorname, land.country
        FROM tbl_kurse_lernende AS kl
        JOIN tbl_kurse AS k ON kl.fk_kurs = k.id_kurs
        JOIN tbl_lernende AS lern ON kl.fk_lernende = lern.id_lernende
        JOIN tbl_countries AS land ON lern.fk_land = land.id_countries
    ';

    // Execute the query
    $stmt = $db->query($query);
    $stmt->execute();

    $results = $stmt->fetchAll();

    // Check if results were found
    if ($results) {
        Response::json([
            'status' => 'success',
            'message' => 'Entries retrieved successfully',
            'data' => $results
        ], Response::OK);
    } else {
        // No entries found
        Response::json([
            'status' => 'error',
            'message' => 'No entries found'
        ], Response::NOT_FOUND);
    }
} catch (Exception $e) {
    // Handle unexpected errors with a 500 Internal Server Error response
    Response::json([
        'status' => 'error',
        'message' => 'An error occurred while retrieving entries'
    ], Response::SERVER_ERROR);
}