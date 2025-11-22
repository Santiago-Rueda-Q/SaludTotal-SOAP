<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\SoapClientHandler;

header('Content-Type: text/plain; charset=utf-8');

try {
    echo "=== PRUEBA SIMPLE DE SOAP ===\n\n";
    
    $client = new SoapClientHandler();
    
    // Test 1: Listar pacientes
    echo "1. Probando listarPacientes()...\n";
    $response = $client->listarPacientes();
    echo "Resultado: " . ($response['exito'] ? '✓ OK' : '✗ ERROR') . "\n";
    echo "Mensaje: " . $response['mensaje'] . "\n\n";
    
    // Test 2: Crear paciente
    echo "2. Probando crearPaciente()...\n";
    $datos = [
        'cedula' => '9876543210',
        'nombres' => 'Prueba',
        'apellidos' => 'SOAP Test',
        'fecha_nacimiento' => '2000-01-01',
        'genero' => 'M',
        'telefono' => '3001234567',
        'email' => 'test@soap.com',
        'direccion' => 'Calle Prueba'
    ];
    
    $response = $client->crearPaciente($datos);
    echo "Resultado: " . ($response['exito'] ? '✓ OK' : '✗ ERROR') . "\n";
    echo "Mensaje: " . $response['mensaje'] . "\n\n";
    
    if (!$response['exito']) {
        echo "=== DEBUG INFO ===\n";
        echo "Request XML:\n" . $client->getLastRequest() . "\n\n";
        echo "Response XML:\n" . $client->getLastResponse() . "\n";
    }
    
} catch (Exception $e) {
    echo "✗ EXCEPCIÓN: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}