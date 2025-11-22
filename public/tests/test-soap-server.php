<?php
/**
 * Test del servidor SOAP
 */

// Evitar que PHP agregue declaraciones use automáticamente
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir bootstrap
require_once __DIR__ . '/../../src/bootstrap.php';

// Probar creación del servidor SOAP
try {
    echo "🧪 Probando Servidor SOAP\n\n";
    
    // Verificar que las clases existan
    if (!class_exists('SaludTotal\Core\XmlManager')) {
        die("❌ Error: Clase XmlManager no encontrada\n");
    }
    
    if (!class_exists('SaludTotal\Models\PatientModel')) {
        die("❌ Error: Clase PatientModel no encontrada\n");
    }
    
    echo "✅ Clases cargadas correctamente\n";
    
    // Probar XmlManager
    $xmlManager = new SaludTotal\Core\XmlManager();
    echo "✅ XmlManager inicializado\n";
    
    // Probar PatientModel
    $patientModel = new SaludTotal\Models\PatientModel($xmlManager);
    echo "✅ PatientModel inicializado\n";
    
    // Probar obtención de pacientes
    $patients = $patientModel->getAll();
    echo "✅ Total de pacientes: " . count($patients) . "\n";
    
    if (!empty($patients)) {
        echo "✅ Primer paciente: " . $patients[0]['nombres'] . " " . $patients[0]['apellidos'] . "\n";
    }
    
    // Probar creación del servidor SOAP
    $wsdlPath = __DIR__ . '/../../public/wsdl/pacientes.wsdl';
    
    if (!file_exists($wsdlPath)) {
        die("❌ Error: Archivo WSDL no encontrado en: $wsdlPath\n");
    }
    
    echo "✅ Archivo WSDL encontrado\n";
    
    // Intentar crear el servidor SOAP
    $server = new SoapServer($wsdlPath, [
        'cache_wsdl' => WSDL_CACHE_NONE,
        'trace' => 1
    ]);
    
    echo "✅ SoapServer creado correctamente\n";
    
    echo "\n✅ Todas las pruebas pasaron exitosamente\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Archivo: " . $e->getFile() . "\n";
    echo "Línea: " . $e->getLine() . "\n";
}