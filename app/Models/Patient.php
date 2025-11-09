<?php
namespace GinpacSoap\Models;

class Patient
{
    public string $cedula;
    public string $nombres;
    public string $apellidos;
    public string $telefono;
    public string $fecha_nacimiento;

    public function __construct(array $data)
    {
        $this->cedula = trim((string)($data['cedula'] ?? ''));
        $this->nombres = trim((string)($data['nombres'] ?? ''));
        $this->apellidos = trim((string)($data['apellidos'] ?? ''));
        $this->telefono = trim((string)($data['telefono'] ?? ''));
        $this->fecha_nacimiento = trim((string)($data['fecha_nacimiento'] ?? ''));
    }

    public function toArray(): array
    {
        return [
            'cedula' => $this->cedula,
            'nombres' => $this->nombres,
            'apellidos' => $this->apellidos,
            'telefono' => $this->telefono,
            'fecha_nacimiento' => $this->fecha_nacimiento,
        ];
    }
}
