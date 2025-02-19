<?php

use App\Core\DatabaseConnection;
use App\Core\Response;

// Get the database connection
$db = DatabaseConnection::getDatabase();

try {
    // Define the SQL query with joins for foreign key relationships
    $query = '
        SELECT ll.*, l.firma, l.strasse, l.plz, l.ort, lern.nachname, lern.vorname, land.country
        FROM tbl_lehrbetrieb_lernende AS ll
        JOIN tbl_lehrbetrieb AS l ON ll.fk_lehrbetrieb = l.id_lehrbetrieb
        JOIN tbl_lernende AS lern ON ll.fk_lernende = lern.id_lernende
        JOIN tbl_countries AS land ON lern.fk_land = land.id_countries
    ';

    // Execute the query
    $stmt = $db->query($query);
    $stmt->execute();

    $results = $stmt->fetchAll();

    // Check if results were found
    Response::json([
        'status' => 'success',
        'message' => 'Entries retrieved successfully',
        'data' => $results
    ], Response::OK);
} catch (Exception $e) {
    // Handle unexpected errors with a 500 Internal Server Error response
    Response::json([
        'status' => 'error',
        'message' => 'An error occurred while retrieving entries'
    ], Response::SERVER_ERROR);
}