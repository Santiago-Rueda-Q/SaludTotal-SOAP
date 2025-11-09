<?php
namespace GinpacSoap\Services;

use GinpacSoap\Core\SoapConfig;
use GinpacSoap\Core\XmlManager;
use GinpacSoap\Models\Patient;
use SoapFault;

class PatientService
{
    private XmlManager $xml;

    public function __construct()
    {
        $this->xml = new XmlManager(SoapConfig::storageXml());
    }

    // RF-01
    public function RegistrarPaciente(array $paciente): bool
    {
        $p = new Patient($paciente);
        if ($p->cedula === '') throw new SoapFault('Client', 'Cédula requerida');

        $xml = $this->xml->all();
        foreach ($xml->paciente as $node) {
            if ((string)$node->cedula === $p->cedula) {
                throw new SoapFault('Client', 'La cédula ya existe');
            }
        }
        $n = $xml->addChild('paciente');
        $n->addChild('cedula', $p->cedula);
        $n->addChild('nombres', $p->nombres);
        $n->addChild('apellidos', $p->apellidos);
        $n->addChild('telefono', $p->telefono);
        $n->addChild('fecha_nacimiento', $p->fecha_nacimiento);

        return $this->xml->save($xml);
    }

    // RF-02
    public function BuscarPaciente(array $req): array
    {
        $cedula = (string)($req['cedula'] ?? '');
        if ($cedula === '') throw new SoapFault('Client', 'Cédula requerida');
        $xml = $this->xml->all();
        foreach ($xml->paciente as $node) {
            if ((string)$node->cedula === $cedula) {
                return [
                    'cedula' => (string)$node->cedula,
                    'nombres' => (string)$node->nombres,
                    'apellidos' => (string)$node->apellidos,
                    'telefono' => (string)$node->telefono,
                    'fecha_nacimiento' => (string)$node->fecha_nacimiento,
                ];
            }
        }
        throw new SoapFault('Server', 'Paciente no encontrado');
    }

    // RF-03
    public function ListarPacientes(): array
    {
        $xml = $this->xml->all();
        $out = [];
        foreach ($xml->paciente as $node) {
            $out[] = [
                'cedula' => (string)$node->cedula,
                'nombres' => (string)$node->nombres,
                'apellidos' => (string)$node->apellidos,
                'telefono' => (string)$node->telefono,
                'fecha_nacimiento' => (string)$node->fecha_nacimiento,
            ];
        }
        return ['paciente' => $out]; // coincide con PacientesArray
    }

    // RF-04
    public function ActualizarPaciente(array $paciente): bool
    {
        $p = new Patient($paciente);
        if ($p->cedula === '') throw new SoapFault('Client', 'Cédula requerida');

        $xml = $this->xml->all();
        foreach ($xml->paciente as $node) {
            if ((string)$node->cedula === $p->cedula) {
                $node->nombres = $p->nombres;
                $node->apellidos = $p->apellidos;
                $node->telefono = $p->telefono;
                $node->fecha_nacimiento = $p->fecha_nacimiento;
                return $this->xml->save($xml);
            }
        }
        throw new SoapFault('Server', 'Paciente no encontrado');
    }

    // RF-05
    public function EliminarPaciente(array $req): bool
    {
        $cedula = (string)($req['cedula'] ?? '');
        if ($cedula === '') throw new SoapFault('Client', 'Cédula requerida');

        $xml = $this->xml->all();
        $i = 0;
        foreach ($xml->paciente as $node) {
            if ((string)$node->cedula === $cedula) {
                unset($xml->paciente[$i]);
                return $this->xml->save($xml);
            }
            $i++;
        }
        throw new SoapFault('Server', 'Paciente no encontrado');
    }
}
