<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🧪 Test Conexión Base de Datos</h1><pre>";

// Test 1: Conexión directa
echo "1️⃣ TEST CONEXIÓN MYSQL\n";
try {
    $host = 'localhost';
    $dbname = 'saludtotal';
    $user = 'root';
    $pass = '';
    
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    echo "✅ Conexión exitosa\n\n";
    
    // Test 2: Contar pacientes
    echo "2️⃣ PACIENTES EN LA BASE DE DATOS\n";
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM pacientes WHERE estado = 'Activo'");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Total pacientes activos: " . $result['total'] . "\n\n";
    
    // Test 3: Listar primeros 5
    echo "3️⃣ PRIMEROS 5 PACIENTES\n";
    $stmt = $pdo->query("SELECT cedula, nombres, apellidos FROM pacientes WHERE estado = 'Activo' LIMIT 5");
    $pacientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($pacientes)) {
        echo "⚠️ NO HAY PACIENTES EN LA BASE DE DATOS\n";
        echo "👉 Debes insertar datos manualmente en phpMyAdmin\n";
    } else {
        foreach ($pacientes as $p) {
            echo "  ✓ {$p['nombres']} {$p['apellidos']} - CC: {$p['cedula']}\n";
        }
    }
    
    echo "\n✅ TODAS LAS PRUEBAS PASARON\n";
    
} catch (PDOException $e) {
    echo "❌ ERROR DE CONEXIÓN\n";
    echo "Mensaje: " . $e->getMessage() . "\n";
    echo "\n🔧 SOLUCIÓN:\n";
    echo "1. Verifica que MySQL esté corriendo en Laragon\n";
    echo "2. Crea la base de datos 'saludtotal' en phpMyAdmin\n";
    echo "3. Importa la estructura SQL que te proporcioné\n";
}

echo "</pre>";