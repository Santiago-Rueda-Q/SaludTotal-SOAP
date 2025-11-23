<?php

namespace App\Services;

use App\Core\XmlManager;

class PatientService
{
    private XmlManager $xml;

    public function __construct()
    {
        $this->xml = new XmlManager();
    }

    public function all(): array
    {
        return $this->xml->all();
    }

    public function findByCedula(string $cedula): ?array
    {
        return $this->xml->findByCedula($cedula);
    }

    public function create(array $data): bool
    {
        return $this->xml->create($data);
    }

    public function update(array $data): bool
    {
        return $this->xml->update($data);
    }

    public function delete(string $cedula): bool
    {
        return $this->xml->delete($cedula);
    }
}
