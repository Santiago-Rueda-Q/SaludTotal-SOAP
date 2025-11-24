<?php

use App\Core\SoapClientHandler;

$soapClient = new SoapClientHandler();

$routes = require __DIR__ . '/routes.php';

$action = $_GET['action'] ?? 'home';

if (!isset($routes[$action])) {
    $view = view_path('404.php');
    require $view;
    exit;
}

$handler = $routes[$action];

$handler($soapClient);
