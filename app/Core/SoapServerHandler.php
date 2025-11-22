<?php
namespace App\Core;

use App\Services\PatientService;
use App\Exceptions\SoapExceptionHandler;
use SoapClient;
use SoapFault;
use SoapVar;
use stdClass;

/**
 * Manejador del Servidor SOAP
 * Implementa las operaciones del servicio web
 */
class SoapServerHandler
{
    private $patientService;
    
    public function __construct()
    {
        $this->patientService = new PatientService();
    }
    
    /**
     * Normalizar entrada - acepta cualquier formato
     */
    private function normalizeInput($input)
    {
        // Si es objeto, convertir a array
        if (is_object($input)) {
            $input = json_decode(json_encode($input), true);
        }
        
        // Si no es array, envolver
        if (!is_array($input)) {
            return [];
        }
        
        // Si tiene una clave 'datos' o 'data', extraerla
        if (isset($input['datos'])) {
            return $this->normalizeInput($input['datos']);
        }
        
        if (isset($input['data'])) {
            return $this->normalizeInput($input['data']);
        }
        
        return $input;
    }
    
    /**
     * Extraer cédula de cualquier formato
     */
    private function extractCedula($input)
    {
        $normalized = $this->normalizeInput($input);
        return $normalized['cedula'] ?? ($input->cedula ?? '');
    }
    
    /**
     * Crear un nuevo paciente
     */
    public function crearPaciente($params)
    {
        try {
            $datosArray = $this->normalizeInput($params);
            
            // Validar datos requeridos
            $required = ['cedula', 'nombres', 'apellidos', 'fecha_nacimiento'];
            foreach ($required as $field) {
                if (empty($datosArray[$field])) {
                    throw new \Exception("El campo {$field} es requerido");
                }
            }
            
            $result = $this->patientService->create($datosArray);
            
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
     */
    public function buscarPaciente($params)
    {
        try {
            $cedula = $this->extractCedula($params);
            
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
     */
    public function listarPacientes($params = null)
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
     */
    public function actualizarPaciente($params)
    {
        try {
            $datosArray = $this->normalizeInput($params);
            
            if (empty($datosArray['cedula'])) {
                throw new \Exception("La cédula es requerida para actualizar");
            }
            
            $paciente = $this->patientService->findByCedula($datosArray['cedula']);
            if (!$paciente) {
                throw new \Exception("Paciente no encontrado");
            }
            
            $result = $this->patientService->update($datosArray['cedula'], $datosArray);
            
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
     */
    public function eliminarPaciente($params)
    {
        try {
            $cedula = $this->extractCedula($params);
            
            if (empty($cedula)) {
                throw new \Exception("La cédula es requerida");
            }
            
            $paciente = $this->patientService->findByCedula($cedula);
            if (!$paciente) {
                throw new \Exception("Paciente no encontrado");
            }
            
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
