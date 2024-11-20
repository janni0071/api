<?php

$router->get('/cats', 'App/Controllers/cats/index.php');
$router->get('/cats/{id}', 'App/Controllers/cats/retrieve.php');