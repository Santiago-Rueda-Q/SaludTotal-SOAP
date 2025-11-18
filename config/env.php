<?php
/**
 * Cargador de Variables de Entorno
 * Este archivo DEBE cargarse PRIMERO antes que todo
 */

// Función para cargar el archivo .env
function loadEnv($path) {
    if (!file_exists($path)) {
        return;
    }
    
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Ignorar comentarios
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        
        // Parsear línea KEY=VALUE
        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);
            
            // Remover comillas si existen
            $value = trim($value, '"\'');
            
            // Establecer como variable de entorno y global
            if (!isset($_ENV[$name])) {
                putenv("$name=$value");
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }
    }
}

// Cargar el archivo .env desde la raíz del proyecto
$envPath = __DIR__ . '/../.env';
loadEnv($envPath);

// Función helper para obtener variables de entorno
if (!function_exists('env')) {
    /**
     * Obtener variable de entorno con valor por defecto
     * 
     * @param string $key Nombre de la variable
     * @param mixed $default Valor por defecto
     * @return mixed
     */
    function env($key, $default = null) {
        // Buscar en $_ENV
        if (isset($_ENV[$key])) {
            return parseEnvValue($_ENV[$key]);
        }
        
        // Buscar en $_SERVER
        if (isset($_SERVER[$key])) {
            return parseEnvValue($_SERVER[$key]);
        }
        
        // Buscar con getenv()
        $value = getenv($key);
        if ($value !== false) {
            return parseEnvValue($value);
        }
        
        return $default;
    }
}

// Función para parsear valores especiales
if (!function_exists('parseEnvValue')) {
    /**
     * Parsear valores especiales de variables de entorno
     * 
     * @param string $value
     * @return mixed
     */
    function parseEnvValue($value) {
        if ($value === '') {
            return '';
        }
        
        // Convertir valores booleanos
        $lower = strtolower($value);
        if ($lower === 'true' || $lower === '(true)') {
            return true;
        }
        if ($lower === 'false' || $lower === '(false)') {
            return false;
        }
        
        // Convertir null
        if ($lower === 'null' || $lower === '(null)') {
            return null;
        }
        
        // Convertir valores vacíos
        if ($lower === 'empty' || $lower === '(empty)') {
            return '';
        }
        
        return $value;
    }
}

// Definir constantes de entorno
if (!defined('ENVIRONMENT')) {
    define('ENVIRONMENT', env('APP_ENV', 'production'));
}

if (!defined('APP_DEBUG')) {
    define('APP_DEBUG', env('APP_DEBUG', false));
}