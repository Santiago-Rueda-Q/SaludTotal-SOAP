<?php
namespace App\Core;

/**
 * Clase de configuración para el servicio SOAP
 */
class SoapConfig
{
    private static $instance = null;
    private $config = [];
    
    private function __construct()
    {
        $this->loadConfig();
    }
    
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function loadConfig()
    {
        $rootPath = dirname(dirname(__DIR__));
        
        // Cargar configuración de la aplicación
        $appConfigFile = $rootPath . '/config/app.php';
        $soapConfigFile = $rootPath . '/config/soap.php';
        
        $appConfig = [];
        $soapConfig = [];
        
        // Cargar app.php de forma segura
        if (file_exists($appConfigFile)) {
            $appConfig = require $appConfigFile;
            if (!is_array($appConfig)) {
                $appConfig = [];
            }
        }
        
        // Cargar soap.php de forma segura
        if (file_exists($soapConfigFile)) {
            $soapConfig = require $soapConfigFile;
            if (!is_array($soapConfig)) {
                $soapConfig = [];
            }
        }
        
        // Merge con valores por defecto
        $this->config = array_merge([
            'name' => 'SaludTotal',
            'env' => 'production',
            'debug' => false,
            'url' => 'http://localhost',
            'base_path' => '',
            'timezone' => 'America/Bogota',
            'error_reporting' => 0,
            'display_errors' => 0
        ], $appConfig);
        
        // Agregar configuración SOAP
        $this->config['soap_config'] = $soapConfig;
        
        // Configurar zona horaria
        if (isset($this->config['timezone'])) {
            date_default_timezone_set($this->config['timezone']);
        }
        
        // Configurar errores
        if (isset($this->config['error_reporting'])) {
            error_reporting($this->config['error_reporting']);
        }
        if (isset($this->config['display_errors'])) {
            ini_set('display_errors', $this->config['display_errors']);
        }
    }
    
    public function get($key, $default = null)
    {
        $keys = explode('.', $key);
        $value = $this->config;
        
        foreach ($keys as $k) {
            if (!isset($value[$k])) {
                return $default;
            }
            $value = $value[$k];
        }
        
        return $value;
    }
    
    public function all()
    {
        return $this->config;
    }
    
    public function getDatabase()
    {
        return $this->config['database'] ?? [];
    }
    
    public function getSoap()
    {
        return $this->config['soap_config'] ?? [];
    }
    
    public function getWsdlUrl()
    {
        return $this->config['soap']['wsdl_url'] 
            ?? $this->config['soap_config']['wsdl_url'] 
            ?? 'http://localhost/public/wsdl/pacientes.wsdl';
    }
    
    public function getServerUrl()
    {
        return $this->config['soap']['server_url'] 
            ?? $this->config['soap_config']['server_url'] 
            ?? 'http://localhost/public/server/index.php';
    }
    
    public function getServerOptions()
    {
        return $this->config['soap_config']['server_options'] ?? [];
    }
    
    public function getClientOptions()
    {
        return $this->config['soap_config']['client_options'] ?? [];
    }
}