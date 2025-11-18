# 🚀 Guía de Instalación Rápida - SaludTotal SOAP

## ⚡ Instalación Express (5 minutos)

### Paso 1: Requisitos Previos
Asegúrate de tener instalado:
- ✅ Laragon (o XAMPP/WAMP)
- ✅ PHP 7.4 o superior
- ✅ MySQL 5.7 o superior
- ✅ Composer

### Paso 2: Descargar el Proyecto
```bash
# Si tienes git
git clone [URL-del-repositorio]

# O simplemente descomprimir el archivo ZIP en:
C:\laragon\www\SaludTotal-SOAP
```

### Paso 3: Instalar Dependencias
```bash
cd C:\laragon\www\SaludTotal-SOAP
composer install
```

### Paso 4: Crear Base de Datos

**Opción A: Desde phpMyAdmin**
1. Abrir phpMyAdmin: `http://localhost/phpmyadmin`
2. Crear base de datos: `saludtotal`
3. Seleccionar la base de datos
4. Ir a la pestaña "SQL"
5. Copiar y ejecutar el contenido de `database.sql`

**Opción B: Desde línea de comandos**
```bash
mysql -u root -p
CREATE DATABASE saludtotal;
USE saludtotal;
SOURCE database.sql;
EXIT;
```

### Paso 5: Configurar .env

Editar el archivo `.env` con tus datos:
```env
DB_HOST=localhost
DB_PORT=3306
DB_NAME=saludtotal
DB_USER=root
DB_PASS=

# IMPORTANTE: Ajustar estas URLs según tu configuración
SOAP_WSDL_URL=http://localhost/SaludTotal-SOAP/public/wsdl/pacientes.wsdl
SOAP_SERVER_URL=http://localhost/SaludTotal-SOAP/public/server/index.php
```

### Paso 6: Verificar Permisos

**En Windows (Laragon):**
No necesitas hacer nada, los permisos ya están configurados.

**En Linux/Mac:**
```bash
chmod -R 775 storage/
chmod -R 775 storage/logs/
```

### Paso 7: Probar el Sistema

1. **Iniciar Laragon** (o tu servidor web)

2. **Probar el Servidor SOAP:**
   ```
   http://localhost/SaludTotal-SOAP/public/tests/test_server.php
   ```

3. **Probar el Cliente SOAP:**
   ```
   http://localhost/SaludTotal-SOAP/public/tests/test_client.php
   ```

4. **Acceder al Sistema:**
   ```
   http://localhost/SaludTotal-SOAP
   ```

---

## 🎯 URLs Importantes

| Descripción | URL |
|-------------|-----|
| **Sistema Principal** | http://localhost/SaludTotal-SOAP |
| **Servidor SOAP** | http://localhost/SaludTotal-SOAP/public/server/index.php |
| **WSDL** | http://localhost/SaludTotal-SOAP/public/wsdl/pacientes.wsdl |
| **API REST** | http://localhost/SaludTotal-SOAP/public/client/routes.php |
| **Test Servidor** | http://localhost/SaludTotal-SOAP/public/tests/test_server.php |
| **Test Cliente** | http://localhost/SaludTotal-SOAP/public/tests/test_client.php |

---

## ✅ Verificación de la Instalación

### 1. Test del Servidor
Ejecuta: `http://localhost/SaludTotal-SOAP/public/tests/test_server.php`

Debe mostrar todos los checks en verde:
- ✅ Extensión PHP SOAP
- ✅ Archivo WSDL
- ✅ Configuración del Sistema
- ✅ Conexión a Base de Datos
- ✅ Servidor SOAP
- ✅ Permisos de Escritura

### 2. Test del Cliente
Ejecuta: `http://localhost/SaludTotal-SOAP/public/tests/test_client.php`

Debe ejecutar exitosamente:
- ✅ Listar pacientes (RF-03)
- ✅ Crear paciente (RF-01)
- ✅ Buscar paciente (RF-02)
- ✅ Actualizar paciente (RF-04)
- ✅ Eliminar paciente (RF-05)

---

## 🐛 Solución de Problemas Comunes

### Error: "SOAP-ERROR: Parsing WSDL"
**Causa:** El archivo WSDL no se encuentra o la URL es incorrecta.

**Solución:**
1. Verificar que el archivo `public/wsdl/pacientes.wsdl` existe
2. Verificar la URL en `.env` (debe coincidir con tu configuración)
3. En el WSDL, cambiar la línea:
   ```xml
   <soap:address location="http://localhost/SaludTotal-SOAP/public/server/index.php"/>
   ```
   Por tu URL correcta si es diferente.

### Error: "Access denied for user"
**Causa:** Credenciales de base de datos incorrectas.

**Solución:**
1. Verificar usuario y contraseña en `.env`
2. Probar la conexión manualmente desde phpMyAdmin

### Error: "Class 'SoapClient' not found"
**Causa:** Extensión PHP SOAP no habilitada.

**Solución:**
1. Abrir `php.ini`
2. Buscar `;extension=soap`
3. Quitar el `;` para habilitar: `extension=soap`
4. Reiniciar Apache/Laragon

### Error: "Table 'saludtotal.pacientes' doesn't exist"
**Causa:** La base de datos no fue creada correctamente.

**Solución:**
1. Ejecutar el script `database.sql` nuevamente
2. Verificar que la base de datos `saludtotal` existe

### Error: "Permission denied" en storage/logs
**Causa:** Sin permisos de escritura.

**Solución (Linux/Mac):**
```bash
sudo chmod -R 775 storage/
sudo chown -R www-data:www-data storage/
```

**Solución (Windows):**
Generalmente no es necesario, pero si ocurre:
1. Click derecho en carpeta `storage`
2. Propiedades > Seguridad
3. Dar permisos completos

---

## 📖 Datos de Prueba

El sistema incluye 3 pacientes de ejemplo:

| Cédula | Nombre | Teléfono |
|--------|--------|----------|
| 1234567890 | Juan Carlos Pérez Gómez | 3001234567 |
| 0987654321 | María Fernanda Rodríguez López | 3109876543 |
| 1122334455 | Carlos Alberto Martínez Silva | 3201122334 |

---

## 🎨 Personalización

### Cambiar el Logo
Reemplazar el archivo:
```
public/assets/img/logo_saludtotal.png
```
Tamaño recomendado: 200x200px (PNG con fondo transparente)

### Cambiar Colores
Editar en `public/assets/css/style.css`:
```css
:root {
    --primary-color: #39aaa7;    /* Color principal */
    --secondary-color: #9bc352;   /* Color secundario */
    --dark-gray: #696a69;         /* Gris oscuro */
}
```

### Cambiar Nombre de la Clínica
Editar en `.env`:
```env
APP_NAME=TuClinica
```

---

## 📚 Documentación Adicional

Para más información, consultar:
- `README.md` - Documentación completa
- `docs/arquitectura.md` - Arquitectura del sistema
- `docs/endpoints.md` - Documentación de endpoints
- `docs/ejemplos_consumo.md` - Ejemplos de consumo del servicio

---

## 🆘 Soporte

Si tienes problemas:
1. Revisar los logs en `storage/logs/`
2. Ejecutar los tests de diagnóstico
3. Consultar la documentación completa
4. Contactar a: info@saludtotal.com

---

## ✨ ¡Listo!

Si todos los tests pasaron, tu sistema está funcionando correctamente.

Accede a: **http://localhost/SaludTotal-SOAP**

¡Disfruta usando SaludTotal! 🎉