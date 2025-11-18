<?php
/**
 * Bootstrap - Inicialización Simple
 */

// Prevenir doble carga
if (defined('BOOTSTRAP_LOADED')) {
    return;
}
define('BOOTSTRAP_LOADED', true);

// 1. Cargar variables de entorno
require_once __DIR__ . '/env.php';

// 2. Autoload de Composer
require_once __DIR__ . '/../vendor/autoload.php';

// 3. Cargar las CLASES helper
require_once __DIR__ . '/../app/Helpers/RouteHelper.php';
require_once __DIR__ . '/../app/Helpers/ValidationHelper.php';

// 4. Cargar funciones GLOBALES (esto es lo más importante)
require_once __DIR__ . '/helpers.php';

// 5. Configuración básica
date_default_timezone_set(env('TIMEZONE', 'America/Bogota'));

if (ENVIRONMENT === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// 6. Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 7. Crear directorios necesarios
$dirs = [
    __DIR__ . '/../storage/logs',
    __DIR__ . '/../storage/cache'
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0777, true);
    }
}