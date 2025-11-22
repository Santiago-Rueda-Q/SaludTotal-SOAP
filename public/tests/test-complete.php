<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Test Completo</title>";
echo "<style>body{font-family:monospace;padding:20px;background:#f5f5f5}pre{background:#fff;padding:15px;border:1px solid #ddd;border-radius:5px}.success{color:green}.error{color:red}.warning{color:orange}</style>";
echo "</head><body>";
echo "<h1>🧪 Test Completo del Sistema</h1>";

// TEST 1: Base de Datos
echo "<h2>1️⃣ Base de Datos</h2><pre>";
try {
    $pdo = new PDO("mysql:host=localhost;dbname=saludtotal;charset=utf8mb4", 'root', '');
    echo "<span class='success'>✅ Conexión a BD exitosa</span>\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM pacientes WHERE estado = 'Activo'");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<span class='success'>✅ Total pacientes: " . $result['total'] . "</span>\n";
    
    if ($result['total'] == 0) {
        echo "<span class='warning'>⚠️ No hay pacientes, debes insertar datos de prueba</span>\n";
    } else {
        echo "<span class='success'>✅ Hay " . $result['total'] . " pacientes activos</span>\n";
    }
} catch (Exception $e) {
    echo "<span class='error'>❌ Error BD: " . $e->getMessage() . "</span>\n";
}
echo "</pre>";

// TEST 2: Archivos críticos
echo "<h2>2️⃣ Archivos Críticos</h2><pre>";
$files = [
    'Bootstrap' => __DIR__ . '/../../config/bootstrap.php',
    'Main.js' => __DIR__ . '/../assets/js/main.js',
    'Alerts.js' => __DIR__ . '/../assets/js/alerts.js',
    'WSDL' => __DIR__ . '/../wsdl/pacientes.wsdl',
    'SOAP Server' => __DIR__ . '/../server/index.php'
];

foreach ($files as $name => $path) {
    if (file_exists($path)) {
        echo "<span class='success'>✅ $name existe</span>\n";
    } else {
        echo "<span class='error'>❌ $name NO EXISTE: $path</span>\n";
    }
}
echo "</pre>";

// TEST 3: Cargar Bootstrap y probar SOAP
echo "<h2>3️⃣ Sistema SOAP</h2><pre>";
try {
    require_once __DIR__ . '/../../config/bootstrap.php';
    echo "<span class='success'>✅ Bootstrap cargado</span>\n";
    
    // Importar clases DESPUÉS de require
    $xmlManager = \App\Core\XmlManager::getInstance();
    echo "<span class='success'>✅ XmlManager inicializado</span>\n";
    
    $patient = new \App\Models\Patient();
    $pacientes = $patient->getAll();
    echo "<span class='success'>✅ PatientModel funciona - Total: " . count($pacientes) . " pacientes</span>\n";
    
    if (count($pacientes) > 0) {
        echo "<span class='success'>✅ Primer paciente: " . $pacientes[0]->nombres . " " . $pacientes[0]->apellidos . "</span>\n";
    }
    
} catch (Exception $e) {
    echo "<span class='error'>❌ Error SOAP: " . $e->getMessage() . "</span>\n";
    echo "<span class='error'>Archivo: " . $e->getFile() . "</span>\n";
    echo "<span class='error'>Línea: " . $e->getLine() . "</span>\n";
}
echo "</pre>";

// TEST 4: URLs
echo "<h2>4️⃣ URLs del Sistema</h2><pre>";
$baseUrl = 'http://saludtotal-soap.test';
$urls = [
    'Inicio' => $baseUrl,
    'WSDL' => $baseUrl . '/public/wsdl/pacientes.wsdl',
    'SOAP Server' => $baseUrl . '/public/server/index.php',
    'API Listar' => $baseUrl . '/soap/pacientes',
    'Vista Listar Pacientes' => $baseUrl . '/pacientes'
];

echo "Prueba estas URLs:\n\n";
foreach ($urls as $name => $url) {
    echo "📌 <strong>$name:</strong>\n   <a href='$url' target='_blank'>$url</a>\n\n";
}
echo "</pre>";

// TEST 5: Test API directamente
echo "<h2>5️⃣ Test API Directamente</h2><pre>";
try {
    $apiUrl = $baseUrl . '/soap/pacientes';
    $context = stream_context_create([
        'http' => [
            'timeout' => 5,
            'ignore_errors' => true
        ]
    ]);
    
    $response = @file_get_contents($apiUrl, false, $context);
    
    if ($response) {
        $data = json_decode($response, true);
        if ($data && isset($data['exito'])) {
            echo "<span class='success'>✅ API responde correctamente</span>\n";
            echo "<span class='success'>✅ Exito: " . ($data['exito'] ? 'true' : 'false') . "</span>\n";
            echo "<span class='success'>✅ Mensaje: " . ($data['mensaje'] ?? 'N/A') . "</span>\n";
            if (isset($data['datos']) && is_array($data['datos'])) {
                echo "<span class='success'>✅ Datos: " . count($data['datos']) . " pacientes</span>\n";
            }
        } else {
            echo "<span class='warning'>⚠️ API responde pero formato incorrecto</span>\n";
            echo "Respuesta: " . substr($response, 0, 200) . "...\n";
        }
    } else {
        echo "<span class='error'>❌ API no responde</span>\n";
        echo "URL: $apiUrl\n";
    }
} catch (Exception $e) {
    echo "<span class='error'>❌ Error al probar API: " . $e->getMessage() . "</span>\n";
}
echo "</pre>";

echo "<h2>✅ Tests Completados</h2>";
echo "<p><strong>Siguiente paso:</strong> Accede a <a href='{$baseUrl}/pacientes' target='_blank'>{$baseUrl}/pacientes</a></p>";
echo "</body></html>";
