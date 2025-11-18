<?php
/**
 * Bootstrap del Sistema
 * Inicialización de componentes y configuración
 * ORDEN CRÍTICO: Este archivo carga todo en el orden correcto
 */

// Prevenir carga múltiple
if (defined('BOOTSTRAP_LOADED')) {
    return;
}
define('BOOTSTRAP_LOADED', true);

// 1. PRIMERO: Cargar variables de entorno y función env()
require_once __DIR__ . '/env.php';

// 2. SEGUNDO: Inicializar autoload de Composer (antes de los helpers para que no haya conflicto)
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
}

// 3. TERCERO: Cargar helpers solo si no fueron autoloaded
if (!class_exists('App\Helpers\RouteHelper')) {
    require_once __DIR__ . '/../app/Helpers/RouteHelper.php';
}

if (!class_exists('App\Helpers\ValidationHelper')) {
    require_once __DIR__ . '/../app/Helpers/ValidationHelper.php';
}

// 4. Configurar zona horaria
date_default_timezone_set(env('TIMEZONE', 'America/Bogota'));

// 5. Configurar manejo de errores según ambiente
if (ENVIRONMENT === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '/../storage/logs/php-errors.log');
}

// 6. Configurar límites de PHP
ini_set('memory_limit', '256M');
ini_set('max_execution_time', '300');
ini_set('upload_max_filesize', '10M');
ini_set('post_max_size', '10M');

// 7. Configurar charset por defecto
ini_set('default_charset', 'UTF-8');
mb_internal_encoding('UTF-8');

// 8. Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_httponly' => true,
        'cookie_secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
        'use_strict_mode' => true,
        'sid_length' => 48,
        'sid_bits_per_character' => 6
    ]);
}

// 9. Configurar cabeceras de seguridad
if (!headers_sent()) {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('X-XSS-Protection: 1; mode=block');
    header('Content-Type: text/html; charset=utf-8');
}

// 10. Verificar y crear directorios necesarios
$requiredDirs = [
    __DIR__ . '/../storage/logs',
    __DIR__ . '/../storage/cache',
    __DIR__ . '/../storage/sessions'
];

foreach ($requiredDirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0777, true);
    }
}

// 11. Definir constantes útiles
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__));
}

if (!defined('APP_PATH')) {
    define('APP_PATH', ROOT_PATH . DS . 'app');
}

if (!defined('PUBLIC_PATH')) {
    define('PUBLIC_PATH', ROOT_PATH . DS . 'public');
}

if (!defined('STORAGE_PATH')) {
    define('STORAGE_PATH', ROOT_PATH . DS . 'storage');
}

if (!defined('CONFIG_PATH')) {
    define('CONFIG_PATH', ROOT_PATH . DS . 'config');
}

// 12. Registrar manejador de errores fatal
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        // Log del error fatal
        $logFile = STORAGE_PATH . '/logs/fatal-errors.log';
        $message = sprintf(
            "[%s] FATAL ERROR: %s in %s on line %d\n",
            date('Y-m-d H:i:s'),
            $error['message'],
            $error['file'],
            $error['line']
        );
        @file_put_contents($logFile, $message, FILE_APPEND);
        
        // Mostrar página de error amigable si no es debug
        if (!APP_DEBUG && !headers_sent()) {
            http_response_code(500);
            include __DIR__ . '/../resources/views/errors/500.php';
        }
    }
});

// 13. Registrar manejador de excepciones no capturadas
set_exception_handler(function($exception) {
    // Log de la excepción
    $logFile = STORAGE_PATH . '/logs/exceptions.log';
    $message = sprintf(
        "[%s] EXCEPTION: %s in %s on line %d\nTrace:\n%s\n\n",
        date('Y-m-d H:i:s'),
        $exception->getMessage(),
        $exception->getFile(),
        $exception->getLine(),
        $exception->getTraceAsString()
    );
    @file_put_contents($logFile, $message, FILE_APPEND);
    
    if (APP_DEBUG) {
        echo "<pre style='background:#fff;padding:20px;border:2px solid #dc3545;'>";
        echo "<h2 style='color:#dc3545;'>Uncaught Exception</h2>";
        echo "<strong>Message:</strong> " . htmlspecialchars($exception->getMessage()) . "<br>";
        echo "<strong>File:</strong> " . htmlspecialchars($exception->getFile()) . "<br>";
        echo "<strong>Line:</strong> " . $exception->getLine() . "<br>";
        echo "<h3>Stack Trace:</h3>";
        echo htmlspecialchars($exception->getTraceAsString());
        echo "</pre>";
    } else {
        http_response_code(500);
        echo "<h1>Error del Sistema</h1>";
        echo "<p>Ha ocurrido un error inesperado. Por favor, intente nuevamente.</p>";
    }
});