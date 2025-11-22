<?php
namespace App\Core;

/**
 * Clase de configuracion para el servicio SOAP
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

        // Asegurar que las variables de entorno esten disponibles
        $envFile = $rootPath . '/config/env.php';
        if (!function_exists('env') && file_exists($envFile)) {
            require_once $envFile;
        }
        
        // Cargar configuracion de la aplicacion
        $appConfigFile = $rootPath . '/config/app.php';
        $soapConfigFile = $rootPath . '/config/soap.php';
        
        $appConfig = [];
        $soapConfig = [];
        
        if (file_exists($appConfigFile)) {
            $appConfig = require $appConfigFile;
            if (!is_array($appConfig)) {
                $appConfig = [];
            }
        }
        
        if (file_exists($soapConfigFile)) {
            $soapConfig = require $soapConfigFile;
            if (!is_array($soapConfig)) {
                $soapConfig = [];
            }
        }
        
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
        
        $this->config['soap_config'] = $soapConfig;
        
        if (isset($this->config['timezone'])) {
            date_default_timezone_set($this->config['timezone']);
        }

        // Detectar base_path automaticamente si no esta definido
        if (empty($this->config['base_path'])) {
            $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
            $detectedBase = str_replace('\\', '/', dirname($scriptName));
            $this->config['base_path'] = ($detectedBase === '/' || $detectedBase === '.') 
                ? '' 
                : rtrim($detectedBase, '/');
        } else {
            $this->config['base_path'] = rtrim($this->config['base_path'], '/');
        }
        
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
