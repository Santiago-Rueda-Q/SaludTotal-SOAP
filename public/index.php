<?php
require_once dirname(__DIR__) . '/config/bootstrap.php';

use App\Core\Router;

$router = new Router();
$router->handle();
