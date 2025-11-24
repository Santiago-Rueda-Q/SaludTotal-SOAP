<?php

namespace App\Core;

use SimpleXMLElement;

class XmlManager
{
    private string $file;

    public function __construct(?string $file = null)
    {
        $this->file = $file ?? \STORAGE_PATH . '/pacientes.xml';
        $this->ensureFileExists();
    }

    private function ensureFileExists(): void
    {
        if (!file_exists($this->file)) {
            $xml = new SimpleXMLElement('<pacientes></pacientes>');
            $xml->asXML($this->file);
        }
    }

    private function loadXml(): SimpleXMLElement
    {
        $this->ensureFileExists();
        return simplexml_load_file($this->file);
    }

    private function saveXml(SimpleXMLElement $xml): void
    {
        $xml->asXML($this->file);
    }

    public function all(): array
    {
        $xml = $this->loadXml();
        $result = [];
        foreach ($xml->paciente as $paciente) {
            $result[] = [
                'cedula'           => (string) $paciente->cedula,
                'nombres'          => (string) $paciente->nombres,
                'apellidos'        => (string) $paciente->apellidos,
                'telefono'         => (string) $paciente->telefono,
                'fecha_nacimiento' => (string) $paciente->fecha_nacimiento,
            ];
        }
        return $result;
    }

    public function findByCedula(string $cedula): ?array
    {
        $xml = $this->loadXml();
        foreach ($xml->paciente as $paciente) {
            if ((string) $paciente->cedula === $cedula) {
                return [
                    'cedula'           => (string) $paciente->cedula,
                    'nombres'          => (string) $paciente->nombres,
                    'apellidos'        => (string) $paciente->apellidos,
                    'telefono'         => (string) $paciente->telefono,
                    'fecha_nacimiento' => (string) $paciente->fecha_nacimiento,
                ];
            }
        }
        return null;
    }

    public function create(array $data): bool
    {
        $xml = $this->loadXml();

        // evitar duplicados por cédula
        foreach ($xml->paciente as $paciente) {
            if ((string) $paciente->cedula === $data['cedula']) {
                return false;
            }
        }

        $paciente = $xml->addChild('paciente');
        $paciente->addChild('cedula', $data['cedula']);
        $paciente->addChild('nombres', $data['nombres']);
        $paciente->addChild('apellidos', $data['apellidos']);
        $paciente->addChild('telefono', $data['telefono']);
        $paciente->addChild('fecha_nacimiento', $data['fecha_nacimiento']);

        $this->saveXml($xml);
        return true;
    }

    public function update(array $data): bool
    {
        $xml = $this->loadXml();
        $found = false;

        foreach ($xml->paciente as $paciente) {
            if ((string) $paciente->cedula === $data['cedula']) {
                $paciente->nombres          = $data['nombres'];
                $paciente->apellidos        = $data['apellidos'];
                $paciente->telefono         = $data['telefono'];
                $paciente->fecha_nacimiento = $data['fecha_nacimiento'];
                $found = true;
                break;
            }
        }

        if ($found) {
            $this->saveXml($xml);
        }

        return $found;
    }

    public function delete(string $cedula): bool
    {
        $xml = $this->loadXml();
        $index = 0;
        $foundIndex = -1;

        foreach ($xml->paciente as $paciente) {
            if ((string) $paciente->cedula === $cedula) {
                $foundIndex = $index;
                break;
            }
            $index++;
        }

        if ($foundIndex >= 0) {
            unset($xml->paciente[$foundIndex]);
            $this->saveXml($xml);
            return true;
        }

        return false;
    }
}