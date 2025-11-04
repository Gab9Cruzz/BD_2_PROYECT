<?php

class MovimientoInventario {
    private $conn;
    private $table = "movimientos_inventario";

    public $id;
    public $producto_id;
    public $tipo_movimiento;
    public $cantidad;
    public $motivo;
    public $usuario;
    public $fecha_movimiento;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function registrar() {
        $query = "INSERT INTO " . $this->table . " 
                  (producto_id, tipo_movimiento, cantidad, motivo, usuario) 
                  VALUES 
                  (:producto_id, :tipo_movimiento, :cantidad, :motivo, :usuario)";
        
        try {
            $stmt = $this->conn->prepare($query);
            
            $this->motivo = htmlspecialchars(strip_tags($this->motivo));
            $this->usuario = htmlspecialchars(strip_tags($this->usuario));
            
            $stmt->bindParam(":producto_id", $this->producto_id);
            $stmt->bindParam(":tipo_movimiento", $this->tipo_movimiento);
            $stmt->bindParam(":cantidad", $this->cantidad);
            $stmt->bindParam(":motivo", $this->motivo);
            $stmt->bindParam(":usuario", $this->usuario);
            
            if($stmt->execute()) {
                return true;
            }
            return false;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function obtenerTodos() {
        $query = "SELECT * FROM vista_movimientos_inventario";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function obtenerPorProducto() {
        $query = "SELECT m.*, p.nombre AS producto_nombre 
                  FROM " . $this->table . " m
                  INNER JOIN productos p ON m.producto_id = p.id
                  WHERE m.producto_id = :producto_id
                  ORDER BY m.fecha_movimiento DESC";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":producto_id", $this->producto_id);
            $stmt->execute();
            return $stmt;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}
?>
