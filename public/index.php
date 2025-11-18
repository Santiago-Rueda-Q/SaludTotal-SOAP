<?php
/**
 * SaludTotal - Punto de Entrada del Sistema (PUBLIC)
 * Todas las peticiones HTTP procesadas pasan por aquí
 */

// Cargar bootstrap que incluye helpers
require_once __DIR__ . '/../config/bootstrap.php';

// Autoload de Composer
require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;
use App\Core\SoapConfig;
use App\Exceptions\SoapExceptionHandler;

try {
    // Inicializar configuración
    $config = SoapConfig::getInstance();
    
    // Inicializar manejador de excepciones
    SoapExceptionHandler::init();
    
    // Obtener el router
    $router = Router::getInstance();
    
    // Resolver la ruta actual
    $router->resolve();
    
} catch (Exception $e) {
    // Manejar errores globales
    http_response_code(500);
    
    // Mostrar error según ambiente
    if ($config->get('debug', false)) {
        echo "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Error del Sistema</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .error-box { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #dc3545; }
        pre { background: #f8f9fa; padding: 15px; border-radius: 5px; overflow-x: auto; }
        .trace { font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class='error-box'>
        <h1>⚠️ Error del Sistema</h1>
        <p><strong>" . htmlspecialchars($e->getMessage()) . "</strong></p>
        <p><strong>Archivo:</strong> " . htmlspecialchars($e->getFile()) . " 
           <strong>Línea:</strong> " . $e->getLine() . "</p>
        <hr>
        <h3>Stack Trace:</h3>
        <pre class='trace'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>
    </div>
</body>
</html>";
    } else {
        echo "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Error</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; text-align: center; background: #f5f5f5; }
        .error-box { background: white; padding: 50px; border-radius: 8px; max-width: 600px; margin: 100px auto; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #dc3545; }
    </style>
</head>
<body>
    <div class='error-box'>
        <h1>⚠️ Error del Sistema</h1>
        <p>Ha ocurrido un error inesperado. Por favor, contacte al administrador.</p>
        <a href='/' style='display: inline-block; margin-top: 20px; padding: 10px 20px; background: #39aaa7; color: white; text-decoration: none; border-radius: 5px;'>Volver al Inicio</a>
    </div>
</body>
</html>";
    }
    
    // Log del error
    try {
        SoapExceptionHandler::logActivity('SYSTEM_ERROR', $e->getMessage());
    } catch (Exception $logError) {
        error_log("Error al registrar log: " . $logError->getMessage());
    }
}