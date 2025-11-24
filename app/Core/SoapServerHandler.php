<?php

namespace App\Core;

use App\Services\PatientService;

class SoapServerHandler
{
    private PatientService $service;

    public function __construct()
    {
        $this->service = new PatientService();
    }

    /**
     * RF-01: Crear paciente
     */
    public function createPatient(string $cedula, string $nombres, string $apellidos, string $telefono, string $fecha_nacimiento): bool
    {
        $data = compact('cedula', 'nombres', 'apellidos', 'telefono', 'fecha_nacimiento');
        return $this->service->create($data);
    }

    /**
     * RF-02: Buscar paciente por cédula
     */
    public function getPatientByCedula(string $cedula)
    {
        $paciente = $this->service->findByCedula($cedula);
        if ($paciente === null) {
            return null;
        }
        return $paciente;
    }

    /**
     * RF-03: Listar todos los pacientes
     */
    public function getAllPatients()
    {
        $pacientes = $this->service->all();
        return $pacientes;
    }

    /**
     * RF-04: Actualizar paciente
     */
    public function updatePatient(string $cedula, string $nombres, string $apellidos, string $telefono, string $fecha_nacimiento): bool
    {
        $data = compact('cedula', 'nombres', 'apellidos', 'telefono', 'fecha_nacimiento');
        return $this->service->update($data);
    }

    /**
     * RF-05: Eliminar paciente
     */
    public function deletePatient(string $cedula): bool
    {
        return $this->service->delete($cedula);
    }
}