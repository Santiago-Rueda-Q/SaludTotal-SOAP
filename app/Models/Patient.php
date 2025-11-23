<?php

namespace App\Models;

class Patient
{
    public string $cedula;
    public string $nombres;
    public string $apellidos;
    public string $telefono;
    public string $fecha_nacimiento;

    public function __construct(array $data = [])
    {
        $this->cedula           = $data['cedula'] ?? '';
        $this->nombres          = $data['nombres'] ?? '';
        $this->apellidos        = $data['apellidos'] ?? '';
        $this->telefono         = $data['telefono'] ?? '';
        $this->fecha_nacimiento = $data['fecha_nacimiento'] ?? '';
    }

    public function toArray(): array
    {
        return [
            'cedula'           => $this->cedula,
            'nombres'          => $this->nombres,
            'apellidos'        => $this->apellidos,
            'telefono'         => $this->telefono,
            'fecha_nacimiento' => $this->fecha_nacimiento,
        ];
    }
}
