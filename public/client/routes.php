<?php
/**
 * Gateway SOAP (cliente) - expone endpoints internos para el frontend
 */

require_once __DIR__ . '/Cliente.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

try {
    // Obtener cliente SOAP
    $cliente = getCliente();
    
    // Obtener accion de la URL
    $requestUri = $_SERVER['REQUEST_URI'];
    $method = $_SERVER['REQUEST_METHOD'];
    
    // Parsear la ruta para obtener la accion (solo /soap/pacientes)
    if (preg_match('#/soap/pacientes/(.+)#', $requestUri, $matches)) {
        $action = $matches[1];
    } elseif (preg_match('#/soap/pacientes#', $requestUri)) {
        $action = 'listar';
    } else {
        $action = $_GET['action'] ?? $_POST['action'] ?? 'listar';
    }
    
    $response = ['exito' => false, 'mensaje' => 'Accion no valida', 'datos' => null];
    
    error_log("SOAP Gateway Request - Action: $action, Method: $method");
    
    switch ($action) {
        case 'crear':
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
            $cedula = $_GET['cedula'] ?? $_POST['cedula'] ?? '';
            $response = $cliente->buscarPaciente($cedula);
            break;
            
        case 'listar':
        case '':
            $response = $cliente->listarPacientes();
            break;
            
        case 'actualizar':
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
            if ($method === 'POST') {
                $cedula = $_POST['cedula'] ?? $_GET['cedula'] ?? '';
                $response = $cliente->eliminarPaciente($cedula);
            }
            break;
            
        default:
            $response = [
                'exito' => false,
                'mensaje' => "Accion '$action' no reconocida",
                'datos' => null
            ];
            break;
    }
    
    echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'exito' => false,
        'mensaje' => 'Error en el servidor: ' . $e->getMessage(),
        'datos' => null,
        'debug' => [
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}
