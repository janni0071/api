<?php

$id = $params['id'] ?? null;

if ($id) {
    // Perform operations with $id
    echo "Updating cat with ID: $id";
    // ... (handle the update logic here)
} else {
    echo "No ID provided.";
}