## 1. Visión general de la arquitectura

SaludTotal está organizado en una arquitectura por capas, centrada en un **servicio web SOAP** que expone operaciones CRUD sobre pacientes, consumidas por una interfaz web interna.

A alto nivel, los componentes son:

- **Capa de Presentación (Frontend Web)**
  - Vistas HTML/CSS (formularios, tablas, navegación).
  - JavaScript para interacción, validaciones en cliente y alertas.
- **Cliente SOAP (Frontend Lógico)**
  - Orquesta las vistas y se comunica con el servidor SOAP mediante `SoapClient`.
- **Servidor SOAP (Backend / Lógica de Negocio)**
  - Expone las operaciones definidas en el WSDL.
  - Implementa la lógica de negocio sobre pacientes.
- **Capa de Dominio y Servicios**
  - Modelo `Patient`.
  - Servicio `PatientService`.
  - Gestor de XML `XmlManager`.
- **Persistencia**
  - Almacenamiento en archivo `storage/pacientes.xml`.
- **Configuración e Infraestructura**
  - Configuración de WSDL y endpoint SOAP.
  - Bootstrap y autoload de clases vía Composer.

---

## 2. Estructura física del proyecto

Estructura relevante para la arquitectura:

```text
SaludTotal-SOAP/
├── app/
│   ├── Core/
│   │   ├── Router.php
│   │   ├── SoapClientHandler.php
│   │   ├── SoapConfig.php
│   │   ├── SoapServerHandler.php
│   │   └── XmlManager.php
│   ├── Exceptions/
│   │   └── SoapExceptionHandler.php
│   ├── Helpers/
│   │   ├── RouteHelper.php
│   │   ├── SecurityHelper.php
│   │   └── ValidationHelper.php
│   ├── Models/
│   │   └── Patient.php
│   └── Services/
│       └── PatientService.php
├── config/
│   ├── app.php
│   ├── bootstrap.php
│   ├── env.php
│   ├── helpers.php
│   └── soap.php
├── docs/
│   ├── INSTALACION.md
│   ├── arquitectura.md
│   └── ejemplos_consumo.md
├── public/
│   ├── assets/
│   ├── client/
│   │   ├── Cliente.php
│   │   └── routes.php
│   ├── server/
│   │   └── index.php
│   ├── views/
│   │   ├── partials/
│   │   │   ├── header.php
│   │   │   ├── footer.php
│   │   │   └── navbar.php
│   │   ├── index.php
│   │   ├── crear_paciente.php
│   │   ├── listar_pacientes.php
│   │   ├── editar_paciente.php
│   │   └── 404.php
│   ├── wsdl/
│   │   └── pacientes.wsdl
│   └── index.php
├── storage/
│   └── pacientes.xml
├── composer.json
└── index.php
