<?php
/**
 * SaludTotal - Punto de Entrada Principal (RAÍZ)
 * Este archivo redirige todo al sistema de rutas en /public
 */

// Redirigir al directorio public
$requestUri = $_SERVER['REQUEST_URI'];
$scriptName = $_SERVER['SCRIPT_NAME'];
$basePath = dirname($scriptName);

// Si ya estamos en public, no redirigir
if (strpos($requestUri, '/public') !== false) {
    // Ya estamos en public, no hacer nada
    exit;
}

// Construir la URL correcta
$publicPath = rtrim($basePath, '/') . '/public' . $requestUri;

// Redirigir
header('Location: ' . $publicPath);
exit;