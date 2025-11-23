<?php

namespace App\Core;

use SoapClient;
use SoapFault;

class SoapClientHandler
{
    private SoapClient $client;

    public function __construct()
    {
        $wsdl = SoapConfig::getWsdlPath();

        $this->client = new SoapClient($wsdl, [
            'trace'      => true,
            'exceptions' => true,
        ]);
    }

    public function createPatient(array $data): bool
    {
        try {
            return $this->client->createPatient(
                $data['cedula'],
                $data['nombres'],
                $data['apellidos'],
                $data['telefono'],
                $data['fecha_nacimiento'],
            );
        } catch (SoapFault $e) {
            error_log('SOAP createPatient error: ' . $e->getMessage());
            return false;
        }
    }

    public function getPatientByCedula(string $cedula): ?array
    {
        try {
            $result = $this->client->getPatientByCedula($cedula);
            if ($result === null) {
                return null;
            }
            // stdClass → array
            return (array) $result;
        } catch (SoapFault $e) {
            error_log('SOAP getPatientByCedula error: ' . $e->getMessage());
            return null;
        }
    }

    public function getAllPatients(): array
    {
        try {
            $result = $this->client->getAllPatients();

            if ($result === null) {
                return [];
            }

            // Puede venir como objeto con propiedad paciente, o como array directo
            if (is_array($result)) {
                // array de stdClass
                return array_map(fn($p) => (array) $p, $result);
            }

            if (is_object($result) && isset($result->paciente)) {
                $pacientes = $result->paciente;
                if (is_array($pacientes)) {
                    return array_map(fn($p) => (array) $p, $pacientes);
                }
                return [ (array) $pacientes ];
            }

            return [];
        } catch (SoapFault $e) {
            error_log('SOAP getAllPatients error: ' . $e->getMessage());
            return [];
        }
    }

    public function updatePatient(array $data): bool
    {
        try {
            return $this->client->updatePatient(
                $data['cedula'],
                $data['nombres'],
                $data['apellidos'],
                $data['telefono'],
                $data['fecha_nacimiento'],
            );
        } catch (SoapFault $e) {
            error_log('SOAP updatePatient error: ' . $e->getMessage());
            return false;
        }
    }

    public function deletePatient(string $cedula): bool
    {
        try {
            return $this->client->deletePatient($cedula);
        } catch (SoapFault $e) {
            error_log('SOAP deletePatient error: ' . $e->getMessage());
            return false;
        }
    }
}
