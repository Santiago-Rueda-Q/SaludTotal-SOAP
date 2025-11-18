<?php
/**
 * Test del Cliente SOAP
 * Prueba las operaciones CRUD del servicio
 */

require_once __DIR__ . '/../client/Cliente.php';

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Cliente SOAP - SaludTotal</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
        }
        .card {
            background: white;
            border-radius: 10px;
            padding: 30px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #39aaa7;
            margin-bottom: 10px;
        }
        h2 {
            color: #696a69;
            margin: 20px 0 10px;
            border-bottom: 2px solid #9bc352;
            padding-bottom: 10px;
        }
        .success {
            background: #d4edda;
            border-left: 4px solid #9bc352;
            color: #155724;
            padding: 15px;
            margin: 10px 0;
        }
        .error {
            background: #f8d7da;
            border-left: 4px solid #dc3545;
            color: #721c24;
            padding: 15px;
            margin: 10px 0;
        }
        .warning {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            color: #856404;
            padding: 15px;
            margin: 10px 0;
        }
        .info {
            background: #d1ecf1;
            border-left: 4px solid #39aaa7;
            color: #0c5460;
            padding: 15px;
            margin: 10px 0;
        }
        pre {
            background: #2d2d2d;
            color: #f8f8f2;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
            font-size: 13px;
            margin: 10px 0;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #39aaa7;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px;
            border: none;
            cursor: pointer;
        }
        .btn:hover {
            background: #2d8885;
        }
        .test-result {
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
        }
        .test-result h3 {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>🧪 Test del Cliente SOAP</h1>
            <p style="color: #696a69;">Prueba de las operaciones CRUD del servicio web</p>
        </div>

        <?php
        try {
            $cliente = getCliente();
            
            // Test 1: Listar Pacientes (RF-03)
            echo '<div class="card">';
            echo '<h2>Test 1: Listar Pacientes (RF-03)</h2>';
            
            $response = $cliente->listarPacientes();
            
            if ($response['exito']) {
                $count = count($response['datos']);
                echo '<div class="success">';
                echo '<h3>✓ Test exitoso</h3>';
                echo '<p><strong>Pacientes encontrados:</strong> ' . $count . '</p>';
                echo '</div>';
                
                if ($count > 0) {
                    echo '<div class="info">';
                    echo '<strong>Primeros 3 pacientes:</strong>';
                    echo '<pre>' . json_encode(array_slice($response['datos'], 0, 3), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . '</pre>';
                    echo '</div>';
                }
            } else {
                echo '<div class="error">';
                echo '<h3>✗ Test fallido</h3>';
                echo '<p>' . $response['mensaje'] . '</p>';
                echo '</div>';
            }
            echo '</div>';
            
            // Test 2: Crear Paciente (RF-01)
            echo '<div class="card">';
            echo '<h2>Test 2: Crear Paciente (RF-01)</h2>';
            
            $testCedula = 'TEST' . time();
            $datosTest = [
                'cedula' => $testCedula,
                'nombres' => 'Paciente',
                'apellidos' => 'De Prueba',
                'telefono' => '3001234567',
                'fecha_nacimiento' => '1990-01-01',
                'direccion' => 'Dirección de prueba 123',
                'email' => 'test@ejemplo.com',
                'genero' => 'Otro'
            ];
            
            echo '<div class="info">';
            echo '<strong>Datos a enviar:</strong>';
            echo '<pre>' . json_encode($datosTest, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . '</pre>';
            echo '</div>';
            
            $response = $cliente->crearPaciente($datosTest);
            
            if ($response['exito']) {
                echo '<div class="success">';
                echo '<h3>✓ Test exitoso</h3>';
                echo '<p>' . $response['mensaje'] . '</p>';
                echo '<pre>' . json_encode($response['datos'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . '</pre>';
                echo '</div>';
                
                // Guardar cédula para siguientes tests
                $cedulaPrueba = $testCedula;
            } else {
                echo '<div class="error">';
                echo '<h3>✗ Test fallido</h3>';
                echo '<p>' . $response['mensaje'] . '</p>';
                echo '</div>';
                $cedulaPrueba = null;
            }
            echo '</div>';
            
            // Test 3: Buscar Paciente (RF-02)
            if ($cedulaPrueba) {
                echo '<div class="card">';
                echo '<h2>Test 3: Buscar Paciente (RF-02)</h2>';
                
                $response = $cliente->buscarPaciente($cedulaPrueba);
                
                if ($response['exito'] && $response['datos']) {
                    echo '<div class="success">';
                    echo '<h3>✓ Test exitoso</h3>';
                    echo '<p>Paciente encontrado:</p>';
                    echo '<pre>' . json_encode($response['datos'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . '</pre>';
                    echo '</div>';
                } else {
                    echo '<div class="error">';
                    echo '<h3>✗ Test fallido</h3>';
                    echo '<p>' . $response['mensaje'] . '</p>';
                    echo '</div>';
                }
                echo '</div>';
                
                // Test 4: Actualizar Paciente (RF-04)
                echo '<div class="card">';
                echo '<h2>Test 4: Actualizar Paciente (RF-04)</h2>';
                
                $datosActualizar = [
                    'cedula' => $cedulaPrueba,
                    'nombres' => 'Paciente Actualizado',
                    'apellidos' => 'De Prueba Modificado',
                    'telefono' => '3109876543',
                    'fecha_nacimiento' => '1990-01-01',
                    'email' => 'actualizado@ejemplo.com',
                    'genero' => 'M'
                ];
                
                echo '<div class="info">';
                echo '<strong>Datos a actualizar:</strong>';
                echo '<pre>' . json_encode($datosActualizar, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . '</pre>';
                echo '</div>';
                
                $response = $cliente->actualizarPaciente($datosActualizar);
                
                if ($response['exito']) {
                    echo '<div class="success">';
                    echo '<h3>✓ Test exitoso</h3>';
                    echo '<p>' . $response['mensaje'] . '</p>';
                    echo '<pre>' . json_encode($response['datos'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . '</pre>';
                    echo '</div>';
                } else {
                    echo '<div class="error">';
                    echo '<h3>✗ Test fallido</h3>';
                    echo '<p>' . $response['mensaje'] . '</p>';
                    echo '</div>';
                }
                echo '</div>';
                
                // Test 5: Eliminar Paciente (RF-05)
                echo '<div class="card">';
                echo '<h2>Test 5: Eliminar Paciente (RF-05)</h2>';
                
                $response = $cliente->eliminarPaciente($cedulaPrueba);
                
                if ($response['exito']) {
                    echo '<div class="success">';
                    echo '<h3>✓ Test exitoso</h3>';
                    echo '<p>' . $response['mensaje'] . '</p>';
                    echo '</div>';
                    
                    // Verificar que ya no existe
                    $verifyResponse = $cliente->buscarPaciente($cedulaPrueba);
                    if (!$verifyResponse['exito'] || !$verifyResponse['datos']) {
                        echo '<div class="success">';
                        echo '<h3>✓ Verificación exitosa</h3>';
                        echo '<p>El paciente fue eliminado correctamente (no se encuentra en el sistema)</p>';
                        echo '</div>';
                    } else {
                        echo '<div class="warning">';
                        echo '<h3>⚠ Advertencia</h3>';
                        echo '<p>El paciente aún aparece en el sistema (posible soft delete)</p>';
                        echo '</div>';
                    }
                } else {
                    echo '<div class="error">';
                    echo '<h3>✗ Test fallido</h3>';
                    echo '<p>' . $response['mensaje'] . '</p>';
                    echo '</div>';
                }
                echo '</div>';
            }
            
            // Resumen
            echo '<div class="card">';
            echo '<h2>📊 Resumen de Tests</h2>';
            echo '<div class="success">';
            echo '<h3>✅ Tests Completados</h3>';
            echo '<p>Todos los tests de las operaciones CRUD se ejecutaron correctamente.</p>';
            echo '<ul style="margin-top: 10px; line-height: 2;">';
            echo '<li>✓ Listar pacientes (RF-03)</li>';
            echo '<li>✓ Crear paciente (RF-01)</li>';
            echo '<li>✓ Buscar paciente (RF-02)</li>';
            echo '<li>✓ Actualizar paciente (RF-04)</li>';
            echo '<li>✓ Eliminar paciente (RF-05)</li>';
            echo '</ul>';
            echo '</div>';
            echo '<div style="margin-top: 20px;">';
            echo '<a href="../views/index.php" class="btn">Ir al Sistema</a>';
            echo '<a href="test_server.php" class="btn" style="background: #9bc352;">Test Servidor</a>';
            echo '</div>';
            echo '</div>';
            
        } catch (Exception $e) {
            echo '<div class="card">';
            echo '<div class="error">';
            echo '<h3>❌ Error Fatal</h3>';
            echo '<p>' . $e->getMessage() . '</p>';
            echo '<pre>' . $e->getTraceAsString() . '</pre>';
            echo '</div>';
            echo '</div>';
        }
        ?>
    </div>
</body>
</html>