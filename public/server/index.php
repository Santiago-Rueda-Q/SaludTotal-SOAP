<?php
require_once __DIR__ . '/../../config/bootstrap.php';

use App\Core\SoapConfig;
use App\Core\SoapServerHandler;

// Headers para SOAP
header('Content-Type: text/xml; charset=utf-8');

try {
    // Configuración
    $config = SoapConfig::getInstance();
    $wsdlPath = dirname(__DIR__) . '/wsdl/pacientes.wsdl';
    
    if (!file_exists($wsdlPath)) {
        throw new Exception("Archivo WSDL no encontrado: {$wsdlPath}");
    }
    
    // Opciones del servidor con manejo flexible de datos
    $serverOptions = [
        'uri' => $config->getServerUrl(),
        'encoding' => 'UTF-8',
        'soap_version' => SOAP_1_2,
        'cache_wsdl' => WSDL_CACHE_NONE,
        'features' => SOAP_SINGLE_ELEMENT_ARRAYS,
        'typemap' => [
            [
                'type_ns' => 'http://www.w3.org/2001/XMLSchema',
                'type_name' => 'anyType',
                'from_xml' => function($xml) {
                    $data = simplexml_load_string($xml);
                    return json_decode(json_encode($data), true);
                }
            ]
        ]
    ];
    
    // Crear servidor
    $server = new SoapServer($wsdlPath, $serverOptions);
    $server->setClass(SoapServerHandler::class);
    
    // Manejar petición
    $server->handle();
    
} catch (SoapFault $e) {
    header('Content-Type: text/xml; charset=utf-8');
    echo '<?xml version="1.0" encoding="UTF-8"?>';
    echo '<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/">';
    echo '<SOAP-ENV:Body>';
    echo '<SOAP-ENV:Fault>';
    echo '<faultcode>SOAP-ENV:Server</faultcode>';
    echo '<faultstring>' . htmlspecialchars($e->getMessage()) . '</faultstring>';
    echo '</SOAP-ENV:Fault>';
    echo '</SOAP-ENV:Body>';
    echo '</SOAP-ENV:Envelope>';
} catch (Exception $e) {
    http_response_code(500);
    header('Content-Type: text/xml; charset=utf-8');
    echo '<?xml version="1.0" encoding="UTF-8"?>';
    echo '<error>';
    echo '<message>' . htmlspecialchars($e->getMessage()) . '</message>';
    echo '</error>';
}
