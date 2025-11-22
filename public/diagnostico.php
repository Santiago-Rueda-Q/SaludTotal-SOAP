<?php
/**
 * Herramienta de diagnóstico del sistema
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnóstico del Sistema - SaludTotal</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            min-height: 100vh;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        h1 { 
            color: #667eea;
            margin-bottom: 30px;
            font-size: 28px;
            text-align: center;
        }
        .test-section {
            margin-bottom: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            border-left: 4px solid #667eea;
        }
        .test-title {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .test-item {
            padding: 10px;
            margin: 8px 0;
            border-radius: 5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .success {
            background: #d4edda;
            color: #155724;
            border-left: 3px solid #28a745;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            border-left: 3px solid #dc3545;
        }
        .warning {
            background: #fff3cd;
            color: #856404;
            border-left: 3px solid #ffc107;
        }
        .info {
            background: #d1ecf1;
            color: #0c5460;
            border-left: 3px solid #17a2b8;
        }
        .icon { font-size: 20px; }
        .code-block {
            background: #2d3748;
            color: #e2e8f0;
            padding: 15px;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            overflow-x: auto;
            margin: 10px 0;
        }
        .url-list {
            list-style: none;
            padding: 0;
        }
        .url-item {
            padding: 10px;
            margin: 5px 0;
            background: white;
            border-radius: 5px;
            border: 1px solid #dee2e6;
        }
        .url-item a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }
        .url-item a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Diagnóstico Completo del Sistema</h1>

        <?php
        // 1. Test de Archivos Críticos
        echo '<div class="test-section">';
        echo '<div class="test-title">📁 1. Archivos Críticos</div>';
        
        $files = [
            'Bootstrap' => __DIR__ . '/../src/bootstrap.php',
            'Index Principal' => __DIR__ . '/index.php',
            'WSDL' => __DIR__ . '/wsdl/pacientes.wsdl',
            'SOAP Server' => __DIR__ . '/server/index.php',
            'XmlManager' => __DIR__ . '/../src/Core/XmlManager.php',
            'PatientModel' => __DIR__ . '/../src/Models/PatientModel.php',
        ];
        
        foreach ($files as $name => $path) {
            $exists = file_exists($path);
            $class = $exists ? 'success' : 'error';
            $icon = $exists ? '✅' : '❌';
            echo "<div class='test-item $class'>";
            echo "<span class='icon'>$icon</span>";
            echo "<span><strong>$name:</strong> " . ($exists ? "Encontrado" : "NO ENCONTRADO") . "</span>";
            echo "</div>";
        }
        
        echo '</div>';

        // 2. Test de Carga de Clases
        echo '<div class="test-section">';
        echo '<div class="test-title">🔧 2. Carga de Clases</div>';
        
        try {
            require_once __DIR__ . '/../src/bootstrap.php';
            echo "<div class='test-item success'><span class='icon'>✅</span><span>Bootstrap cargado correctamente</span></div>";
            
            $classes = [
                'SaludTotal\Core\XmlManager',
                'SaludTotal\Models\PatientModel',
                'SaludTotal\Controllers\PatientController'
            ];
            
            foreach ($classes as $class) {
                $exists = class_exists($class);
                $className = substr($class, strrpos($class, '\\') + 1);
                $classType = $exists ? 'success' : 'error';
                $icon = $exists ? '✅' : '❌';
                echo "<div class='test-item $classType'>";
                echo "<span class='icon'>$icon</span>";
                echo "<span><strong>$className:</strong> " . ($exists ? "Cargada" : "NO CARGADA") . "</span>";
                echo "</div>";
            }
        } catch (Exception $e) {
            echo "<div class='test-item error'><span class='icon'>❌</span><span>Error: " . htmlspecialchars($e->getMessage()) . "</span></div>";
        }
        
        echo '</div>';

        // 3. Test de Base de Datos
        echo '<div class="test-section">';
        echo '<div class="test-title">💾 3. Base de Datos</div>';
        
        try {
            $xmlManager = new SaludTotal\Core\XmlManager();
            echo "<div class='test-item success'><span class='icon'>✅</span><span>XmlManager inicializado</span></div>";
            
            $patientModel = new SaludTotal\Models\PatientModel($xmlManager);
            $patients = $patientModel->getAll();
            
            echo "<div class='test-item success'><span class='icon'>✅</span><span>PatientModel funciona correctamente</span></div>";
            echo "<div class='test-item info'><span class='icon'>ℹ️</span><span>Total de pacientes en BD: <strong>" . count($patients) . "</strong></span></div>";
            
            if (!empty($patients)) {
                $first = $patients[0];
                echo "<div class='test-item info'><span class='icon'>👤</span><span>Primer paciente: <strong>" . htmlspecialchars($first['nombres'] . ' ' . $first['apellidos']) . "</strong></span></div>";
            }
        } catch (Exception $e) {
            echo "<div class='test-item error'><span class='icon'>❌</span><span>Error de BD: " . htmlspecialchars($e->getMessage()) . "</span></div>";
        }
        
        echo '</div>';

        // 4. Test de API REST
        echo '<div class="test-section">';
        echo '<div class="test-title">🌐 4. Test de API REST</div>';
        
        $apiUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/soap/pacientes';
        
        try {
            $context = stream_context_create([
                'http' => [
                    'method' => 'GET',
                    'header' => 'Accept: application/json',
                    'timeout' => 5
                ]
            ]);
            
            $response = @file_get_contents($apiUrl, false, $context);
            
            if ($response === false) {
                echo "<div class='test-item error'><span class='icon'>❌</span><span>No se pudo conectar a la API</span></div>";
            } else {
                // Verificar si es JSON válido
                $data = json_decode($response, true);
                
                if ($data === null) {
                    echo "<div class='test-item error'><span class='icon'>❌</span><span>La API no devuelve JSON válido</span></div>";
                    echo "<div class='test-item warning'><span class='icon'>⚠️</span><span>Respuesta recibida (primeros 200 caracteres):</span></div>";
                    echo "<div class='code-block'>" . htmlspecialchars(substr($response, 0, 200)) . "...</div>";
                } else {
                    echo "<div class='test-item success'><span class='icon'>✅</span><span>API responde correctamente con JSON</span></div>";
                    
                    if (isset($data['success']) && $data['success']) {
                        echo "<div class='test-item success'><span class='icon'>✅</span><span>Estructura JSON correcta</span></div>";
                        
                        if (isset($data['count'])) {
                            echo "<div class='test-item info'><span class='icon'>📊</span><span>Pacientes en respuesta: <strong>{$data['count']}</strong></span></div>";
                        }
                    } else {
                        echo "<div class='test-item warning'><span class='icon'>⚠️</span><span>La API responde pero con estructura inesperada</span></div>";
                    }
                    
                    echo "<div class='code-block'>" . htmlspecialchars(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) . "</div>";
                }
            }
        } catch (Exception $e) {
            echo "<div class='test-item error'><span class='icon'>❌</span><span>Error: " . htmlspecialchars($e->getMessage()) . "</span></div>";
        }
        
        echo '</div>';

        // 5. URLs del Sistema
        echo '<div class="test-section">';
        echo '<div class="test-title">🔗 5. URLs del Sistema</div>';
        
        $baseUrl = 'http://' . $_SERVER['HTTP_HOST'];
        $urls = [
            '🏠 Inicio' => $baseUrl,
            '📄 WSDL' => $baseUrl . '/public/wsdl/pacientes.wsdl',
            '🔌 SOAP Server' => $baseUrl . '/public/server/index.php',
            '📊 API Listar' => $baseUrl . '/soap/pacientes',
            '👥 Vista Pacientes' => $baseUrl . '/pacientes',
            '🧪 Este diagnóstico' => $baseUrl . '/public/diagnostico.php',
        ];
        
        echo '<ul class="url-list">';
        foreach ($urls as $label => $url) {
            echo "<li class='url-item'><strong>$label:</strong> <a href='$url' target='_blank'>$url</a></li>";
        }
        echo '</ul>';
        
        echo '</div>';

        // 6. Información del Servidor
        echo '<div class="test-section">';
        echo '<div class="test-title">⚙️ 6. Configuración del Servidor</div>';
        
        $serverInfo = [
            'PHP Version' => phpversion(),
            'Server Software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'Document Root' => $_SERVER['DOCUMENT_ROOT'] ?? 'Unknown',
            'SOAP Extension' => extension_loaded('soap') ? 'Instalada ✅' : 'NO INSTALADA ❌',
            'SimpleXML Extension' => extension_loaded('simplexml') ? 'Instalada ✅' : 'NO INSTALADA ❌',
        ];
        
        foreach ($serverInfo as $key => $value) {
            $hasError = (strpos($value, '❌') !== false);
            $class = $hasError ? 'error' : 'info';
            echo "<div class='test-item $class'><span class='icon'>ℹ️</span><span><strong>$key:</strong> $value</span></div>";
        }
        
        echo '</div>';
        ?>
    </div>
</body>
</html>
