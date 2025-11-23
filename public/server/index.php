<?php

require_once dirname(__DIR__, 2) . '/config/bootstrap.php';

use App\Core\SoapConfig;
use App\Core\SoapServerHandler;

$wsdl = SoapConfig::getWsdlPath();

$server = new SoapServer($wsdl, [
    'uri' => 'http://saludtotal.com/pacientes',
]);

$server->setClass(SoapServerHandler::class);
$server->handle();
