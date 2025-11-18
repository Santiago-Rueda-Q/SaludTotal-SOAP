<?php
namespace App\Exceptions;

/**
 * Manejador de Excepciones SOAP
 * Gestiona errores y excepciones del sistema
 */
class SoapExceptionHandler
{
    private static $logPath;
    
    /**
     * Inicializar manejador de excepciones
     */
    public static function init()
    {
        self::$logPath = dirname(dirname(__DIR__)) . '/storage/logs/';
        
        // Crear directorio de logs si no existe
        if (!is_dir(self::$logPath)) {
            mkdir(self::$logPath, 0755, true);
        }
        
        // Configurar manejadores
        set_exception_handler([self::class, 'handleException']);
        set_error_handler([self::class, 'handleError']);
    }
    
    /**
     * Manejar excepciones
     */
    public static function handleException($exception)
    {
        $error = [
            'type' => 'Exception',
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        self::logError($error);
        
        // Si es modo debug, mostrar detalles
        if (getenv('APP_DEBUG') === 'true') {
            self::displayError($error);
        } else {
            self::displayGenericError();
        }
    }
    
    /**
     * Manejar errores
     */
    public static function handleError($errno, $errstr, $errfile, $errline)
    {
        $error = [
            'type' => 'Error',
            'code' => $errno,
            'message' => $errstr,
            'file' => $errfile,
            'line' => $errline,
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        self::logError($error);
        
        return true;
    }
    
    /**
     * Registrar error en archivo
     */
    private static function logError($error)
    {
        $logFile = self::$logPath . 'error-' . date('Y-m-d') . '.log';
        
        $logMessage = sprintf(
            "[%s] %s: %s in %s on line %s\n",
            $error['timestamp'],
            $error['type'],
            $error['message'],
            $error['file'] ?? 'Unknown',
            $error['line'] ?? 'Unknown'
        );
        
        if (isset($error['trace'])) {
            $logMessage .= "Stack trace:\n" . $error['trace'] . "\n";
        }
        
        $logMessage .= str_repeat('-', 80) . "\n";
        
        file_put_contents($logFile, $logMessage, FILE_APPEND);
    }
    
    /**
     * Mostrar error detallado (solo en modo debug)
     */
    private static function displayError($error)
    {
        http_response_code(500);
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Error - SaludTotal</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background: #f5f5f5;
                    padding: 20px;
                }
                .error-container {
                    background: white;
                    border-left: 4px solid #d32f2f;
                    padding: 20px;
                    margin: 20px auto;
                    max-width: 1000px;
                    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                }
                h1 {
                    color: #d32f2f;
                    margin: 0 0 10px 0;
                }
                .error-message {
                    background: #ffebee;
                    padding: 15px;
                    margin: 10px 0;
                    border-radius: 4px;
                    color: #c62828;
                }
                .error-location {
                    color: #666;
                    font-size: 14px;
                    margin: 10px 0;
                }
                .error-trace {
                    background: #f5f5f5;
                    padding: 15px;
                    margin: 10px 0;
                    border-radius: 4px;
                    overflow-x: auto;
                    font-family: monospace;
                    font-size: 12px;
                    white-space: pre-wrap;
                }
            </style>
        </head>
        <body>
            <div class="error-container">
                <h1>⚠️ Error del Sistema</h1>
                <div class="error-message">
                    <strong><?= htmlspecialchars($error['message']) ?></strong>
                </div>
                <div class="error-location">
                    <strong>Archivo:</strong> <?= htmlspecialchars($error['file']) ?><br>
                    <strong>Línea:</strong> <?= htmlspecialchars($error['line']) ?>
                </div>
                <?php if (isset($error['trace'])): ?>
                <div class="error-trace">
                    <strong>Stack Trace:</strong><br>
                    <?= htmlspecialchars($error['trace']) ?>
                </div>
                <?php endif; ?>
            </div>
        </body>
        </html>
        <?php
        exit;
    }
    
    /**
     * Mostrar error genérico (modo producción)
     */
    private static function displayGenericError()
    {
        http_response_code(500);
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Error - SaludTotal</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background: #f5f5f5;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    height: 100vh;
                    margin: 0;
                }
                .error-box {
                    background: white;
                    padding: 40px;
                    border-radius: 8px;
                    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                    text-align: center;
                    max-width: 500px;
                }
                h1 {
                    color: #39aaa7;
                    margin: 0 0 20px 0;
                }
                p {
                    color: #696a69;
                    line-height: 1.6;
                }
                .btn {
                    display: inline-block;
                    background: #39aaa7;
                    color: white;
                    padding: 10px 20px;
                    text-decoration: none;
                    border-radius: 4px;
                    margin-top: 20px;
                }
                .btn:hover {
                    background: #2d8885;
                }
            </style>
        </head>
        <body>
            <div class="error-box">
                <h1>⚠️ Ha ocurrido un error</h1>
                <p>Lo sentimos, ha ocurrido un error inesperado. Por favor, intenta nuevamente o contacta al administrador del sistema.</p>
                <a href="/" class="btn">Volver al Inicio</a>
            </div>
        </body>
        </html>
        <?php
        exit;
    }
    
    /**
     * Log de actividad
     */
    public static function logActivity($action, $description)
    {
        $logFile = self::$logPath . 'activity-' . date('Y-m-d') . '.log';
        
        $logMessage = sprintf(
            "[%s] %s: %s\n",
            date('Y-m-d H:i:s'),
            $action,
            $description
        );
        
        file_put_contents($logFile, $logMessage, FILE_APPEND);
    }
}