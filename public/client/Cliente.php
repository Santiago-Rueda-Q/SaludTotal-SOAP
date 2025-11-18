<?php
/**
 * Cliente SOAP - SaludTotal
 * Consumidor del servicio web SOAP
 */

// Autoload de Composer
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Core\SoapClientHandler;
use App\Exceptions\SoapExceptionHandler;

// Inicializar manejador de excepciones
SoapExceptionHandler::init();

/**
 * Clase Cliente para consumir el servicio SOAP
 */
class Cliente
{
    private $soapClient;
    
    public function __construct()
    {
        try {
            $this->soapClient = new SoapClientHandler();
        } catch (Exception $e) {
            throw new Exception("Error al inicializar cliente SOAP: " . $e->getMessage());
        }
    }
    
    /**
     * Crear paciente
     * RF-01: Registrar Paciente
     */
    public function crearPaciente($datos)
    {
        try {
            $response = $this->soapClient->crearPaciente($datos);
            SoapExceptionHandler::logActivity('CREATE_PATIENT', "Cédula: {$datos['cedula']}");
            return $response;
        } catch (Exception $e) {
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
     */
    public function buscarPaciente($cedula)
    {
        try {
            $response = $this->soapClient->buscarPaciente($cedula);
            SoapExceptionHandler::logActivity('SEARCH_PATIENT', "Cédula: {$cedula}");
            return $response;
        } catch (Exception $e) {
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
     */
    public function listarPacientes()
    {
        try {
            $response = $this->soapClient->listarPacientes();
            SoapExceptionHandler::logActivity('LIST_PATIENTS', 'Listado completo');
            return $response;
        } catch (Exception $e) {
            return [
                'exito' => false,
                'mensaje' => 'Error al listar pacientes: ' . $e->getMessage(),
                'datos' => []
            ];
        }
    }
    
    /**
     * Actualizar paciente
     * RF-04: Modificar Paciente
     */
    public function actualizarPaciente($datos)
    {
        try {
            $response = $this->soapClient->actualizarPaciente($datos);
            SoapExceptionHandler::logActivity('UPDATE_PATIENT', "Cédula: {$datos['cedula']}");
            return $response;
        } catch (Exception $e) {
            return [
                'exito' => false,
                'mensaje' => 'Error al actualizar paciente: ' . $e->getMessage(),
                'datos' => null
            ];
        }
    }
    
    /**
     * Eliminar paciente
     * RF-05: Eliminar Paciente
     */
    public function eliminarPaciente($cedula)
    {
        try {
            $response = $this->soapClient->eliminarPaciente($cedula);
            SoapExceptionHandler::logActivity('DELETE_PATIENT', "Cédula: {$cedula}");
            return $response;
        } catch (Exception $e) {
            return [
                'exito' => false,
                'mensaje' => 'Error al eliminar paciente: ' . $e->getMessage(),
                'datos' => null
            ];
        }
    }
}

// Función helper para obtener instancia del cliente
function getCliente()
{
    static $cliente = null;
    if ($cliente === null) {
        $cliente = new Cliente();
    }
    return $cliente;
}