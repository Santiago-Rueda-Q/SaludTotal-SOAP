<?php
namespace App\Models;

use App\Core\XmlManager;

/**
 * Modelo de Paciente
 * Representa la entidad Paciente y maneja la lógica de datos
 */
class Patient
{
    private $db;
    
    // Propiedades del paciente
    public $id;
    public $cedula;
    public $nombres;
    public $apellidos;
    public $telefono;
    public $fecha_nacimiento;
    public $direccion;
    public $email;
    public $genero;
    public $estado;
    public $created_at;
    public $updated_at;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->db = XmlManager::getInstance();
    }
    
    /**
     * Crear nuevo paciente
     */
    public function create($data)
    {
        try {
            $this->db->beginTransaction();
            
            $sql = "INSERT INTO pacientes (cedula, nombres, apellidos, telefono, fecha_nacimiento, 
                    direccion, email, genero, estado) 
                    VALUES (:cedula, :nombres, :apellidos, :telefono, :fecha_nacimiento, 
                    :direccion, :email, :genero, :estado)";
            
            $params = [
                ':cedula' => $data['cedula'],
                ':nombres' => $data['nombres'],
                ':apellidos' => $data['apellidos'],
                ':telefono' => $data['telefono'] ?? null,
                ':fecha_nacimiento' => $data['fecha_nacimiento'],
                ':direccion' => $data['direccion'] ?? null,
                ':email' => $data['email'] ?? null,
                ':genero' => $data['genero'] ?? 'Otro',
                ':estado' => 'Activo'
            ];
            
            $this->db->execute($sql, $params);
            $lastId = $this->db->lastInsertId();
            
            // Sincronizar con XML
            $this->db->syncToXml();
            
            $this->db->commit();
            
            return $this->findById($lastId);
            
        } catch (\Exception $e) {
            $this->db->rollback();
            throw new \Exception("Error al crear paciente: " . $e->getMessage());
        }
    }
    
    /**
     * Buscar paciente por ID
     */
    public function findById($id)
    {
        try {
            $sql = "SELECT * FROM pacientes WHERE id = :id AND estado = 'Activo'";
            $stmt = $this->db->execute($sql, [':id' => $id]);
            $result = $stmt->fetch();
            
            return $result ? $this->mapToObject($result) : null;
            
        } catch (\Exception $e) {
            throw new \Exception("Error al buscar paciente: " . $e->getMessage());
        }
    }
    
    /**
     * Buscar paciente por cédula
     */
    public function findByCedula($cedula)
    {
        try {
            $sql = "SELECT * FROM pacientes WHERE cedula = :cedula AND estado = 'Activo'";
            $stmt = $this->db->execute($sql, [':cedula' => $cedula]);
            $result = $stmt->fetch();
            
            return $result ? $this->mapToObject($result) : null;
            
        } catch (\Exception $e) {
            throw new \Exception("Error al buscar paciente: " . $e->getMessage());
        }
    }
    
    /**
     * Obtener todos los pacientes
     */
    public function getAll()
    {
        try {
            $sql = "SELECT * FROM pacientes WHERE estado = 'Activo' ORDER BY apellidos, nombres";
            $stmt = $this->db->execute($sql);
            $results = $stmt->fetchAll();
            
            return array_map([$this, 'mapToObject'], $results);
            
        } catch (\Exception $e) {
            throw new \Exception("Error al obtener pacientes: " . $e->getMessage());
        }
    }
    
    /**
     * Actualizar paciente
     */
    public function update($cedula, $data)
    {
        try {
            $this->db->beginTransaction();
            
            // Construir query dinámicamente solo con campos presentes
            $fields = [];
            $params = [':cedula' => $cedula];
            
            $allowedFields = ['nombres', 'apellidos', 'telefono', 'fecha_nacimiento', 
                              'direccion', 'email', 'genero'];
            
            foreach ($allowedFields as $field) {
                if (isset($data[$field]) && $data[$field] !== '') {
                    $fields[] = "{$field} = :{$field}";
                    $params[":{$field}"] = $data[$field];
                }
            }
            
            if (empty($fields)) {
                throw new \Exception("No hay campos para actualizar");
            }
            
            $sql = "UPDATE pacientes SET " . implode(', ', $fields) . " WHERE cedula = :cedula";
            
            $this->db->execute($sql, $params);
            
            // Sincronizar con XML
            $this->db->syncToXml();
            
            $this->db->commit();
            
            return $this->findByCedula($cedula);
            
        } catch (\Exception $e) {
            $this->db->rollback();
            throw new \Exception("Error al actualizar paciente: " . $e->getMessage());
        }
    }
    
    /**
     * Eliminar paciente (soft delete)
     */
    public function delete($cedula)
    {
        try {
            $this->db->beginTransaction();
            
            $sql = "UPDATE pacientes SET estado = 'Inactivo' WHERE cedula = :cedula";
            $this->db->execute($sql, [':cedula' => $cedula]);
            
            // Sincronizar con XML
            $this->db->syncToXml();
            
            $this->db->commit();
            
            return true;
            
        } catch (\Exception $e) {
            $this->db->rollback();
            throw new \Exception("Error al eliminar paciente: " . $e->getMessage());
        }
    }
    
    /**
     * Mapear array a objeto
     */
    private function mapToObject($data)
    {
        $patient = new \stdClass();
        $patient->id = $data['id'];
        $patient->cedula = $data['cedula'];
        $patient->nombres = $data['nombres'];
        $patient->apellidos = $data['apellidos'];
        $patient->telefono = $data['telefono'];
        $patient->fecha_nacimiento = $data['fecha_nacimiento'];
        $patient->direccion = $data['direccion'];
        $patient->email = $data['email'];
        $patient->genero = $data['genero'];
        $patient->estado = $data['estado'];
        $patient->created_at = $data['created_at'];
        $patient->updated_at = $data['updated_at'];
        
        return $patient;
    }
    
    /**
     * Validar si existe un paciente por cédula
     */
    public function existsByCedula($cedula)
    {
        try {
            $sql = "SELECT COUNT(*) as count FROM pacientes WHERE cedula = :cedula AND estado = 'Activo'";
            $stmt = $this->db->execute($sql, [':cedula' => $cedula]);
            $result = $stmt->fetch();
            
            return $result['count'] > 0;
            
        } catch (\Exception $e) {
            throw new \Exception("Error al validar cédula: " . $e->getMessage());
        }
    }
}