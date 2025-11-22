<?php
/**
 * Configuración de la Aplicación
 */

return [
    // Información de la Aplicación
    'name' => env('APP_NAME', 'SaludTotal'),
    'env' => env('APP_ENV', 'production'),
    'debug' => env('APP_DEBUG', false),
    'url' => env('APP_URL', 'http://localhost'),
    'base_path' => env('BASE_PATH', ''),
    
    // Zona Horaria
    'timezone' => 'America/Bogota',
    
    // Configuración de Errores
    'error_reporting' => env('APP_DEBUG') ? E_ALL : 0,
    'display_errors' => env('APP_DEBUG') ? 1 : 0,
    
    // Configuración de Base de Datos
    'database' => [
        'host' => env('DB_HOST', 'localhost'),
        'port' => env('DB_PORT', '3306'),
        'name' => env('DB_NAME', 'saludtotal'),
        'user' => env('DB_USER', 'root'),
        'password' => env('DB_PASS', ''),
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci'
    ]
];