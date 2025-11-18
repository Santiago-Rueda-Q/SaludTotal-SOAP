<?php
namespace App\Core;

use SoapClient;
use SoapFault;
use stdClass;

/**
 * Manejador del Cliente SOAP
 * Consume el servicio web SOAP
 */
class SoapClientHandler
{
    private $client;
    private $config;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->config = SoapConfig::getInstance();
        $this->initializeClient();
    }
    
    /**
     * Inicializar cliente SOAP
     */
    private function initializeClient()
    {
        try {
            $wsdlUrl = $this->config->getWsdlUrl();
            $options = $this->config->getClientOptions();
            
            $this->client = new SoapClient($wsdlUrl, $options);
            
        } catch (SoapFault $e) {
            throw new \Exception("Error al conectar con el servicio SOAP: " . $e->getMessage());
        }
    }
    
    /**
     * Crear paciente
     * Consume RF-01
     */
    public function crearPaciente($datos)
    {
        try {
            // Convertir el array a objeto stdClass
            $paciente = new stdClass();
            $paciente->cedula = $datos['cedula'] ?? '';
            $paciente->nombres = $datos['nombres'] ?? '';
            $paciente->apellidos = $datos['apellidos'] ?? '';
            $paciente->telefono = $datos['telefono'] ?? '';
            $paciente->fecha_nacimiento = $datos['fecha_nacimiento'] ?? '';
            $paciente->direccion = $datos['direccion'] ?? '';
            $paciente->email = $datos['email'] ?? '';
            $paciente->genero = $datos['genero'] ?? 'Otro';
            
            // Enviar como parámetro con nombre 'datos'
            $params = ['datos' => $paciente];
            
            $response = $this->client->__soapCall('crearPaciente', [$params]);
            return $this->processResponse($response);
            
        } catch (SoapFault $e) {
            return [
                'exito' => false,
                'mensaje' => 'Error SOAP: ' . $e->getMessage(),
                'datos' => null
            ];
        }
    }
    
    /**
     * Buscar paciente por cédula
     * Consume RF-02
     */
    public function buscarPaciente($cedula)
    {
        try {
            // Crear parámetro como objeto
            $params = new stdClass();
            $params->cedula = $cedula;
            
            $response = $this->client->__soapCall('buscarPaciente', [$params]);
            return $this->processResponse($response);
            
        } catch (SoapFault $e) {
            return [
                'exito' => false,
                'mensaje' => 'Error SOAP: ' . $e->getMessage(),
                'datos' => null
            ];
        }
    }
    
    /**
     * Listar todos los pacientes
     * Consume RF-03
     */
    public function listarPacientes()
    {
        try {
            $response = $this->client->__soapCall('listarPacientes', []);
            return $this->processResponse($response);
            
        } catch (SoapFault $e) {
            return [
                'exito' => false,
                'mensaje' => 'Error SOAP: ' . $e->getMessage(),
                'datos' => []
            ];
        }
    }
    
    /**
     * Actualizar paciente
     * Consume RF-04
     */
    public function actualizarPaciente($datos)
    {
        try {
            // Convertir el array a objeto stdClass
            $paciente = new stdClass();
            $paciente->cedula = $datos['cedula'] ?? '';
            $paciente->nombres = $datos['nombres'] ?? '';
            $paciente->apellidos = $datos['apellidos'] ?? '';
            $paciente->telefono = $datos['telefono'] ?? '';
            $paciente->fecha_nacimiento = $datos['fecha_nacimiento'] ?? '';
            $paciente->direccion = $datos['direccion'] ?? '';
            $paciente->email = $datos['email'] ?? '';
            $paciente->genero = $datos['genero'] ?? 'Otro';
            
            // Enviar como parámetro con nombre 'datos'
            $params = ['datos' => $paciente];
            
            $response = $this->client->__soapCall('actualizarPaciente', [$params]);
            return $this->processResponse($response);
            
        } catch (SoapFault $e) {
            return [
                'exito' => false,
                'mensaje' => 'Error SOAP: ' . $e->getMessage(),
                'datos' => null
            ];
        }
    }
    
    /**
     * Eliminar paciente
     * Consume RF-05
     */
    public function eliminarPaciente($cedula)
    {
        try {
            // Crear parámetro como objeto
            $params = new stdClass();
            $params->cedula = $cedula;
            
            $response = $this->client->__soapCall('eliminarPaciente', [$params]);
            return $this->processResponse($response);
            
        } catch (SoapFault $e) {
            return [
                'exito' => false,
                'mensaje' => 'Error SOAP: ' . $e->getMessage(),
                'datos' => null
            ];
        }
    }
    
    /**
     * Procesar respuesta del servidor
     */
    private function processResponse($response)
    {
        // Manejar diferentes estructuras de respuesta
        if (is_object($response)) {
            return [
                'exito' => $response->exito ?? false,
                'mensaje' => $response->mensaje ?? 'Sin mensaje',
                'datos' => $response->datos ?? null
            ];
        }
        
        if (is_array($response)) {
            return [
                'exito' => $response['exito'] ?? false,
                'mensaje' => $response['mensaje'] ?? 'Sin mensaje',
                'datos' => $response['datos'] ?? null
            ];
        }
        
        return [
            'exito' => false,
            'mensaje' => 'Respuesta inválida del servidor',
            'datos' => null
        ];
    }
    
    /**
     * Obtener funciones disponibles en el WSDL
     */
    public function getFunctions()
    {
        try {
            return $this->client->__getFunctions();
        } catch (SoapFault $e) {
            return [];
        }
    }
    
    /**
     * Obtener tipos disponibles en el WSDL
     */
    public function getTypes()
    {
        try {
            return $this->client->__getTypes();
        } catch (SoapFault $e) {
            return [];
        }
    }
    
    /**
     * Obtener última petición (para debugging)
     */
    public function getLastRequest()
    {
        try {
            return $this->client->__getLastRequest();
        } catch (SoapFault $e) {
            return null;
        }
    }
    
    /**
     * Obtener última respuesta (para debugging)
     */
    public function getLastResponse()
    {
        try {
            return $this->client->__getLastResponse();
        } catch (SoapFault $e) {
            return null;
        }
    }
    
    /**
     * Debug: Imprimir última petición y respuesta
     */
    public function debugLastCall()
    {
        return [
            'request' => $this->getLastRequest(),
            'response' => $this->getLastResponse()
        ];
    }
}