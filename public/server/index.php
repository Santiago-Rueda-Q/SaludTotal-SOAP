<?php
/**
 * Servidor SOAP - SaludTotal
 * Publicación del servicio web SOAP para gestión de pacientes
 */

// Autoload de Composer
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Core\SoapConfig;
use App\Core\SoapServerHandler;
use App\Exceptions\SoapExceptionHandler;

// Inicializar manejador de excepciones
SoapExceptionHandler::init();

try {
    // Cargar configuración
    $config = SoapConfig::getInstance();
    
    // Configurar headers para SOAP
    header('Content-Type: text/xml; charset=utf-8');
    
    // Obtener ruta del WSDL
    $wsdlPath = dirname(__DIR__) . '/wsdl/pacientes.wsdl';
    
    if (!file_exists($wsdlPath)) {
        throw new Exception("Archivo WSDL no encontrado: {$wsdlPath}");
    }
    
    // Opciones del servidor
    $serverOptions = $config->getServerOptions();
    $serverOptions['wsdl'] = $wsdlPath;
    
    // Crear servidor SOAP
    $server = new SoapServer($wsdlPath, $serverOptions);
    
    // Asignar la clase que implementa las operaciones
    $server->setClass(SoapServerHandler::class);
    
    // Log de inicio del servidor
    SoapExceptionHandler::logActivity('SERVER_START', 'Servidor SOAP iniciado correctamente');
    
    // Manejar la petición SOAP
    $server->handle();
    
} catch (SoapFault $e) {
    // Error SOAP específico
    SoapExceptionHandler::logActivity('SOAP_ERROR', $e->getMessage());
    
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
    // Error general
    SoapExceptionHandler::logActivity('SERVER_ERROR', $e->getMessage());
    
    header('HTTP/1.1 500 Internal Server Error');
    header('Content-Type: text/xml; charset=utf-8');
    echo '<?xml version="1.0" encoding="UTF-8"?>';
    echo '<error>';
    echo '<message>' . htmlspecialchars($e->getMessage()) . '</message>';
    echo '</error>';
}