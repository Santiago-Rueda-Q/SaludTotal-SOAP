# Ejemplos de Consumo del Servicio SOAP (SaludTotal)

Este documento muestra cómo consumir el servicio web SOAP de **SaludTotal** desde
scripts externos, usando **PHP nativo** con `SoapClient`.  
El objetivo es que cualquier desarrollador pueda probar el CRUD de pacientes
sin conocer los detalles internos de la aplicación.

---

## 1. URL del WSDL

El servicio expone su contrato WSDL desde la carpeta `public/wsdl`.

Por defecto, en un entorno local con **Laragon** y dominio:

```text
http://saludtotal-soap.test
````

la URL del WSDL es:

```text
http://saludtotal-soap.test/public/wsdl/pacientes.wsdl
```

Esta URL se construye a partir de la configuración en:

* `config/env.php`
* `config/soap.php`
* `app/Core/SoapConfig.php`

Si cambias el dominio o el path del proyecto, solo debes actualizar la
configuración, manteniendo el consumo igual.

---

## 2. Operaciones disponibles

El servicio SOAP expone las siguientes operaciones para la gestión de pacientes
(vistas también en `SoapServerHandler` y `SoapClientHandler`):

1. `createPatient(cedula, nombres, apellidos, telefono, fecha_nacimiento)`
2. `getPatientByCedula(cedula)`
3. `getAllPatients()`
4. `updatePatient(cedula, nombres, apellidos, telefono, fecha_nacimiento)`
5. `deletePatient(cedula)`

Todas estas operaciones trabajan contra el archivo XML:

```text
storage/pacientes.xml
```

---

## 3. Ejemplo rápido: script de prueba en PHP

Este ejemplo muestra un archivo independiente (por ejemplo
`tests/consumo-externo.php`) que **consume directamente el WSDL** y ejecuta
las 5 operaciones básicas.

### 3.1. Script completo

```php
<?php
/**
 * Ejemplo de consumo externo del servicio SOAP SaludTotal
 * --------------------------------------------------------
 * Requisitos:
 * - PHP con extensión SOAP habilitada.
 * - Servidor corriendo y WSDL accesible.
 */

// 1. URL del WSDL (ajústala según tu entorno)
$wsdl = 'http://saludtotal-soap.test/public/wsdl/pacientes.wsdl';

