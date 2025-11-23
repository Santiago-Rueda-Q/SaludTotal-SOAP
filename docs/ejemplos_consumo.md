# Ejemplos de consumo del servicio SOAP (PHP)

```php
require 'config/bootstrap.php';

use App\Core\SoapClientHandler;

$client = new SoapClientHandler();

// Crear paciente
$client->createPatient([
    'cedula'           => '123',
    'nombres'          => 'Juan',
    'apellidos'        => 'Perez',
    'telefono'         => '3001234567',
    'fecha_nacimiento' => '1990-01-01',
]);

// Listar pacientes
$pacientes = $client->getAllPatients();
var_dump($pacientes);
```
