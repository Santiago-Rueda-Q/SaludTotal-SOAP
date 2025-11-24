# Guía de instalación - Gestor Interno de Pacientes (SaludTotal)

Este documento explica cómo instalar y ejecutar el proyecto **Gestor Interno de Pacientes (SaludTotal)** en un entorno local, utilizando PHP y la arquitectura SOAP propuesta para la Clínica SaludTotal.

---

## 1. Requisitos previos

Antes de instalar el proyecto, verifica que cuentas con:

- **PHP 8.x o superior**  
- Extensiones de PHP:
  - `soap` (OBLIGATORIA)
  - `xml` (normalmente viene activada por defecto)
- Servidor web local:
  - Recomendado: **Laragon**  
  - Alternativa: XAMPP/WAMP u otro stack con Apache + PHP
- **Composer** instalado y accesible desde la terminal
- Un navegador web moderno (Chrome, Edge, Firefox)

### 1.1. Verificar PHP y Composer

En una terminal:

```bash
php -v
composer -V
````

Si ves las versiones sin error, estás listo.

### 1.2. Habilitar la extensión SOAP

En tu archivo `php.ini` (Laragon o XAMPP):

1. Busca la línea:

   ```ini
   ;extension=soap
   ```

2. Elimina el punto y coma `;` para activarla:

   ```ini
   extension=soap
   ```

3. Guarda los cambios y **reinicia Apache/Laragon**.

---

## 2. Estructura del proyecto

Al descomprimir el proyecto deberías tener algo similar a:

```text
SaludTotal-SOAP/
├── app/
├── config/
├── docs/
├── public/
│   ├── assets/
│   ├── client/
│   ├── server/
│   ├── views/
│   └── wsdl/
├── storage/
│   └── pacientes.xml
├── composer.json
└── index.php
```

Los puntos clave:

* `public/` → carpeta pública del sistema (HTML/CSS/JS, vistas y punto de entrada web).
* `public/server/index.php` → servidor SOAP.
* `public/client/Cliente.php` → cliente que consume el servicio SOAP.
* `public/wsdl/pacientes.wsdl` → contrato WSDL del servicio.
* `storage/pacientes.xml` → archivo donde se persisten los pacientes.
* `config/soap.php` → configuración del WSDL y endpoint del servidor SOAP.
* `config/bootstrap.php` → carga de clases y configuración base del proyecto.

---

## 3. Instalación en Laragon (recomendado)

### 3.1. Copiar el proyecto

1. Copia la carpeta `SaludTotal-SOAP` a:

   ```text
   C:\laragon\www\
   ```

Te debería quedar:

```text
C:\laragon\www\SaludTotal-SOAP\
```

### 3.2. Instalar dependencias PHP (Composer)

1. Abre una terminal en la carpeta del proyecto:

   ```bash
   cd C:\laragon\www\SaludTotal-SOAP
   ```

2. Ejecuta:

   ```bash
   composer install
   composer dump-autoload -o
   ```

Esto genera el autoload de la carpeta `app/` con el namespace `App\\`.

### 3.3. Verificar el archivo `pacientes.xml`

En `storage/pacientes.xml` debe existir el archivo XML (aunque esté vacío con la estructura base).
Si no existe, crea un archivo llamado **`pacientes.xml`** dentro de `storage/` con contenido mínimo, por ejemplo:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<pacientes></pacientes>
```

> Asegúrate de que PHP tenga permisos de escritura sobre la carpeta `storage/`.

### 3.4. Configurar el Virtual Host en Laragon

1. Abre el menú de Laragon → **www** → **Create site from folder…** (o similar).

2. Selecciona la carpeta `SaludTotal-SOAP`.

3. Asigna un dominio, por ejemplo:

   ```text
   saludtotal-soap.test
   ```

4. Laragon creará el host y apuntará por defecto a la carpeta raíz del proyecto.

5. Reinicia Apache/Nginx desde Laragon.

Con esto podrás acceder a:

```text
http://saludtotal-soap.test/
```

> El archivo `index.php` de la raíz redirige automáticamente a `public/index.php`.

---

## 4. Configuración del WSDL y endpoint SOAP

