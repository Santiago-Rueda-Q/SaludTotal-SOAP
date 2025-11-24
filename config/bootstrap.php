<?php
declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');

define('BASE_PATH', dirname(__DIR__));
define('CONFIG_PATH', BASE_PATH . '/config');
define('APP_PATH', BASE_PATH . '/app');
define('PUBLIC_PATH', BASE_PATH . '/public');
define('STORAGE_PATH', BASE_PATH . '/storage');
define('LOG_PATH', STORAGE_PATH . '/logs');

if (!is_dir(STORAGE_PATH)) {
    mkdir(STORAGE_PATH, 0777, true);
}
if (!is_dir(LOG_PATH)) {
    mkdir(LOG_PATH, 0777, true);
}

// Configs base
$appConfig  = require CONFIG_PATH . '/app.php';
$envConfig  = require CONFIG_PATH . '/env.php';
$soapConfig = require CONFIG_PATH . '/soap.php';

date_default_timezone_set($appConfig['timezone'] ?? 'UTC');

require_once CONFIG_PATH . '/helpers.php';

// Autoload PSR-4 simple para namespace App\
spl_autoload_register(function (string $class): void {
    if (strpos($class, 'App\\') === 0) {
        $relative = str_replace(['App\\', '\\'], ['', '/'], $class) . '.php';
        $path = APP_PATH . '/' . $relative;
        if (file_exists($path)) {
            require_once $path;
        }
    }
});