<?php
/**
 * Modelo Proveedor
 * Gestiona las operaciones de proveedores
 */

class Proveedor {
    private $conn;
    private $table = "Proveedor";

    public $id_proveedor;
    public $nombre;
    public $telefono;
    public $correo;
    public $direccion;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Obtener todos los proveedores
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
}
?>
