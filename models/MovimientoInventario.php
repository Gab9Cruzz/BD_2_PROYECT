<?php
/**
 * Modelo MovimientoInventario
 * Gestiona los movimientos de stock (entradas y salidas)
 */

class MovimientoInventario {
    private $conn;
    private $table = "MovimientoInventario";

    public $id_movimiento;
    public $id_producto;
    public $tipo_movimiento;
    public $cantidad;
    public $fecha_movimiento;
    public $descripcion;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Registrar movimiento de inventario
     */
    public function registrar() {
        $query = "INSERT INTO " . $this->table . " 
                  (id_producto, tipo_movimiento, cantidad, descripcion) 
                  VALUES 
                  (:id_producto, :tipo_movimiento, :cantidad, :descripcion)";
        
        try {
            $stmt = $this->conn->prepare($query);
            
            $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
            
            $stmt->bindParam(":id_producto", $this->id_producto);
            $stmt->bindParam(":tipo_movimiento", $this->tipo_movimiento);
            $stmt->bindParam(":cantidad", $this->cantidad);
            $stmt->bindParam(":descripcion", $this->descripcion);
            
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
     * Obtener todos los movimientos
     */
    public function obtenerTodos() {
        $query = "SELECT m.*, p.nombre AS producto_nombre 
                  FROM " . $this->table . " m
                  INNER JOIN Producto p ON m.id_producto = p.id_producto
                  ORDER BY m.fecha_movimiento DESC";
        
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
     * Obtener movimientos por producto
     */
    public function obtenerPorProducto() {
        $query = "SELECT m.*, p.nombre AS producto_nombre 
                  FROM " . $this->table . " m
                  INNER JOIN Producto p ON m.id_producto = p.id_producto
                  WHERE m.id_producto = :id_producto
                  ORDER BY m.fecha_movimiento DESC";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id_producto", $this->id_producto);
            $stmt->execute();
            return $stmt;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}
?>
