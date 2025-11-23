<?php
require_once dirname(__DIR__, 2) . '/config/bootstrap.php';

use App\Core\SoapClientHandler;

$client = new SoapClientHandler();
var_dump($client->getAllPatients());
