# 🏥 Gestor Interno de Pacientes (SaludTotal-SOAP)

## 📘 Descripción General

**SaludTotal-SOAP** es un microproyecto desarrollado en **PHP** que implementa un **sistema interno (intranet)** para la **gestión de pacientes** en la *Clínica SaludTotal*.  
El sistema está diseñado bajo la **arquitectura SOAP**, garantizando interoperabilidad, estructura formal de servicios y persistencia de datos en formato **XML**.  

Este proyecto permitirá al personal autorizado (por ejemplo, la recepcionista o el archivista) **registrar, consultar, modificar y eliminar información de pacientes** mediante una interfaz web sencilla y funcional conectada a un **servicio web SOAP**.

---

## 👤 Equipo de Trabajo

| Rol | Nombre |
|------|---------|
| Desarrollador Principal | **Ing. Santiago Rueda Quintero** |

---

## 🧩 Objetivo del Proyecto

Desarrollar una **librería de métodos remotos (servicio web SOAP)** que implemente las operaciones CRUD (Crear, Leer, Actualizar y Eliminar) sobre el registro de pacientes, asegurando la persistencia en un archivo `pacientes.xml`.

---

## ⚙️ Arquitectura del Sistema

El proyecto se construye bajo la **arquitectura SOAP**, con los siguientes componentes:

| Componente | Descripción |
|-------------|-------------|
| **WSDL (Contrato)** | Archivo `pacientes.wsdl` que define formalmente las operaciones del servicio. |
| **Servidor (Backend)** | Implementado con `SoapServer` en PHP, gestiona la lógica de negocio y manipula el archivo `pacientes.xml`. |
| **Cliente (Frontend)** | Aplicación PHP/HTML que consume los servicios SOAP usando `SoapClient`. |
| **Persistencia** | Archivo `pacientes.xml` donde se almacena toda la información de los pacientes. |

---

## 🧠 Enunciado del Problema

Actualmente, el registro de pacientes en la *Clínica SaludTotal* se realiza manualmente, generando **inconsistencias y demoras**.  
Se requiere una **solución informática local (tipo intranet)** que permita centralizar y automatizar la gestión de pacientes desde un único punto de acceso interno.

---

## 🚀 Funcionalidades Principales (Requerimientos Funcionales)

El sistema cumple con los siguientes módulos:

### 🔹 Backend – Servicio SOAP
| Código | Operación | Descripción |
|--------|------------|--------------|
| **RF-01** | Registrar Paciente | Crea un nuevo nodo `<paciente>` en `pacientes.xml`. |
| **RF-02** | Buscar Paciente por Cédula | Devuelve la información completa de un paciente según su cédula. |
| **RF-03** | Listar Todos los Pacientes | Retorna todos los registros almacenados en el XML. |
| **RF-04** | Actualizar Paciente | Modifica la información de un paciente existente. |
| **RF-05** | Eliminar Paciente | Elimina completamente el nodo `<paciente>` correspondiente. |

### 🔹 Frontend – Interfaz Web
| Código | Vista | Descripción |
|--------|--------|--------------|
| **RF-06** | Inicio (`index.php`) | Vista principal con botones de navegación. |
| **RF-07** | Crear Paciente (`crear_paciente.php`) | Formulario para registrar nuevos pacientes. |
| **RF-08** | Listar Pacientes (`listar_pacientes.php`) | Tabla con todos los pacientes y opciones de edición y eliminación. |
| **RF-09** | Editar Paciente (`editar_paciente.php`) | Formulario precargado para actualizar los datos del paciente. |
| **RF-10** | Confirmación de Eliminación | Mensaje de confirmación previo a la eliminación definitiva. |

---

## 🧱 Requerimientos Técnicos

| Código | Requisito | Descripción |
|---------|------------|-------------|
| **RA-01** | Persistencia | Los datos se guardan en `pacientes.xml`. |
| **RA-02** | Contrato WSDL | Se define `pacientes.wsdl` con las operaciones SOAP. |
| **RA-03** | Servidor | Implementado en PHP con `SoapServer`. |
| **RA-04** | Cliente | Implementado en PHP con `SoapClient`. |
| **RA-05** | Interfaz Web | Desarrollada con HTML y CSS responsivo. |
| **RA-06** | Lenguaje | Implementado completamente en **PHP**. |

---

## 💾 Estructura del Proyecto
```bash
GINPAC-SOAP/
│
├── wsdl/
│ └── pacientes.wsdl
│
├── backend/
│ ├── servidor.php
│ └── pacientes.xml
│
├── frontend/
│ ├── index.php
│ ├── crear_paciente.php
│ ├── listar_pacientes.php
│ ├── editar_paciente.php
│ └── assets/
│ ├── css/
│ └── js/
│
└── README.md
```
---

## 🧪 Flujo General del Sistema

1. El **cliente (frontend)** envía una solicitud SOAP al servidor.
2. El **servidor (backend)** interpreta la solicitud mediante `SoapServer` y ejecuta la función correspondiente.
3. El **servidor** modifica o consulta el archivo `pacientes.xml`.
4. Se devuelve una **respuesta SOAP** al cliente, que la muestra en la interfaz HTML.

---

## 🧰 Tecnologías Utilizadas

- **Lenguaje:** PHP 8+
- **Framework de Comunicación:** SOAP (`SoapServer`, `SoapClient`)
- **Almacenamiento:** XML (`pacientes.xml`)
- **Frontend:** HTML5, CSS3
- **Servidor Web:** Apache (Laragon, XAMPP o similar)
- **Formato de Contrato:** WSDL 1.1

---

## 🧾 Evaluación del Proyecto

| Área | Peso | Criterios |
|------|------|------------|
| **Código y Funcionalidad** | 2.0 pts | Arquitectura SOAP, CRUD completo, persistencia XML, documentación técnica. |
| **Socialización / Sustentación** | 2.0 pts | Demostración funcional y dominio técnico del flujo Cliente ↔ WSDL ↔ Servidor ↔ XML. |
| **Diseño y Creatividad** | 1.0 pts | Flujo funcional, usabilidad, diseño visual y coherencia de vistas. |

---

## 📅 Entrega

- **Fecha límite:** Lunes **24 de noviembre de 2025**, antes de las **9:30 a.m.**
- **Modo de entrega:** Repositorio GitHub y sustentación en vivo.
- **Periodo de sustentación:** 9:30 a.m. – 12:00 p.m.

---

## 🧠 Autoría

> Proyecto desarrollado por **Ing. Santiago Rueda Quintero** como microproyecto académico bajo la línea de **Arquitectura de Software Distribuido**.  
>  
> El propósito es aplicar los principios de interoperabilidad mediante la arquitectura **SOAP** con servicios web y persistencia XML, consolidando el conocimiento práctico en sistemas cliente-servidor desarrollados en PHP.

---

## 🏁 Estado del Proyecto

> 🟡 En desarrollo — versión inicial de arquitectura SOAP en fase de implementación.

---

## 📄 Licencia

Este proyecto es de carácter académico y se distribuye bajo la licencia **MIT License**.

---
