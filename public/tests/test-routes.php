<?php
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Test de Rutas</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        .test { background: #f5f5f5; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .success { background: #d4edda; color: #155724; }
        .error { background: #f8d7da; color: #721c24; }
        button { padding: 10px 20px; margin: 5px; cursor: pointer; background: #39aaa7; color: white; border: none; border-radius: 5px; }
        pre { background: #2d2d2d; color: #f8f8f2; padding: 10px; border-radius: 5px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>🧪 Test de Rutas - SaludTotal</h1>
    
    <div class="test">
        <h3>Información del Sistema</h3>
        <p><strong>URL Actual:</strong> <?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?></p>
        <p><strong>Document Root:</strong> <?php echo $_SERVER['DOCUMENT_ROOT']; ?></p>
        <p><strong>Script Filename:</strong> <?php echo $_SERVER['SCRIPT_FILENAME']; ?></p>
    </div>
    
    <div class="test">
        <h3>Test 1: Listar Pacientes</h3>
        <button onclick="testAPI('/soap/pacientes', 'GET', null, 'result1')">Probar GET /soap/pacientes</button>
        <div id="result1"></div>
    </div>
    
    <div class="test">
        <h3>Test 2: Crear Paciente</h3>
        <button onclick="testCreate()">Probar POST /soap/pacientes/crear</button>
        <div id="result2"></div>
    </div>
    
    <div class="test">
        <h3>Test 3: Buscar Paciente</h3>
        <button onclick="testAPI('/soap/pacientes/buscar?cedula=1234567890', 'GET', null, 'result3')">Probar Búsqueda</button>
        <div id="result3"></div>
    </div>
    
    <script>
        async function testAPI(url, method, body, resultId) {
            const resultDiv = document.getElementById(resultId);
            resultDiv.innerHTML = '<p>Probando...</p>';
            
            try {
                const options = {
                    method: method,
                    headers: {}
                };
                
                if (body) {
                    options.headers['Content-Type'] = 'application/x-www-form-urlencoded';
                    options.body = new URLSearchParams(body);
                }
                
                const response = await fetch(url, options);
                const data = await response.json();
                
                const className = data.exito ? 'success' : 'error';
                resultDiv.innerHTML = `
                    <div class="${className}">
                        <p><strong>${data.exito ? '✓' : '✗'}</strong> ${data.mensaje}</p>
                        <pre>${JSON.stringify(data, null, 2)}</pre>
                    </div>
                `;
            } catch (error) {
                resultDiv.innerHTML = `<div class="error">✗ Error: ${error.message}</div>`;
            }
        }
        
        async function testCreate() {
            const body = {
                cedula: '9999888877',
                nombres: 'Test',
                apellidos: 'Routing',
                fecha_nacimiento: '2000-01-01',
                genero: 'M',
                telefono: '3001234567',
                email: 'test@test.com',
                direccion: 'Test Address'
            };
            
            await testAPI('/soap/pacientes/crear', 'POST', body, 'result2');
        }
    </script>
</body>
</html>