try {
    // 2. Instanciar SoapClient
    $client = new SoapClient($wsdl, [
        'trace'      => true,   // Permite ver la request/response si se requiere
        'exceptions' => true,   // Lanza excepciones SoapFault
    ]);

    echo "✅ Conexión SOAP establecida correctamente.\n\n";

    // 3. CREAR PACIENTE (createPatient)
    echo "=== 1) Crear paciente ===\n";

    $cedula = '1001';
    $nombres = 'Juan Carlos';
    $apellidos = 'Pérez Gómez';
    $telefono = '+57 300 123 4567';
    $fechaNacimiento = '1990-05-10'; // Formato: YYYY-MM-DD

    $created = $client->createPatient(
        $cedula,
        $nombres,
        $apellidos,
        $telefono,
        $fechaNacimiento
    );

    echo $created
        ? "Paciente creado correctamente.\n\n"
        : "No se pudo crear el paciente.\n\n";

    // 4. BUSCAR POR CÉDULA (getPatientByCedula)
    echo "=== 2) Buscar paciente por cédula ===\n";

    $paciente = $client->getPatientByCedula($cedula);

    if ($paciente === null) {
        echo "No se encontró paciente con cédula {$cedula}.\n\n";
    } else {
        // El resultado suele venir como stdClass → lo convertimos a array si se desea
        $pacienteArray = (array) $paciente;
        print_r($pacienteArray);
        echo "\n";
    }

    // 5. LISTAR TODOS (getAllPatients)
    echo "=== 3) Listar todos los pacientes ===\n";

    $lista = $client->getAllPatients();

    if ($lista === null || $lista === []) {
        echo "No hay pacientes registrados.\n\n";
    } else {
        // Puede llegar como array de stdClass o como objeto con propiedad 'paciente'
        if (is_array($lista)) {
            $pacientes = array_map(fn($p) => (array) $p, $lista);
        } elseif (is_object($lista) && isset($lista->paciente)) {
            $pacientes = is_array($lista->paciente)
                ? array_map(fn($p) => (array) $p, $lista->paciente)
                : [ (array) $lista->paciente ];
        } else {
            $pacientes = [];
        }

        foreach ($pacientes as $p) {
            echo "- {$p['cedula']} | {$p['nombres']} {$p['apellidos']} | {$p['telefono']}\n";
        }
        echo "\n";
    }

    // 6. ACTUALIZAR PACIENTE (updatePatient)
    echo "=== 4) Actualizar paciente ===\n";

    $nombresActualizados = 'Juan Camilo';
    $apellidosActualizados = 'Pérez Gómez';
    $telefonoActualizado = '+57 320 987 6543';

    $updated = $client->updatePatient(
        $cedula,
        $nombresActualizados,
        $apellidosActualizados,
        $telefonoActualizado,
        $fechaNacimiento
    );

    echo $updated
        ? "Paciente actualizado correctamente.\n\n"
        : "No se pudo actualizar el paciente.\n\n";

    // 7. ELIMINAR PACIENTE (deletePatient)
    echo "=== 5) Eliminar paciente ===\n";

    $deleted = $client->deletePatient($cedula);

    echo $deleted
        ? "Paciente eliminado correctamente.\n\n"
        : "No se pudo eliminar el paciente.\n\n";

} catch (SoapFault $e) {
    echo "❌ Error SOAP:\n";
    echo "Código: {$e->faultcode}\n";
    echo "Mensaje: {$e->getMessage()}\n\n";

    // Opcional: ver XML crudo de la última request/response
    if (isset($client)) {
        echo "---- Última REQUEST ----\n";
        echo $client->__getLastRequest() . "\n\n";

        echo "---- Última RESPONSE ----\n";
        echo $client->__getLastResponse() . "\n\n";
    }
} catch (Exception $e) {
    echo "❌ Error general: {$e->getMessage()}\n";
}
```

---

## 4. Notas sobre el formato de datos

* **Cédula (`cedula`)**: se maneja como `string` para evitar problemas con ceros a la izquierda.
* **Fechas**: el campo `fecha_nacimiento` se envía en formato `YYYY-MM-DD`.
* **Teléfono**: se maneja como `string` (permite prefijo `+57`, espacios, etc.).
* En el backend, el archivo `storage/pacientes.xml` almacena los nodos `<paciente>` con
  estos campos como hijos.

---

## 5. Consumo desde otros lenguajes (idea general)

Aunque el proyecto está implementado en **PHP**, cualquier lenguaje que soporte
SOAP puede consumir el WSDL de la misma forma:

* **Python**: usando librerías como `zeep`.
* **C#**: agregando un "Service Reference" al WSDL.
* **Java**: usando JAX-WS o herramientas como `wsimport`.

Ejemplo conceptual en pseudo-Python:

```python
from zeep import Client

wsdl = 'http://saludtotal-soap.test/public/wsdl/pacientes.wsdl'
client = Client(wsdl=wsdl)

client.service.createPatient('1001', 'Juan', 'Pérez', '+57 300 123 4567', '1990-05-10')
paciente = client.service.getPatientByCedula('1001')
lista = client.service.getAllPatients()
client.service.updatePatient('1001', 'Juan Camilo', 'Pérez', '+57...', '1990-05-10')
client.service.deletePatient('1001')
```

> **Importante:**
> La seguridad a nivel de rutas e infraestructura (ocultar implementación,
> cifrado de IDs, etc.) se maneja en el backend de SaludTotal.
> El consumidor externo solo necesita el WSDL y las operaciones expuestas.

---

## 6. Resumen

* El contrato WSDL principal está en:
  `http://<tu-dominio>/public/wsdl/pacientes.wsdl`
* Se consumen las operaciones con `SoapClient` desde cualquier script externo.
* El ejemplo de este documento recorre el **CRUD completo**:
  crear, buscar, listar, actualizar y eliminar pacientes.
* Este archivo sirve como guía rápida para pruebas, demos y para otros
  desarrolladores que quieran integrarse con SaludTotal.

