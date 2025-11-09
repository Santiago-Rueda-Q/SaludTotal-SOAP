<?php
require_once __DIR__ . '/../../vendor/autoload.php';

$wsdl = __DIR__ . '/../wsdl/pacientes.wsdl';
$client = new SoapClient($wsdl, [
    'trace' => 1,
    'cache_wsdl' => WSDL_CACHE_DISK,
    'exceptions' => true,
]);

// Smoke test: listar
$res = $client->ListarPacientes([]);
print_r($res);
