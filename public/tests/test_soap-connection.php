<?php
/**
 * Script de Prueba para Depurar Conexión SOAP
 * Coloca este archivo en /public/test-soap-connection.php
 */

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\SoapClientHandler;

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prueba SOAP - SaludTotal</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #39aaa7;
            border-bottom: 3px solid #39aaa7;
            padding-bottom: 10px;
        }
        h2 {
            color: #9bc352;
            margin-top: 30px;
        }
        .test-section {
            margin: 20px 0;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 5px;
            border-left: 4px solid #39aaa7;
        }
        .success {
            color: #9bc352;
            font-weight: bold;
        }
        .error {
            color: #dc3545;
            font-weight: bold;
        }
        pre {
            background: #2d2d2d;
            color: #f8f8f2;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
            font-size: 13px;
            line-height: 1.5;
        }
        .info {
            background: #e7f3ff;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #2196F3;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Diagnóstico de Conexión SOAP</h1>
        
        <?php
        echo '<div class="info">';
        echo '<strong>Hora de prueba:</strong> ' . date('Y-m-d H:i:s') . '<br>';
        echo '<strong>PHP Version:</strong> ' . phpversion();
        echo '</div>';
        
        // TEST 1: Verificar extensión SOAP
        echo '<div class="test-section">';
        echo '<h2>✅ Test 1: Extensión SOAP</h2>';
        if (extension_loaded('soap')) {
            echo '<p class="success">✓ Extensión SOAP está instalada y activa</p>';
        } else {
            echo '<p class="error">✗ Extensión SOAP NO está instalada</p>';
            echo '<p>Por favor instala la extensión PHP SOAP</p>';
        }
        echo '</div>';
        
        // TEST 2: Verificar WSDL
        echo '<div class="test-section">';
        echo '<h2>✅ Test 2: Archivo WSDL</h2>';
        $wsdlPath = __DIR__ . '/wsdl/pacientes.wsdl';
        if (file_exists($wsdlPath)) {
            echo '<p class="success">✓ Archivo WSDL encontrado</p>';
            echo '<p><strong>Ruta:</strong> ' . $wsdlPath . '</p>';
        } else {
            echo '<p class="error">✗ Archivo WSDL NO encontrado</p>';
            echo '<p>Buscado en: ' . $wsdlPath . '</p>';
        }
        echo '</div>';
        
        // TEST 3: Inicializar cliente
        echo '<div class="test-section">';
        echo '<h2>✅ Test 3: Inicialización del Cliente SOAP</h2>';
        try {
            $client = new SoapClientHandler();
            echo '<p class="success">✓ Cliente SOAP inicializado correctamente</p>';
            
            // Mostrar funciones disponibles
            echo '<h3>Funciones disponibles en el WSDL:</h3>';
            $functions = $client->getFunctions();
            echo '<pre>' . print_r($functions, true) . '</pre>';
            
            // Mostrar tipos disponibles
            echo '<h3>Tipos de datos disponibles:</h3>';
            $types = $client->getTypes();
            echo '<pre>' . print_r($types, true) . '</pre>';
            
        } catch (Exception $e) {
            echo '<p class="error">✗ Error al inicializar cliente: ' . $e->getMessage() . '</p>';
        }
        echo '</div>';
        
        // TEST 4: Prueba de conexión con el servidor
        echo '<div class="test-section">';
        echo '<h2>✅ Test 4: Conectividad con el Servidor SOAP</h2>';
        try {
            $response = $client->listarPacientes();
            echo '<p class="success">✓ Conexión exitosa con el servidor SOAP</p>';
            echo '<h3>Respuesta del servidor:</h3>';
            echo '<pre>' . print_r($response, true) . '</pre>';
        } catch (Exception $e) {
            echo '<p class="error">✗ Error de conexión: ' . $e->getMessage() . '</p>';
        }
        echo '</div>';
        
        // TEST 5: Prueba de creación de paciente
        echo '<div class="test-section">';
        echo '<h2>✅ Test 5: Crear Paciente de Prueba</h2>';
        try {
            $datosPrueba = [
                'cedula' => '1234567890',
                'nombres' => 'Juan Carlos',
                'apellidos' => 'Prueba Test',
                'telefono' => '3001234567',
                'fecha_nacimiento' => '1990-01-01',
                'direccion' => 'Calle 123 #45-67',
                'email' => 'prueba@test.com',
                'genero' => 'M'
            ];
            
            echo '<h3>Datos enviados:</h3>';
            echo '<pre>' . print_r($datosPrueba, true) . '</pre>';
            
            $response = $client->crearPaciente($datosPrueba);
            
            echo '<h3>Respuesta del servidor:</h3>';
            echo '<pre>' . print_r($response, true) . '</pre>';
            
            if ($response['exito']) {
                echo '<p class="success">✓ Paciente creado exitosamente</p>';
                
                // Mostrar petición y respuesta SOAP
                echo '<h3>Petición SOAP XML:</h3>';
                echo '<pre>' . htmlspecialchars($client->getLastRequest()) . '</pre>';
                
                echo '<h3>Respuesta SOAP XML:</h3>';
                echo '<pre>' . htmlspecialchars($client->getLastResponse()) . '</pre>';
            } else {
                echo '<p class="error">✗ Error: ' . $response['mensaje'] . '</p>';
                
                // Mostrar petición y respuesta incluso en error
                echo '<h3>Petición SOAP XML (para diagnóstico):</h3>';
                echo '<pre>' . htmlspecialchars($client->getLastRequest()) . '</pre>';
                
                echo '<h3>Respuesta SOAP XML (para diagnóstico):</h3>';
                echo '<pre>' . htmlspecialchars($client->getLastResponse()) . '</pre>';
            }
            
        } catch (Exception $e) {
            echo '<p class="error">✗ Excepción: ' . $e->getMessage() . '</p>';
            echo '<pre>' . $e->getTraceAsString() . '</pre>';
        }
        echo '</div>';
        
        // TEST 6: Prueba de búsqueda
        echo '<div class="test-section">';
        echo '<h2>✅ Test 6: Buscar Paciente</h2>';
        try {
            $response = $client->buscarPaciente('1234567890');
            echo '<h3>Respuesta:</h3>';
            echo '<pre>' . print_r($response, true) . '</pre>';
            
            if ($response['exito']) {
                echo '<p class="success">✓ Búsqueda exitosa</p>';
            } else {
                echo '<p class="error">✗ ' . $response['mensaje'] . '</p>';
            }
        } catch (Exception $e) {
            echo '<p class="error">✗ Error: ' . $e->getMessage() . '</p>';
        }
        echo '</div>';
        
        ?>
        
        <div class="info" style="margin-top: 30px;">
            <strong>💡 Próximos pasos:</strong>
            <ul>
                <li>Si todos los tests son exitosos, el sistema SOAP está funcionando correctamente</li>
                <li>Revisa los XMLs de petición y respuesta para entender la estructura de datos</li>
                <li>Si hay errores, verifica la configuración en <code>config/soap.php</code></li>
                <li>Asegúrate de que la base de datos esté configurada correctamente</li>
            </ul>
        </div>
    </div>
</body>
</html>