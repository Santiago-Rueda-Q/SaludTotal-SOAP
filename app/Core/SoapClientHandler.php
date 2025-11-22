<?php
namespace App\Core;

use SoapClient;
use SoapFault;

class SoapClientHandler
{
    private $client;
    private $config;
    
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
            $serverUrl = $this->config->getServerUrl();
            
            // Opciones del cliente
            $options = [
                'location' => $serverUrl,
                'uri' => 'http://saludtotal.com/soap/pacientes',
                'trace' => 1,
                'exceptions' => true,
                'encoding' => 'UTF-8',
                'soap_version' => SOAP_1_2,
                'cache_wsdl' => WSDL_CACHE_NONE,
                'connection_timeout' => 30,
                'features' => SOAP_SINGLE_ELEMENT_ARRAYS
            ];
            
            // Log para debug
            error_log("SOAP Client - WSDL: $wsdlUrl");
            error_log("SOAP Client - Location: $serverUrl");
            
            // Verificar que el WSDL existe
            if (!file_exists(str_replace('http://saludtotal-soap.test', $_SERVER['DOCUMENT_ROOT'] . '/..', $wsdlUrl))) {
                throw new \Exception("WSDL no encontrado: $wsdlUrl");
            }
            
            $this->client = new SoapClient($wsdlUrl, $options);
            
            error_log("SOAP Client inicializado correctamente");
            
        } catch (\Exception $e) {
            error_log("Error SOAP Client: " . $e->getMessage());
            throw new \Exception("Error al conectar con el servicio SOAP: " . $e->getMessage());
        }
    }
    
    /**
     * Crear paciente
     */
    public function crearPaciente($datos)
    {
        try {
            $response = $this->client->__soapCall('crearPaciente', [
                ['datos' => $datos]
            ]);
            
            return $this->parseResponse($response);
            
        } catch (SoapFault $e) {
            return $this->handleSoapError($e);
        }
    }
    
    /**
     * Buscar paciente por cédula
     */
    public function buscarPaciente($cedula)
    {
        try {
            $response = $this->client->__soapCall('buscarPaciente', [
                ['cedula' => $cedula]
            ]);
            
            return $this->parseResponse($response);
            
        } catch (SoapFault $e) {
            return $this->handleSoapError($e);
        }
    }
    
    /**
     * Listar pacientes
     */
    public function listarPacientes()
    {
        try {
            $response = $this->client->__soapCall('listarPacientes', []);
            
            return $this->parseResponse($response);
            
        } catch (SoapFault $e) {
            return $this->handleSoapError($e);
        }
    }
    
    /**
     * Actualizar paciente
     */
    public function actualizarPaciente($datos)
    {
        try {
            $response = $this->client->__soapCall('actualizarPaciente', [
                ['datos' => $datos]
            ]);
            
            return $this->parseResponse($response);
            
        } catch (SoapFault $e) {
            return $this->handleSoapError($e);
        }
    }
    
    /**
     * Eliminar paciente
     */
    public function eliminarPaciente($cedula)
    {
        try {
            $response = $this->client->__soapCall('eliminarPaciente', [
                ['cedula' => $cedula]
            ]);
            
            return $this->parseResponse($response);
            
        } catch (SoapFault $e) {
            return $this->handleSoapError($e);
        }
    }
    
    /**
     * Parsear respuesta SOAP
     */
    private function parseResponse($response)
    {
        // Si ya es un array asociativo, retornarlo
        if (is_array($response)) {
            return $response;
        }
        
        // Si es un objeto stdClass, convertirlo a array
        if (is_object($response)) {
            return [
                'exito' => $response->exito ?? false,
                'mensaje' => $response->mensaje ?? '',
                'datos' => $response->datos ?? null
            ];
        }
        
        return [
            'exito' => false,
            'mensaje' => 'Respuesta inválida del servidor',
            'datos' => null
        ];
    }
    
    /**
     * Manejar errores SOAP
     */
    private function handleSoapError(SoapFault $e)
    {
        error_log("SOAP Fault: " . $e->getMessage());
        error_log("SOAP Fault Code: " . $e->faultcode);
        error_log("SOAP Fault String: " . $e->faultstring);
        
        if ($this->client) {
            error_log("Last Request Headers:\n" . $this->client->__getLastRequestHeaders());
            error_log("Last Request:\n" . $this->client->__getLastRequest());
            error_log("Last Response Headers:\n" . $this->client->__getLastResponseHeaders());
            error_log("Last Response:\n" . $this->client->__getLastResponse());
        }
        
        return [
            'exito' => false,
            'mensaje' => 'Error SOAP: ' . $e->getMessage(),
            'datos' => null
        ];
    }
}