<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use GinpacSoap\Core\SoapServerHandler;

header('Content-Type: text/xml; charset=UTF-8');
SoapServerHandler::publish();
