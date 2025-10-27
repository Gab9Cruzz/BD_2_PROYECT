<?php
/**
 * Modelo Cliente
 * Gestiona las operaciones CRUD de clientes
 */

class Cliente {
    private $conn;
    private $table = "Cliente";

    // Propiedades del cliente
    public $id_cliente;
    public $nombre;
    public $direccion;
    public $correo;

    /**
     * Constructor
     */
    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Obtener todos los clientes
     */
    public function obtenerTodos() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY nombre ASC";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Obtener un cliente por ID
     */
    public function obtenerPorId() {
        $query = "SELECT * FROM " . $this->table . " WHERE id_cliente = :id_cliente LIMIT 1";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id_cliente", $this->id_cliente);
            $stmt->execute();
            
            $row = $stmt->fetch();
            if($row) {
                $this->nombre = $row['nombre'];
                $this->direccion = $row['direccion'];
                $this->correo = $row['correo'];
                return true;
            }
            return false;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Crear nuevo cliente
     */
    public function crear() {
        $query = "INSERT INTO " . $this->table . " 
                  (nombre, direccion, correo) 
                  VALUES 
                  (:nombre, :direccion, :correo)";
        
        try {
            $stmt = $this->conn->prepare($query);
            
            // Sanitizar datos
            $this->nombre = htmlspecialchars(strip_tags($this->nombre));
            $this->direccion = htmlspecialchars(strip_tags($this->direccion));
            $this->correo = htmlspecialchars(strip_tags($this->correo));
            
            // Bind de parámetros
            $stmt->bindParam(":nombre", $this->nombre);
            $stmt->bindParam(":direccion", $this->direccion);
            $stmt->bindParam(":correo", $this->correo);
            
            if($stmt->execute()) {
                $this->id_cliente = $this->conn->lastInsertId();
                return true;
            }
            return false;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Actualizar cliente
     */
    public function actualizar() {
        $query = "UPDATE " . $this->table . " 
                  SET nombre = :nombre, 
                      direccion = :direccion, 
                      correo = :correo
                  WHERE id_cliente = :id_cliente";
        
        try {
            $stmt = $this->conn->prepare($query);
            
            // Sanitizar datos
            $this->nombre = htmlspecialchars(strip_tags($this->nombre));
            $this->direccion = htmlspecialchars(strip_tags($this->direccion));
            $this->correo = htmlspecialchars(strip_tags($this->correo));
            
            // Bind de parámetros
            $stmt->bindParam(":nombre", $this->nombre);
            $stmt->bindParam(":direccion", $this->direccion);
            $stmt->bindParam(":correo", $this->correo);
            $stmt->bindParam(":id_cliente", $this->id_cliente);
            
            if($stmt->execute()) {
                return true;
            }
            return false;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Eliminar cliente
     */
    public function eliminar() {
        $query = "DELETE FROM " . $this->table . " WHERE id_cliente = :id_cliente";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id_cliente", $this->id_cliente);
            
            if($stmt->execute()) {
                return true;
            }
            return false;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Obtener teléfonos de un cliente
     */
    public function obtenerTelefonos() {
        $query = "SELECT * FROM Telefono_Cliente WHERE id_cliente = :id_cliente";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id_cliente", $this->id_cliente);
            $stmt->execute();
            return $stmt;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Agregar teléfono a cliente
     */
    public function agregarTelefono($telefono) {
        $query = "INSERT INTO Telefono_Cliente (id_cliente, telefono) VALUES (:id_cliente, :telefono)";
        
        try {
            $stmt = $this->conn->prepare($query);
            $telefono = htmlspecialchars(strip_tags($telefono));
            
            $stmt->bindParam(":id_cliente", $this->id_cliente);
            $stmt->bindParam(":telefono", $telefono);
            
            if($stmt->execute()) {
                return true;
            }
            return false;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Eliminar teléfono de cliente
     */
    public function eliminarTelefono($id_telefono) {
        $query = "DELETE FROM Telefono_Cliente WHERE id_telefono = :id_telefono AND id_cliente = :id_cliente";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id_telefono", $id_telefono);
            $stmt->bindParam(":id_cliente", $this->id_cliente);
            
            if($stmt->execute()) {
                return true;
            }
            return false;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}
?>
