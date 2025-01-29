<?php

// Define resource paths
$resources = [
    "lehrbetriebe" => "App/Controllers/lehrbetriebe",
    "lernende" => "App/Controllers/lernende",
    "lehrbetrieb_lernende" => "App/Controllers/lehrbetrieb_lernende",
    "laender" => "App/Controllers/laender",
    "dozenten" => "App/Controllers/dozenten",
    "kurse" => "App/Controllers/kurse",
    "kurse_lernende" => "App/Controllers/kurse_lernende",
    "benutzer" => "App/Controllers/benutzer"
];

// Define routes for each resource
foreach ($resources as $resource => $resource_path) {
    $router->get("/{$resource}", "{$resource_path}/index.php");
    $router->post("/{$resource}", controller: "{$resource_path}/create.php");
    $router->get("/{$resource}/{id}", "{$resource_path}/read.php");
    $router->put("/{$resource}/{id}", "{$resource_path}/update.php");
    $router->delete("/{$resource}/{id}", "{$resource_path}/delete.php");
}

// Lehrbetriebe
$router->get('/lehrbetriebe', 'App/Controllers/lehrbetriebe/index.php');
$router->post('/lehrbetriebe', 'App/Controllers/lehrbetriebe/create.php');
$router->get('/lehrbetriebe/{id}', 'App/Controllers/lehrbetriebe/read.php');
$router->put('/lehrbetriebe/{id}', 'App/Controllers/lehrbetriebe/update.php');
$router->delete('/lehrbetriebe/{id}', 'App/Controllers/lehrbetriebe/delete.php');

// Lernende
$router->get('/lernende', 'App/Controllers/lernende/index.php');
$router->post('/lernende', 'App/Controllers/lernende/create.php');
$router->get('/lernende/{id}', 'App/Controllers/lernende/read.php');
$router->put('/lernende/{id}', 'App/Controllers/lernende/update.php');
$router->delete('/lernende/{id}', 'App/Controllers/lernende/delete.php');

// Lehrbetrieb_Lernende
$router->get('/lehrbetrieb_lernende', 'App/Controllers/lehrbetrieb_lernende/index.php');
$router->post('/lehrbetrieb_lernende', 'App/Controllers/lehrbetrieb_lernende/create.php');
$router->get('/lehrbetrieb_lernende/{id}', 'App/Controllers/lehrbetrieb_lernende/read.php');
$router->put('/lehrbetrieb_lernende/{id}', 'App/Controllers/lehrbetrieb_lernende/update.php');
$router->delete('/lehrbetrieb_lernende/{id}', 'App/Controllers/lehrbetrieb_lernende/delete.php');

// Länder
$router->get('/laender', 'App/Controllers/laender/index.php');
$router->post('/laender', 'App/Controllers/laender/create.php');
$router->get('/laender/{id}', 'App/Controllers/laender/read.php');
$router->put('/laender/{id}', 'App/Controllers/laender/update.php');
$router->delete('/laender/{id}', 'App/Controllers/laender/delete.php');

// Dozenten
$router->get('/dozenten', 'App/Controllers/dozenten/index.php');
$router->post('/dozenten', 'App/Controllers/dozenten/create.php');
$router->get('/dozenten/{id}', 'App/Controllers/dozenten/read.php');
$router->put('/dozenten/{id}', 'App/Controllers/dozenten/update.php');
$router->delete('/dozenten/{id}', 'App/Controllers/dozenten/delete.php');

// Kurse
$router->get('/kurse', 'App/Controllers/kurse/index.php');
$router->post('/kurse', 'App/Controllers/kurse/create.php');
$router->get('/kurse/{id}', 'App/Controllers/kurse/read.php');
$router->put('/kurse/{id}', 'App/Controllers/kurse/update.php');
$router->delete('/kurse/{id}', 'App/Controllers/kurse/delete.php');

// Kurse_Lernende
$router->get('/kurse_lernende', 'App/Controllers/kurse_lernende/index.php');
$router->post('/kurse_lernende', 'App/Controllers/kurse_lernende/create.php');
$router->get('/kurse_lernende/{id}', 'App/Controllers/kurse_lernende/read.php');
$router->put('/kurse_lernende/{id}', 'App/Controllers/kurse_lernende/update.php');
$router->delete('/kurse_lernende/{id}', 'App/Controllers/kurse_lernende/delete.php');

// Benutzer
$router->get('/benutzer', 'App/Controllers/benutzer/index.php');
$router->post('/benutzer', 'App/Controllers/benutzer/create.php');
$router->get('/benutzer/{id}', 'App/Controllers/benutzer/read.php');
$router->put('/benutzer/{id}', 'App/Controllers/benutzer/update.php');
$router->put('/benutzer/{id}', 'App/Controllers/benutzer/update.php');