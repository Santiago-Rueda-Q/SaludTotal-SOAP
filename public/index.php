<?php
/**
 * Archivo principal de la aplicación
 * Maneja el enrutamiento y la inicialización
 */

// Cargar bootstrap
require_once __DIR__ . '/../config/bootstrap.php';

// Configurar headers para respuestas
header('X-Powered-By: SaludTotal/1.0');

// Obtener la ruta solicitada
$requestUri = $_SERVER['REQUEST_URI'];
$scriptName = dirname($_SERVER['SCRIPT_NAME']);

// Remover el script name de la URI
$route = str_replace($scriptName, '', $requestUri);
$route = parse_url($route, PHP_URL_PATH);
$route = trim($route, '/');

// Debug: registrar la ruta (opcional, comentar en producción)
error_log("Ruta solicitada: $route");

try {
    // Gateway SOAP para el cliente (respuestas JSON)
    if (strpos($route, 'soap/pacientes') === 0) {
        require_once __DIR__ . '/client/routes.php';
        exit;
    }
    
    // Manejar rutas de vistas (devuelven HTML)
    switch ($route) {
        case '':
        case 'index':
        case 'index.php':
            require_once __DIR__ . '/views/index.php';
            break;

        case 'pacientes':
            require_once __DIR__ . '/views/listar_pacientes.php';
            break;

        case 'pacientes/crear':
            require_once __DIR__ . '/views/crear_paciente.php';
            break;

        case 'pacientes/editar':
            if (isset($_GET['cedula']) || isset($_GET['id'])) {
                // Normalizar parámetro para compatibilidad
                if (!isset($_GET['cedula']) && isset($_GET['id'])) {
                    $_GET['cedula'] = $_GET['id'];
                }
                require_once __DIR__ . '/views/editar_paciente.php';
            } else {
                http_response_code(400);
                echo "Cédula de paciente no proporcionada";
            }
            break;

        default:
            http_response_code(404);
            require_once __DIR__ . '/views/404.php';
            break;
    }
    
} catch (Exception $e) {
    // Manejar errores
    error_log("Error en index.php: " . $e->getMessage());
    
    // Si es una ruta del gateway SOAP, devolver JSON
    if (strpos($route, 'soap/pacientes') === 0) {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => 'Error interno del servidor',
            'message' => $e->getMessage()
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    } else {
        // Si es una vista, mostrar página de error
        http_response_code(500);
        require_once __DIR__ . '/Views/errors/500.php';
    }
}
