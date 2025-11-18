<?php
namespace App\Services;

use App\Models\Patient;
use App\Helpers\ValidationHelper;

/**
 * Servicio de Pacientes
 * Capa de lógica de negocio para gestión de pacientes
 */
class PatientService
{
    private $patientModel;
    private $validator;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->patientModel = new Patient();
        $this->validator = new ValidationHelper();
    }
    
    /**
     * Crear nuevo paciente
     */
    public function create($data)
    {
        // Validar datos
        $this->validatePatientData($data, true);
        
        // Verificar que no exista la cédula
        if ($this->patientModel->existsByCedula($data['cedula'])) {
            throw new \Exception("Ya existe un paciente con la cédula {$data['cedula']}");
        }
        
        // Crear paciente
        return $this->patientModel->create($data);
    }
    
    /**
     * Buscar paciente por cédula
     */
    public function findByCedula($cedula)
    {
        if (empty($cedula)) {
            throw new \Exception("La cédula es requerida");
        }
        
        return $this->patientModel->findByCedula($cedula);
    }
    
    /**
     * Obtener todos los pacientes
     */
    public function getAll()
    {
        return $this->patientModel->getAll();
    }
    
    /**
     * Actualizar paciente
     */
    public function update($cedula, $data)
    {
        // Validar que exista el paciente
        $existingPatient = $this->patientModel->findByCedula($cedula);
        if (!$existingPatient) {
            throw new \Exception("Paciente no encontrado con cédula {$cedula}");
        }
        
        // Validar datos (sin requerir todos los campos)
        $this->validatePatientData($data, false);
        
        // Actualizar
        return $this->patientModel->update($cedula, $data);
    }
    
    /**
     * Eliminar paciente
     */
    public function delete($cedula)
    {
        // Validar que exista el paciente
        $existingPatient = $this->patientModel->findByCedula($cedula);
        if (!$existingPatient) {
            throw new \Exception("Paciente no encontrado con cédula {$cedula}");
        }
        
        return $this->patientModel->delete($cedula);
    }
    
    /**
     * Validar datos del paciente
     */
    private function validatePatientData($data, $requireAll = true)
    {
        // Validar cédula
        if ($requireAll || isset($data['cedula'])) {
            if (empty($data['cedula'])) {
                throw new \Exception("La cédula es requerida");
            }
            if (!$this->validator->validateCedula($data['cedula'])) {
                throw new \Exception("Cédula inválida");
            }
        }
        
        // Validar nombres
        if ($requireAll || isset($data['nombres'])) {
            if (empty($data['nombres'])) {
                throw new \Exception("Los nombres son requeridos");
            }
            if (!$this->validator->validateText($data['nombres'], 3, 100)) {
                throw new \Exception("Nombres inválidos (3-100 caracteres)");
            }
        }
        
        // Validar apellidos
        if ($requireAll || isset($data['apellidos'])) {
            if (empty($data['apellidos'])) {
                throw new \Exception("Los apellidos son requeridos");
            }
            if (!$this->validator->validateText($data['apellidos'], 3, 100)) {
                throw new \Exception("Apellidos inválidos (3-100 caracteres)");
            }
        }
        
        // Validar fecha de nacimiento
        if ($requireAll || isset($data['fecha_nacimiento'])) {
            if (empty($data['fecha_nacimiento'])) {
                throw new \Exception("La fecha de nacimiento es requerida");
            }
            if (!$this->validator->validateDate($data['fecha_nacimiento'])) {
                throw new \Exception("Fecha de nacimiento inválida (formato: YYYY-MM-DD)");
            }
            if (!$this->validator->validateAge($data['fecha_nacimiento'], 0, 120)) {
                throw new \Exception("Edad inválida (0-120 años)");
            }
        }
        
        // Validar teléfono (opcional)
        if (!empty($data['telefono'])) {
            if (!$this->validator->validatePhone($data['telefono'])) {
                throw new \Exception("Teléfono inválido (10 dígitos)");
            }
        }
        
        // Validar email (opcional)
        if (!empty($data['email'])) {
            if (!$this->validator->validateEmail($data['email'])) {
                throw new \Exception("Email inválido");
            }
        }
        
        // Validar género (opcional)
        if (!empty($data['genero'])) {
            if (!in_array($data['genero'], ['M', 'F', 'Otro'])) {
                throw new \Exception("Género inválido (M, F, Otro)");
            }
        }
        
        return true;
    }
    
    /**
     * Buscar pacientes por nombre o apellido
     */
    public function search($term)
    {
        $allPatients = $this->patientModel->getAll();
        $term = strtolower($term);
        
        return array_filter($allPatients, function($patient) use ($term) {
            $fullName = strtolower($patient->nombres . ' ' . $patient->apellidos);
            return strpos($fullName, $term) !== false || 
                   strpos(strtolower($patient->cedula), $term) !== false;
        });
    }
    
    /**
     * Obtener estadísticas de pacientes
     */
    public function getStatistics()
    {
        $patients = $this->patientModel->getAll();
        
        return [
            'total' => count($patients),
            'por_genero' => [
                'M' => count(array_filter($patients, fn($p) => $p->genero === 'M')),
                'F' => count(array_filter($patients, fn($p) => $p->genero === 'F')),
                'Otro' => count(array_filter($patients, fn($p) => $p->genero === 'Otro'))
            ]
        ];
    }
}