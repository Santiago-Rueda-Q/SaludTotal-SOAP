# Clínica SaludTotal – Servicio SOAP de Gestión de Pacientes

Proyecto monolítico en PHP que implementa un servicio web SOAP para gestionar pacientes de la clínica **SaludTotal**, utilizando un archivo XML (`storage/pacientes.xml`) como sistema de persistencia.

## Componentes principales

- **Backend SOAP**
  - `public/server/index.php`: servidor SOAP (SoapServer) que expone operaciones CRUD.
  - `public/wsdl/pacientes.wsdl`: contrato WSDL con las 5 operaciones.
  - `app/Core/SoapServerHandler.php`: implementación de los métodos remotos.
  - `app/Services/PatientService.php`: lógica de negocio.
  - `app/Core/XmlManager.php`: manejo de lectura/escritura sobre `pacientes.xml`.

- **Cliente (Frontend)**
  - `public/index.php` → `public/client/Cliente.php`: enrutador básico.
  - Vistas en `public/views/*.php` (Inicio, Crear, Listar, Editar).
  - `app/Core/SoapClientHandler.php`: cliente SOAP (SoapClient) para consumir el WSDL.

## Requisitos

- PHP 8.0 o superior con la extensión **soap** habilitada.
- Servidor local (Laragon, XAMPP, WAMP, etc.).

## Instalación rápida

1. Copiar el proyecto a su servidor local (por ejemplo `C:\laragon\www\SaludTotal-SOAP`).
2. Verificar que PHP tenga habilitada la extensión `soap` en `php.ini`.
3. Asegurarse de que el archivo `storage/pacientes.xml` sea escribible por PHP.
4. Ajustar la URL del servicio en `public/wsdl/pacientes.wsdl` si no usa `http://localhost/SaludTotal-SOAP/public/server/index.php`.

## Uso

- Acceda a: `http://localhost/SaludTotal-SOAP/public/index.php`
- Desde la pantalla principal podrá:
  - Registrar un nuevo paciente
  - Ver el listado de pacientes
  - Editar y eliminar pacientes

El flujo cumple con los requerimientos:

- RF-01 a RF-05: CRUD en el servicio SOAP
- RF-06 a RF-10: flujo de vistas y operaciones desde el cliente HTML/CSS.
