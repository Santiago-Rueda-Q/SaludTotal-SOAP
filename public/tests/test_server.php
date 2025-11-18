<?php
/**
 * Test del Servidor SOAP
 * Prueba la configuración y disponibilidad del servidor
 */

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Core\SoapConfig;

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Servidor SOAP - SaludTotal</title>
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
            max-width: 900px;
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
            border-bottom: 2px solid #39aaa7;
            padding-bottom: 10px;
        }
        .success {
            background: #d4edda;
            border: 1px solid #9bc352;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
        }
        .error {
            background: #f8d7da;
            border: 1px solid #dc3545;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
        }
        .info {
            background: #d1ecf1;
            border: 1px solid #39aaa7;
            color: #0c5460;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #39aaa7;
            color: white;
            font-weight: 600;
        }
        .status {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .status.ok {
            background: #9bc352;
            color: white;
        }
        .status.fail {
            background: #dc3545;
            color: white;
        }
        pre {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
            font-size: 12px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #39aaa7;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
        }
        .btn:hover {
            background: #2d8885;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>🧪 Test del Servidor SOAP</h1>
            <p style="color: #696a69;">Verificación de configuración y disponibilidad del servicio</p>
        </div>

        <?php
        $allOk = true;

        // Test 1: Verificar extensión SOAP
        echo '<div class="card">';
        echo '<h2>1. Extensión PHP SOAP</h2>';
        if (extension_loaded('soap')) {
            echo '<div class="success">✓ Extensión SOAP habilitada</div>';
        } else {
            echo '<div class="error">✗ Extensión SOAP NO habilitada. Habilítela en php.ini</div>';
            $allOk = false;
        }
        echo '</div>';

        // Test 2: Verificar archivo WSDL
        echo '<div class="card">';
        echo '<h2>2. Archivo WSDL</h2>';
        $wsdlPath = dirname(__DIR__) . '/wsdl/pacientes.wsdl';
        if (file_exists($wsdlPath)) {
            echo '<div class="success">✓ Archivo WSDL encontrado</div>';
            echo '<div class="info"><strong>Ruta:</strong> ' . $wsdlPath . '</div>';
        } else {
            echo '<div class="error">✗ Archivo WSDL NO encontrado</div>';
            $allOk = false;
        }
        echo '</div>';

        // Test 3: Verificar configuración
        echo '<div class="card">';
        echo '<h2>3. Configuración del Sistema</h2>';
        try {
            $config = SoapConfig::getInstance();
            echo '<div class="success">✓ Configuración cargada correctamente</div>';
            
            echo '<table>';
            echo '<tr><th>Parámetro</th><th>Valor</th></tr>';
            echo '<tr><td>WSDL URL</td><td>' . $config->getWsdlUrl() . '</td></tr>';
            echo '<tr><td>Server URL</td><td>' . $config->getServerUrl() . '</td></tr>';
            echo '<tr><td>DB Host</td><td>' . $config->get('database.host') . '</td></tr>';
            echo '<tr><td>DB Name</td><td>' . $config->get('database.name') . '</td></tr>';
            echo '</table>';
        } catch (Exception $e) {
            echo '<div class="error">✗ Error al cargar configuración: ' . $e->getMessage() . '</div>';
            $allOk = false;
        }
        echo '</div>';

        // Test 4: Verificar conexión a base de datos
        echo '<div class="card">';
        echo '<h2>4. Conexión a Base de Datos</h2>';
        try {
            $db = App\Core\XmlManager::getInstance();
            $conn = $db->getConnection();
            
            if ($conn) {
                echo '<div class="success">✓ Conexión a base de datos exitosa</div>';
                
                // Verificar tabla pacientes
                $stmt = $conn->query("SHOW TABLES LIKE 'pacientes'");
                if ($stmt->rowCount() > 0) {
                    echo '<div class="success">✓ Tabla "pacientes" existe</div>';
                    
                    // Contar registros
                    $stmt = $conn->query("SELECT COUNT(*) as total FROM pacientes WHERE estado = 'Activo'");
                    $result = $stmt->fetch();
                    echo '<div class="info">Pacientes activos: <strong>' . $result['total'] . '</strong></div>';
                } else {
                    echo '<div class="error">✗ Tabla "pacientes" NO existe. Ejecute el script database.sql</div>';
                    $allOk = false;
                }
            }
        } catch (Exception $e) {
            echo '<div class="error">✗ Error de conexión: ' . $e->getMessage() . '</div>';
            $allOk = false;
        }
        echo '</div>';

        // Test 5: Verificar servidor SOAP
        echo '<div class="card">';
        echo '<h2>5. Servidor SOAP</h2>';
        try {
            $serverUrl = $config->getServerUrl();
            $ch = curl_init($serverUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($httpCode == 200 || $httpCode == 500) { // 500 es esperado sin SOAP request
                echo '<div class="success">✓ Servidor SOAP accesible</div>';
                echo '<div class="info"><strong>URL:</strong> <a href="' . $serverUrl . '" target="_blank">' . $serverUrl . '</a></div>';
            } else {
                echo '<div class="error">✗ Servidor SOAP no responde (HTTP ' . $httpCode . ')</div>';
                $allOk = false;
            }
        } catch (Exception $e) {
            echo '<div class="error">✗ Error al verificar servidor: ' . $e->getMessage() . '</div>';
            $allOk = false;
        }
        echo '</div>';

        // Test 6: Verificar directorios de escritura
        echo '<div class="card">';
        echo '<h2>6. Permisos de Escritura</h2>';
        $dirs = [
            'storage/logs' => dirname(dirname(__DIR__)) . '/storage/logs',
            'storage' => dirname(dirname(__DIR__)) . '/storage'
        ];
        
        foreach ($dirs as $name => $path) {
            if (is_writable($path)) {
                echo '<div class="success">✓ ' . $name . ' es escribible</div>';
            } else {
                echo '<div class="error">✗ ' . $name . ' NO es escribible. Ejecute: chmod -R 775 ' . $path . '</div>';
                $allOk = false;
            }
        }
        echo '</div>';

        // Resumen final
        echo '<div class="card">';
        echo '<h2>📊 Resumen</h2>';
        if ($allOk) {
            echo '<div class="success">';
            echo '<h3 style="margin-bottom: 10px;">✅ Todos los tests pasaron correctamente</h3>';
            echo '<p>El servidor SOAP está configurado y listo para usar.</p>';
            echo '</div>';
            echo '<a href="../views/index.php" class="btn">Ir al Sistema</a>';
        } else {
            echo '<div class="error">';
            echo '<h3 style="margin-bottom: 10px;">❌ Algunos tests fallaron</h3>';
            echo '<p>Revise los errores anteriores y corríjalos antes de continuar.</p>';
            echo '</div>';
        }
        echo '</div>';
        ?>

        <div class="card" style="background: #f8f9fa; border: 1px dashed #39aaa7;">
            <h2>ℹ️ Información Adicional</h2>
            <p><strong>PHP Version:</strong> <?php echo PHP_VERSION; ?></p>
            <p><strong>Sistema Operativo:</strong> <?php echo PHP_OS; ?></p>
            <p><strong>Fecha/Hora:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
        </div>
    </div>
</body>
</html>