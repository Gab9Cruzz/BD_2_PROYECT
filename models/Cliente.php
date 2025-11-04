<?php

class Cliente {
    private $conn;
    private $table = "clientes";

    public $id;
    public $tipo_identificacion;
    public $numero_identificacion;
    public $nombres;
    public $apellidos;
    public $telefono;
    public $email;
    public $direccion;
    public $ciudad;
    public $provincia;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function obtenerTodos() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY nombres, apellidos ASC";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function obtenerPorId() {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $this->id);
            $stmt->execute();
            
            $row = $stmt->fetch();
            if($row) {
                $this->tipo_identificacion = $row['tipo_identificacion'];
                $this->numero_identificacion = $row['numero_identificacion'];
                $this->nombres = $row['nombres'];
                $this->apellidos = $row['apellidos'];
                $this->telefono = $row['telefono'];
                $this->email = $row['email'];
                $this->direccion = $row['direccion'];
                $this->ciudad = $row['ciudad'];
                $this->provincia = $row['provincia'];
                return true;
            }
            return false;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function crear() {
        $query = "INSERT INTO " . $this->table . " 
                  (tipo_identificacion, numero_identificacion, nombres, apellidos, telefono, email, direccion, ciudad, provincia) 
                  VALUES 
                  (:tipo_identificacion, :numero_identificacion, :nombres, :apellidos, :telefono, :email, :direccion, :ciudad, :provincia)";
        
        try {
            $stmt = $this->conn->prepare($query);
            
            $this->numero_identificacion = htmlspecialchars(strip_tags($this->numero_identificacion));
            $this->nombres = htmlspecialchars(strip_tags($this->nombres));
            $this->apellidos = htmlspecialchars(strip_tags($this->apellidos));
            $this->telefono = htmlspecialchars(strip_tags($this->telefono));
            $this->email = htmlspecialchars(strip_tags($this->email));
            $this->direccion = htmlspecialchars(strip_tags($this->direccion));
            $this->ciudad = htmlspecialchars(strip_tags($this->ciudad));
            $this->provincia = htmlspecialchars(strip_tags($this->provincia));
            
            $stmt->bindParam(":tipo_identificacion", $this->tipo_identificacion);
            $stmt->bindParam(":numero_identificacion", $this->numero_identificacion);
            $stmt->bindParam(":nombres", $this->nombres);
            $stmt->bindParam(":apellidos", $this->apellidos);
            $stmt->bindParam(":telefono", $this->telefono);
            $stmt->bindParam(":email", $this->email);
            $stmt->bindParam(":direccion", $this->direccion);
            $stmt->bindParam(":ciudad", $this->ciudad);
            $stmt->bindParam(":provincia", $this->provincia);
            
            if($stmt->execute()) {
                $this->id = $this->conn->lastInsertId();
                return true;
            }
            return false;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function actualizar() {
        $query = "UPDATE " . $this->table . " 
                  SET tipo_identificacion = :tipo_identificacion,
                      numero_identificacion = :numero_identificacion,
                      nombres = :nombres,
                      apellidos = :apellidos,
                      telefono = :telefono,
                      email = :email,
                      direccion = :direccion,
                      ciudad = :ciudad,
                      provincia = :provincia
                  WHERE id = :id";
        
        try {
            $stmt = $this->conn->prepare($query);
            
            $this->numero_identificacion = htmlspecialchars(strip_tags($this->numero_identificacion));
            $this->nombres = htmlspecialchars(strip_tags($this->nombres));
            $this->apellidos = htmlspecialchars(strip_tags($this->apellidos));
            $this->telefono = htmlspecialchars(strip_tags($this->telefono));
            $this->email = htmlspecialchars(strip_tags($this->email));
            $this->direccion = htmlspecialchars(strip_tags($this->direccion));
            $this->ciudad = htmlspecialchars(strip_tags($this->ciudad));
            $this->provincia = htmlspecialchars(strip_tags($this->provincia));
            
            $stmt->bindParam(":tipo_identificacion", $this->tipo_identificacion);
            $stmt->bindParam(":numero_identificacion", $this->numero_identificacion);
            $stmt->bindParam(":nombres", $this->nombres);
            $stmt->bindParam(":apellidos", $this->apellidos);
            $stmt->bindParam(":telefono", $this->telefono);
            $stmt->bindParam(":email", $this->email);
            $stmt->bindParam(":direccion", $this->direccion);
            $stmt->bindParam(":ciudad", $this->ciudad);
            $stmt->bindParam(":provincia", $this->provincia);
            $stmt->bindParam(":id", $this->id);
            
            if($stmt->execute()) {
                return true;
            }
            return false;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function eliminar() {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $this->id);
            
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