El archivo clave es:

```text
config/soap.php
```

Su contenido (simplificado) es:

```php
<?php

return [
    'wsdl_path' => ROOT_PATH . '/public/wsdl/pacientes.wsdl',
    'endpoint'  => 'http://saludtotal-soap.test/public/server/index.php',
];
```

### 4.1. Ajustar el endpoint

Si tu dominio o puerto es diferente (por ejemplo, usas `localhost` o un puerto distinto), cambia la URL:

```php
'endpoint'  => 'http://TU-DOMINIO-O-HOST/public/server/index.php',
```

Ejemplos:

```php
// Usando Laragon (recomendado)
'endpoint' => 'http://saludtotal-soap.test/public/server/index.php',

// Usando localhost directamente
'endpoint' => 'http://localhost/SaludTotal-SOAP/public/server/index.php',
```

### 4.2. Verificar la URL en el WSDL

En `public/wsdl/pacientes.wsdl`, al final del archivo encontrarás algo como:

```xml
<soap:address location="http://localhost/SaludTotal-SOAP/public/server/index.php"/>
```

Si estás usando un dominio como `saludtotal-soap.test`, es recomendable actualizar esta URL para que coincida con tu entorno real:

```xml
<soap:address location="http://saludtotal-soap.test/public/server/index.php"/>
```

> MUY IMPORTANTE: El **endpoint en `config/soap.php`** y la **URL en el `<soap:address>` del WSDL** deben apuntar al mismo lugar.

---

## 5. Levantar el sistema

### 5.1. Opción A: Virtual Host con Laragon (recomendado)

Con Laragon ya configurado:

1. Asegúrate de que Apache esté iniciado.
2. Abre en el navegador:

   ```text
   http://saludtotal-soap.test/
   ```

Esto cargará el cliente web de SaludTotal (la interfaz HTML/CSS).

### 5.2. Opción B: Servidor embebido de PHP (alternativa rápida)

Desde la carpeta del proyecto:

```bash
cd C:\laragon\www\SaludTotal-SOAP
php -S localhost:8000 -t public
```

Luego abre en el navegador:

```text
http://localhost:8000/
```

> En este caso, verifica que tu `endpoint` en `config/soap.php` y el `<soap:address>` del WSDL apunten a `http://localhost:8000/server/index.php`.

---

## 6. Verificar que todo está bien

### 6.1. Comprobar PHP + SOAP

Puedes usar la página de diagnóstico incluida:

1. En el navegador visita:

   ```text
   http://saludtotal-soap.test/public/diagnostico.php
   ```

2. Deberías ver la salida de `phpinfo()` y en ella la sección **soap** habilitada.

### 6.2. Probar el servidor SOAP directamente

Abre en el navegador:

```text
http://saludtotal-soap.test/public/server/index.php
```

Si la configuración es correcta, el servidor SOAP debería responder sin errores fatales.

---

## 7. Acceder al cliente web (interfaz de la recepcionista)

La interfaz principal del sistema (cliente SOAP) se encuentra en:

```text
http://saludtotal-soap.test/
```

Desde allí podrás:

* Registrar pacientes (crear)
* Listar todos los pacientes
* Editar información
* Eliminar pacientes

Todas estas acciones se realizan internamente a través del servicio SOAP definido en `public/wsdl/pacientes.wsdl` y servido por `public/server/index.php`.

---

## 8. Resumen rápido de instalación

1. Copiar `SaludTotal-SOAP/` a `C:\laragon\www\`.

2. Ejecutar en la carpeta del proyecto:

   ```bash
   composer install
   composer dump-autoload -o
   ```

3. Verificar `storage/pacientes.xml` y permisos de escritura.

4. Configurar Virtual Host en Laragon → `saludtotal-soap.test`.

5. Ajustar en caso necesario:

   * `config/soap.php` → `endpoint`
   * `public/wsdl/pacientes.wsdl` → `<soap:address location="..."/>`

6. Abrir en el navegador:

   ```text
   http://saludtotal-soap.test/
   ```

Si todo está correcto, tendrás el **Gestor Interno de Pacientes (SaludTotal)** funcionando en tu entorno local listo para la sustentación.

