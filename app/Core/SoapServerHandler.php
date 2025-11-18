<?php
namespace App\Core;

use App\Services\PatientService;
use App\Exceptions\SoapExceptionHandler;

/**
 * Manejador del Servidor SOAP
 * Implementa las operaciones del servicio web
 */
class SoapServerHandler
{
    private $patientService;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->patientService = new PatientService();
    }
    
    /**
     * Crear un nuevo paciente
     * RF-01: Registrar Paciente
     * 
     * @param array $datos Datos del paciente
     * @return array Respuesta con resultado de la operación
     */
    public function crearPaciente($datos)
    {
        try {
            // Validar datos requeridos
            $required = ['cedula', 'nombres', 'apellidos', 'fecha_nacimiento'];
            foreach ($required as $field) {
                if (empty($datos[$field])) {
                    throw new \Exception("El campo {$field} es requerido");
                }
            }
            
            // Crear paciente
            $result = $this->patientService->create($datos);
            
            return [
                'exito' => true,
                'mensaje' => 'Paciente creado exitosamente',
                'datos' => $result
            ];
            
        } catch (\Exception $e) {
            return [
                'exito' => false,
                'mensaje' => 'Error al crear paciente: ' . $e->getMessage(),
                'datos' => null
            ];
        }
    }
    
    /**
     * Buscar paciente por cédula
     * RF-02: Buscar Paciente por Cédula
     * 
     * @param string $cedula Cédula del paciente
     * @return array Respuesta con datos del paciente
     */
    public function buscarPaciente($cedula)
    {
        try {
            if (empty($cedula)) {
                throw new \Exception("La cédula es requerida");
            }
            
            $paciente = $this->patientService->findByCedula($cedula);
            
            if (!$paciente) {
                return [
                    'exito' => false,
                    'mensaje' => 'Paciente no encontrado',
                    'datos' => null
                ];
            }
            
            return [
                'exito' => true,
                'mensaje' => 'Paciente encontrado',
                'datos' => $paciente
            ];
            
        } catch (\Exception $e) {
            return [
                'exito' => false,
                'mensaje' => 'Error al buscar paciente: ' . $e->getMessage(),
                'datos' => null
            ];
        }
    }
    
    /**
     * Listar todos los pacientes
     * RF-03: Listar Todos los Pacientes
     * 
     * @return array Respuesta con lista de pacientes
     */
    public function listarPacientes()
    {
        try {
            $pacientes = $this->patientService->getAll();
            
            return [
                'exito' => true,
                'mensaje' => 'Pacientes obtenidos exitosamente',
                'datos' => $pacientes
            ];
            
        } catch (\Exception $e) {
            return [
                'exito' => false,
                'mensaje' => 'Error al listar pacientes: ' . $e->getMessage(),
                'datos' => []
            ];
        }
    }
    
    /**
     * Actualizar datos de un paciente
     * RF-04: Modificar Paciente
     * 
     * @param array $datos Datos actualizados del paciente (incluye cedula)
     * @return array Respuesta con resultado de la operación
     */
    public function actualizarPaciente($datos)
    {
        try {
            if (empty($datos['cedula'])) {
                throw new \Exception("La cédula es requerida para actualizar");
            }
            
            // Verificar que el paciente existe
            $paciente = $this->patientService->findByCedula($datos['cedula']);
            if (!$paciente) {
                throw new \Exception("Paciente no encontrado");
            }
            
            // Actualizar paciente
            $result = $this->patientService->update($datos['cedula'], $datos);
            
            return [
                'exito' => true,
                'mensaje' => 'Paciente actualizado exitosamente',
                'datos' => $result
            ];
            
        } catch (\Exception $e) {
            return [
                'exito' => false,
                'mensaje' => 'Error al actualizar paciente: ' . $e->getMessage(),
                'datos' => null
            ];
        }
    }
    
    /**
     * Eliminar un paciente
     * RF-05: Eliminar Paciente
     * 
     * @param string $cedula Cédula del paciente a eliminar
     * @return array Respuesta con resultado de la operación
     */
    public function eliminarPaciente($cedula)
    {
        try {
            if (empty($cedula)) {
                throw new \Exception("La cédula es requerida");
            }
            
            // Verificar que el paciente existe
            $paciente = $this->patientService->findByCedula($cedula);
            if (!$paciente) {
                throw new \Exception("Paciente no encontrado");
            }
            
            // Eliminar paciente
            $result = $this->patientService->delete($cedula);
            
            return [
                'exito' => true,
                'mensaje' => 'Paciente eliminado exitosamente',
                'datos' => ['cedula' => $cedula]
            ];
            
        } catch (\Exception $e) {
            return [
                'exito' => false,
                'mensaje' => 'Error al eliminar paciente: ' . $e->getMessage(),
                'datos' => null
            ];
        }
    }
}