<?php
/**
 * Rutas del Cliente SOAP
 * Maneja las peticiones HTTP y las redirige al cliente SOAP
 */

require_once __DIR__ . '/Cliente.php';

// Obtener el cliente SOAP
$cliente = getCliente();

// Responder siempre con JSON
header('Content-Type: application/json');

try {
    // Obtener acción y método
    $action = $_GET['action'] ?? $_POST['action'] ?? null;
    $method = $_SERVER['REQUEST_METHOD'];
    
    // Inicializar respuesta
    $response = [
        'exito' => false,
        'mensaje' => 'Acción no válida',
        'datos' => null
    ];
    
    // Enrutar según la acción
    switch ($action) {
        
        case 'crear':
            // RF-01: Crear paciente
            if ($method === 'POST') {
                $datos = [
                    'cedula' => $_POST['cedula'] ?? '',
                    'nombres' => $_POST['nombres'] ?? '',
                    'apellidos' => $_POST['apellidos'] ?? '',
                    'telefono' => $_POST['telefono'] ?? '',
                    'fecha_nacimiento' => $_POST['fecha_nacimiento'] ?? '',
                    'direccion' => $_POST['direccion'] ?? '',
                    'email' => $_POST['email'] ?? '',
                    'genero' => $_POST['genero'] ?? 'Otro'
                ];
                
                $response = $cliente->crearPaciente($datos);
            }
            break;
            
        case 'buscar':
            // RF-02: Buscar paciente
            if ($method === 'GET' || $method === 'POST') {
                $cedula = $_GET['cedula'] ?? $_POST['cedula'] ?? '';
                $response = $cliente->buscarPaciente($cedula);
            }
            break;
            
        case 'listar':
            // RF-03: Listar pacientes
            if ($method === 'GET') {
                $response = $cliente->listarPacientes();
            }
            break;
            
        case 'actualizar':
            // RF-04: Actualizar paciente
            if ($method === 'POST') {
                $datos = [
                    'cedula' => $_POST['cedula'] ?? '',
                    'nombres' => $_POST['nombres'] ?? '',
                    'apellidos' => $_POST['apellidos'] ?? '',
                    'telefono' => $_POST['telefono'] ?? '',
                    'fecha_nacimiento' => $_POST['fecha_nacimiento'] ?? '',
                    'direccion' => $_POST['direccion'] ?? '',
                    'email' => $_POST['email'] ?? '',
                    'genero' => $_POST['genero'] ?? 'Otro'
                ];
                
                $response = $cliente->actualizarPaciente($datos);
            }
            break;
            
        case 'eliminar':
            // RF-05: Eliminar paciente
            if ($method === 'POST' || $method === 'DELETE') {
                $cedula = $_POST['cedula'] ?? $_GET['cedula'] ?? '';
                $response = $cliente->eliminarPaciente($cedula);
            }
            break;
            
        case 'test':
            // Prueba de conexión
            $response = [
                'exito' => true,
                'mensaje' => 'Cliente SOAP funcionando correctamente',
                'datos' => [
                    'version' => '1.0',
                    'timestamp' => date('Y-m-d H:i:s')
                ]
            ];
            break;
            
        default:
            $response = [
                'exito' => false,
                'mensaje' => "Acción '{$action}' no reconocida",
                'datos' => null
            ];
            break;
    }
    
    // Enviar respuesta
    echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    // Manejar errores
    http_response_code(500);
    echo json_encode([
        'exito' => false,
        'mensaje' => 'Error en el servidor: ' . $e->getMessage(),
        'datos' => null
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}