<?php
namespace App\Core;

use PDO;
use PDOException;

/**
 * Gestor de conexión a base de datos y persistencia XML
 * Maneja tanto la base de datos MySQL como el archivo XML de respaldo
 */
class XmlManager
{
    private static $instance = null;
    private $pdo = null;
    private $xmlPath;
    
    /**
     * Constructor privado para Singleton
     */
    private function __construct()
    {
        $this->xmlPath = dirname(dirname(__DIR__)) . '/storage/pacientes.xml';
        $this->connect();
        $this->initializeXml();
    }
    
    /**
     * Obtener instancia única
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Conectar a la base de datos
     */
    private function connect()
    {
        try {
            $config = SoapConfig::getInstance()->getDatabase();
            
            $dsn = sprintf(
                "mysql:host=%s;port=%s;dbname=%s;charset=%s",
                $config['host'],
                $config['port'],
                $config['name'],
                $config['charset']
            );
            
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            $this->pdo = new PDO($dsn, $config['user'], $config['password'], $options);
            
        } catch (PDOException $e) {
            throw new \Exception("Error de conexión a la base de datos: " . $e->getMessage());
        }
    }
    
    /**
     * Obtener conexión PDO
     */
    public function getConnection()
    {
        return $this->pdo;
    }
    
    /**
     * Inicializar archivo XML si no existe
     */
    private function initializeXml()
    {
        if (!file_exists($this->xmlPath)) {
            $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><pacientes></pacientes>');
            $this->saveXml($xml);
        }
    }
    
    /**
     * Cargar XML
     */
    public function loadXml()
    {
        if (!file_exists($this->xmlPath)) {
            $this->initializeXml();
        }
        return simplexml_load_file($this->xmlPath);
    }
    
    /**
     * Guardar XML
     */
    public function saveXml($xml)
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml->asXML());
        return $dom->save($this->xmlPath);
    }
    
    /**
     * Sincronizar base de datos con XML
     */
    public function syncToXml()
    {
        try {
            $stmt = $this->pdo->query("SELECT * FROM pacientes WHERE estado = 'Activo' ORDER BY id ASC");
            $pacientes = $stmt->fetchAll();
            
            $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><pacientes></pacientes>');
            
            foreach ($pacientes as $paciente) {
                $pacienteNode = $xml->addChild('paciente');
                $pacienteNode->addAttribute('id', $paciente['id']);
                
                foreach ($paciente as $key => $value) {
                    if ($key !== 'id' && $value !== null) {
                        $pacienteNode->addChild($key, htmlspecialchars($value));
                    }
                }
            }
            
            $this->saveXml($xml);
            return true;
            
        } catch (\Exception $e) {
            throw new \Exception("Error al sincronizar XML: " . $e->getMessage());
        }
    }
    
    /**
     * Obtener ruta del archivo XML
     */
    public function getXmlPath()
    {
        return $this->xmlPath;
    }
    
    /**
     * Ejecutar consulta preparada
     */
    public function execute($sql, $params = [])
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            throw new \Exception("Error en consulta: " . $e->getMessage());
        }
    }
    
    /**
     * Obtener último ID insertado
     */
    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }
    
    /**
     * Iniciar transacción
     */
    public function beginTransaction()
    {
        return $this->pdo->beginTransaction();
    }
    
    /**
     * Confirmar transacción
     */
    public function commit()
    {
        return $this->pdo->commit();
    }
    
    /**
     * Revertir transacción
     */
    public function rollback()
    {
        return $this->pdo->rollback();
    }
}